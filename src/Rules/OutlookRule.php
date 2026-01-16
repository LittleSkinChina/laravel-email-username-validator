<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Rules;

use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class OutlookRule implements EmailUsernameRule
{
    public static function domains(): array
    {
        return [
            'outlook.com',
            'hotmail.com',
            'live.com',
            'msn.com',
        ];

        // For full list of Outlook domains, see: https://xnnd.com/msdomains
        // Extend this rule for more Outlook domains
    }

    public function passes(string $username, string $domain, array $parameters = []): bool
    {
        $real = explode('+', $username, 2)[0];

        // Outlook doesn't public their username rule, so we use general rules here.
        // - Length 4-32
        // - Starts with a letter, ends with a alphanumeric
        // - Allowed chars: letters, digits, dot, underscore, hyphen
        return (bool) preg_match('/^(?=.{4,32}$)[A-Za-z][A-Za-z0-9._-]*[A-Za-z0-9]$/', $real);

        // Note: In our observation, Outlook seems to allow much longer usernames (even more than 60 characters),
        // but such usernames are very rare, so we enforce stricter rules here.
        // If you want to allow those, create your own rule.
    }
}