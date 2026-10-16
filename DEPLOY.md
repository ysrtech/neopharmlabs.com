# Installing neopharmlabs.com on a server

This guide assumes a fresh **Ubuntu 24.04** server with **Nginx, PHP 8.3 (PHP-FPM) and MySQL 8 / MariaDB 10.11**, with the site at `/var/www/neopharmlabs.com`, run by a `deploy` user. Change names and paths to match your server.

The install path below (production `composer install`, MySQL, migrations, content import, admin user, caches) was tested end to end on MariaDB 10.11 + PHP 8.3.

What the app does **not** need:
- **Node/npm:** the site's CSS/JS and the Twill admin assets are already built and committed in `public/assets`.
- **Queue worker or cron:** form emails are sent right away, and there are no scheduled tasks.
- **Redis.**

---

## 1. Server packages

```bash
sudo apt update
sudo apt install -y nginx git unzip curl \
  php8.3-fpm php8.3-cli php8.3-mysql php8.3-mbstring php8.3-xml php8.3-curl \
  php8.3-zip php8.3-gd php8.3-intl php8.3-bcmath php8.3-sqlite3
sudo apt install -y mysql-server        # or: mariadb-server

# Composer
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

`php8.3-gd` is required: images are resized on the fly by Glide at `/img/...`.

## 2. PHP upload limits

The job application form accepts a CV and a cover letter of up to 10 MB each, and editors upload images in the CMS. Raise the PHP limits:

```bash
sudo tee /etc/php/8.3/fpm/conf.d/99-neopharm.ini >/dev/null <<'EOF'
upload_max_filesize = 20M
post_max_size = 30M
memory_limit = 256M
EOF
sudo systemctl restart php8.3-fpm
```

## 3. Database

```bash
sudo mysql <<'EOF'
CREATE DATABASE neopharmlabs CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'neopharmlabs'@'localhost' IDENTIFIED BY 'CHANGE-ME-strong-password';
GRANT ALL PRIVILEGES ON neopharmlabs.* TO 'neopharmlabs'@'localhost';
FLUSH PRIVILEGES;
EOF
```

## 4. Get the code

```bash
sudo mkdir -p /var/www/neopharmlabs.com
sudo chown deploy:www-data /var/www/neopharmlabs.com
cd /var/www/neopharmlabs.com
git clone https://github.com/ysrtech/neopharmlabs.com.git .
# until PR #1 is merged:  git checkout claude/project-thread-or6uqu

composer install --no-dev --prefer-dist --optimize-autoloader
```

The repo is private, so the server needs access to it: a deploy key, or an HTTPS token.

## 5. Configure `.env`

```bash
cp .env.example .env
php artisan key:generate
nano .env
```

Set at least the following:

```dotenv
APP_ENV=production
APP_DEBUG=false
APP_URL=https://www.neopharmlabs.com
LOG_LEVEL=warning

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=neopharmlabs
DB_USERNAME=neopharmlabs
DB_PASSWORD=CHANGE-ME-strong-password

# Outgoing mail (see step 11)
MAIL_MAILER=smtp
MAIL_HOST=smtp.example.com
MAIL_PORT=587
MAIL_USERNAME=...
MAIL_PASSWORD=...
MAIL_FROM_ADDRESS="no-reply@neopharmlabs.com"
MAIL_FROM_NAME="Neopharm Labs"

# Who receives each website form (comma separated)
FORMS_NOTIFY_CONTACT=info@neopharmlabs.com
FORMS_NOTIFY_CAREERS=cv@neopharmlabs.com
FORMS_NOTIFY_PRIVACY=drh@neopharmlabs.com
GOOGLE_ANALYTICS_ID=G-36RQ7WMKFX
```

`APP_URL` must be the real public address (with `https://`). The admin's "view live" links and the image URLs are built from it.

## 6. Database tables, content import and admin user

```bash
php artisan migrate --force
php artisan storage:link
php artisan neopharm:import
php artisan twill:superadmin you@neopharmlabs.com 'a-strong-password'
```

The import loads everything recovered from the archive (pages, services, blog, resources, team, jobs, images, and 106 redirects from old URLs). It should end with a table showing 60 pages, 60 services, 53 posts and 121 images.

Run the import **only on the first install**. `php artisan neopharm:import --fresh` wipes all CMS content and re-imports it, which would erase any edits made in the admin.

## 7. File permissions

```bash
sudo chown -R deploy:www-data storage bootstrap/cache
sudo chmod -R ug+rwX storage bootstrap/cache
```

## 8. Production caches

```bash
php artisan optimize
```

Re-run `php artisan optimize` after every `.env` change or code update.

## 9. Nginx

Create `/etc/nginx/sites-available/neopharmlabs.com`:

```nginx
server {
    listen 80;
    server_name neopharmlabs.com www.neopharmlabs.com;
    root /var/www/neopharmlabs.com/public;
    index index.php;

    client_max_body_size 30M;
    charset utf-8;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* { deny all; }
}
```

Then enable it:

```bash
sudo ln -s /etc/nginx/sites-available/neopharmlabs.com /etc/nginx/sites-enabled/
sudo nginx -t && sudo systemctl reload nginx
```

Don't add a `location ~* \.(jpg|png)$` block that serves files directly. Images under `/img/...` are generated by Laravel and must reach PHP.

## 10. DNS and SSL

1. Point the `A` records for `neopharmlabs.com` and `www.neopharmlabs.com` to the server.
2. Get a certificate:

```bash
sudo apt install -y certbot python3-certbot-nginx
sudo certbot --nginx -d neopharmlabs.com -d www.neopharmlabs.com --redirect
```

The original site lived on `www.`. To keep Google's links, redirect the bare domain to `www`. After certbot runs, add this as its own server block (certbot will have put the certificate lines in the main block; copy them across):

```nginx
server {
    listen 443 ssl;
    server_name neopharmlabs.com;
    # ssl_certificate / ssl_certificate_key lines from certbot
    return 301 https://www.neopharmlabs.com$request_uri;
}
```

Then remove `neopharmlabs.com` from the main block's `server_name` and reload Nginx.

## 11. Mail

Every form submission is saved in the CMS under **Form submissions**, and is also emailed to the `FORMS_NOTIFY_*` addresses. For delivery, use the client's mail provider SMTP (Microsoft 365, Google Workspace) or a sending service such as Postmark, Mailgun or SES. Then set `MAIL_*` in `.env` and run `php artisan optimize`.

Test it by submitting the contact form at `/contact`.

## 12. Check it

- `https://www.neopharmlabs.com/`, `/services`, `/blog`, `/fr` all load.
- An old URL redirects, for example `/stability-services` goes to `/services/stability-testing`.
- `https://www.neopharmlabs.com/admin` lets you log in with the user from step 6.
- `https://www.neopharmlabs.com/sitemap.xml` loads. Submit it in Google Search Console, since the hacked site may have left bad URLs indexed.
- `storage/logs/laravel.log` has no errors.

---

## Updating the site later

```bash
cd /var/www/neopharmlabs.com
git pull
composer install --no-dev --prefer-dist --optimize-autoloader
php artisan migrate --force
php artisan optimize
sudo systemctl reload php8.3-fpm
```

## Backups

The old site was lost because there was no backup, so set one up from day one. Back up two things:
- The database, for example a nightly `mysqldump neopharmlabs | gzip > /backups/neopharmlabs-$(date +%F).sql.gz` from cron.
- `storage/app`, which holds uploaded images and job application CVs.

Keep copies off the server.
