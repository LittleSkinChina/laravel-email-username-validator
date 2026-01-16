<?php

return [
    // List of rule classes. Each class must implement
    // LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule
    'rules' => [
        // App\EmailUsernameRules\GmailRule::class,
        // App\EmailUsernameRules\OutlookRule::class,
    ],

    // If a domain has no matching rules, should validation pass?
    'unknown-domain-pass' => true,

    // Optional custom error message or translation key used when
    // no rule-specific message is provided. If set to a translation key,
    // the package will resolve it via the translator.
    // `:attribute` and `:domain` will be replaced with the attribute name and email domain.
    // Examples:
    // - 'email-username::validation.invalid-email-username' (package default)
    // - 'validation.invalid-email-username' (your app's validation lang file)
    // - 'The :attribute is invalid for domain :domain.' (literal)
    'messages' => [
        'invalid-username' => 'email-username::validation.invalid-username',
        'invalid-email-address' => 'email-username::validation.invalid-email-address',
        'unknown-domain-not-allowed' => 'email-username::validation.unknown-domain-not-allowed',
    ],
];
