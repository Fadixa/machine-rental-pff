<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Default Mailer
    |--------------------------------------------------------------------------
    | Mailer par défaut utilisé par Laravel. Change selon l'environnement.
    */
    'default' => env('MAIL_MAILER', 'smtp'),

    /*
    |--------------------------------------------------------------------------
    | Mailer Configurations
    |--------------------------------------------------------------------------
    | Configuration de tous les drivers d'envoi d'email.
    */

    'mailers' => [

        /* ============================================================
           SMTP — DRIVER PAR DÉFAUT (compatible Bird/SparkPost/Gmail/etc.)
           ============================================================ */
        'smtp' => [
            'transport' => 'smtp',
            'scheme' => env('MAIL_SCHEME'),           // null, 'tls', 'ssl'
            'url' => env('MAIL_URL'),                   // URL complète optionnelle
            'host' => env('MAIL_HOST', 'smtp.sparkpostmail.com'),  // ← Bird SMTP
            'port' => env('MAIL_PORT', 587),           // ← Port TLS standard
            'username' => env('MAIL_USERNAME', 'SMTP_Injection'),  // ← Bird
            'password' => env('MAIL_PASSWORD'),         // ← Ta clé API Bird
            'timeout' => null,
            'local_domain' => env('MAIL_EHLO_DOMAIN', parse_url((string) env('APP_URL', 'http://localhost'), PHP_URL_HOST)),
        ],

        /* ============================================================
           SES — Amazon Simple Email Service
           ============================================================ */
        'ses' => [
            'transport' => 'ses',
        ],

        /* ============================================================
           MAILGUN — via Symfony Mailer
           ============================================================ */
        'mailgun' => [
            'transport' => 'mailgun',
        ],

        /* ============================================================
           POSTMARK — via Symfony Mailer
           ============================================================ */
        'postmark' => [
            'transport' => 'postmark',
        ],

        /* ============================================================
           SENDMAIL — via ligne de commande (serveur local)
           ============================================================ */
        'sendmail' => [
            'transport' => 'sendmail',
            'path' => env('MAIL_SENDMAIL_PATH', '/usr/sbin/sendmail -bs -i'),
        ],

        /* ============================================================
           LOG — Écrit les emails dans les logs (utile pour debug)
           ============================================================ */
        'log' => [
            'transport' => 'log',
            'channel' => env('MAIL_LOG_CHANNEL'),
        ],

        /* ============================================================
           ARRAY — Stocke en mémoire (tests unitaires)
           ============================================================ */
        'array' => [
            'transport' => 'array',
        ],

        /* ============================================================
           FAILOVER — Bascule automatique vers un backup SMTP
           ============================================================ */
        'failover' => [
            'transport' => 'failover',
            'mailers' => [
                'smtp',
                'log',
            ],
        ],

        /* ============================================================
           ROUNDROBIN — Répartit la charge entre plusieurs mailers
           ============================================================ */
        'roundrobin' => [
            'transport' => 'roundrobin',
            'mailers' => [
                'ses',
                'postmark',
            ],
        ],

        /* ============================================================
           MAILPIT / MAILTRAP — Pour le développement local
           ============================================================ */
        'mailpit' => [
            'transport' => 'smtp',
            'host' => env('MAILPIT_HOST', '127.0.0.1'),
            'port' => env('MAILPIT_PORT', 1025),
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Global "From" Address
    |--------------------------------------------------------------------------
    | Adresse d'expédition par défaut pour tous les emails.
    */

    'from' => [
        'address' => env('MAIL_FROM_ADDRESS', 'noreply@rentify.ma'),
        'name' => env('MAIL_FROM_NAME', 'Rentify Maroc'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Markdown Mail Settings
    |--------------------------------------------------------------------------
    | Configuration pour les emails Markdown (thème + chemins).
    */

    'markdown' => [
        'theme' => 'default',
        'paths' => [
            resource_path('views/vendor/mail'),
        ],
    ],

];