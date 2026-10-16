<?php

return [
    // URL prefix per locale. English lives at the root, French under /fr (same as the original site).
    'locales' => [
        'en' => '',
        'fr' => 'fr',
    ],

    // Translated URL segments, matching the original neopharmlabs.com URLs.
    'segments' => [
        'services' => ['en' => 'services', 'fr' => 'services'],
        'blog' => ['en' => 'blog', 'fr' => 'blogue'],
        'blog_category' => ['en' => 'blog/category', 'fr' => 'blogue/categorie'],
        'resources' => ['en' => 'resources', 'fr' => 'ressources'],
        'team' => ['en' => 'about/team', 'fr' => 'a-propos/equipe'],
        'careers' => ['en' => 'careers', 'fr' => 'carrieres'],
        'jobs' => ['en' => 'careers/jobs', 'fr' => 'carrieres/emplois'],
        'apply' => ['en' => 'careers/apply', 'fr' => 'carrieres/postuler'],
        'spontaneous' => ['en' => 'careers/spontaneous', 'fr' => 'carrieres/candidature-spontanee'],
        'search' => ['en' => 'search', 'fr' => 'search'],
    ],

    // Page keys (Twill "Pages" module) used by the navigation.
    'pages' => ['home', 'about', 'careers', 'contact', 'legal'],

    'blog_per_page' => 10,

    'contact' => [
        'email' => 'info@neopharmlabs.com',
        'phone' => '450-435-8864',
        'phone_link' => '4504358864',
        'careers_email' => 'cv@neopharmlabs.com',
        'linkedin' => 'https://www.linkedin.com/company/neopharm-labs-inc-/?originalSubdomain=ca',
        'youtube' => 'https://www.youtube.com/channel/UC0uowL4Zm09VQkY-BwNnX8Q',
        'neoconnect' => 'https://neoconnect.neopharmlabs.com/login',
    ],

    // Where form submissions are emailed (comma separated). Submissions are always stored in the CMS.
    'notify' => [
        'contact' => env('FORMS_NOTIFY_CONTACT', 'info@neopharmlabs.com'),
        'careers' => env('FORMS_NOTIFY_CAREERS', 'cv@neopharmlabs.com'),
        'privacy' => env('FORMS_NOTIFY_PRIVACY', 'drh@neopharmlabs.com'),
    ],

    'newsletter_action' => 'https://neopharmlabs.us12.list-manage.com/subscribe/post?u=980ca4c4c088046b246ed0afc&id=2829daf001&f_id=0036b8e0f0',
    'newsletter_honeypot' => 'b_980ca4c4c088046b246ed0afc_2829daf001',

    'google_analytics' => env('GOOGLE_ANALYTICS_ID', 'G-36RQ7WMKFX'),
];
