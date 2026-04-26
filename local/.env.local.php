<?php

return [
    'defaultErrorReporting' => E_ALL,
    'defaultTimeZone' => 'Europe/Zurich',
    'allowedDomains' => [
        'bsv-buelach.ch.ddev.site',
    ],
    'logEmailRecipient' => 'error@bsv-buelach.ch',
    'debug' => true,
    'robots' => 'noindex,nofollow',
    'mailer.hostname' => 'localhost',
    'mailer.port' => 1025,
    'mailer.username' => '',
    'mailer.password' => '',
    'mailer.tls' => false,
    'db.identifier' => 'db',
    'db.hostname' => 'db',
    'db.username' => 'db',
    'db.password' => 'db',
    'db.database' => 'db',
];