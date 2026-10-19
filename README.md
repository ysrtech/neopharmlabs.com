# neopharmlabs.com

Rebuild of www.neopharmlabs.com on [Laravel 12](https://laravel.com) + [Twill 3](https://twillcms.com), after the original
site was lost. All content was recovered from the Internet Archive (Wayback Machine): the 2024–2026 Craft CMS site in
English and French, plus the older 2017–2023 pages.

## What's in the CMS (`/npoffice`)

| Module | Notes |
|---|---|
| Pages | Home, About, Careers, Contact, Legal, built from blocks (hero, image + text, carousel, locations…). Older pages from the previous sites are kept as **drafts** (`legacy:` key). |
| Services | 7 categories and 53 services, with the icons and filters of the services page. |
| Blog | Posts and categories. 30 news items from the old site are kept as **drafts** (marked legacy). |
| Resources | Posters, webinars, white papers. Upload the PDF in "Download" to deliver it after the form. |
| Team, Careers | Team member pages and job postings (`/careers/jobs/{reference}`). |
| Form submissions | Contact, privacy request, resource download and job application forms. CVs are stored privately. |
| Redirects | 301 redirects for old URLs (WordPress-era pages, renamed items). Add more as needed. |

English is served at the root and French under `/fr`, with the original URL slugs.

## Local setup

```bash
composer install
cp .env.example .env && php artisan key:generate
touch database/database.sqlite        # or configure MySQL in .env
php artisan migrate
php artisan storage:link
php artisan neopharm:import           # imports database/recovered/ (content, images, legacy pages, redirects)
php artisan twill:superadmin you@example.com 'a-strong-password'
php artisan serve
```

## Deploying to the server

See [DEPLOY.md](DEPLOY.md) for the step-by-step server install (Ubuntu, Nginx, PHP-FPM, MySQL, SSL, mail, backups).

Images are resized on the fly by Glide (`/img/...`). Uploaded images live in `storage/app/public/uploads`.

## Known gaps from the recovery

- Gated resource PDFs and webinar recordings were never archived: upload them on each resource.
- Two images could not be recovered (`Mildreth-2.jpg`, the 2023 bioprocessing certificate).
- Job postings were only available in English; the French careers pages show the same postings.
- The original reCAPTCHA keys were not reused; forms use a honeypot and rate limiting.
