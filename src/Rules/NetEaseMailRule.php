<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Rules;

use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class NetEaseMailRule implements EmailUsernameRule
{
    public static function domains(): array
    {
        return [
            '163.com',
            '126.com',
            'yeah.net',
        ];
    }

    public function passes(string $username, string $domain, array $parameters = []): bool
    {
        // Special case: phone number as username
        if (ctype_digit($username)) {
            return strlen($username) === 11 && $username[0] === '1' && $domain === '163.com';
        }

        // Length 6-18 (See note below)
        // Starts with a letter, ends with a alphanumeric
        // Allowed chars: letters, digits, underscore
        return (bool) preg_match('/^(?=.{6,18}$)[A-Za-z][A-Za-z0-9_]*[A-Za-z0-9]$/', $username);
        

        // Note: NetEase Mail actually allows shorter username (1 character minimum) and more characters (dot and hyphen),
        // but these premium usernames require an expensive initial fee and / or a paid subscription to maintain,
        // so we enforce stricter rules here.
        // See https://haoma.163.com for details of premium usernames.
        // If you want to allow those, create your own rule.
    }
}