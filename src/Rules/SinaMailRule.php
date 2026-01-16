<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Rules;

use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class SinaMailRule implements EmailUsernameRule
{
    public static function domains(): array
    {
        return [
            'sina.com',
            'sina.com.cn',
            'sina.cn',
        ];
    }

    public function passes(string $username, string $domain, array $parameters = []): bool
    {
        // Special case: phone number as username
        if (ctype_digit($username)) {
            return strlen($username) === 11 && $username[0] === '1' && $domain === 'sina.cn';
        }

        // Length 4-16
        // Starts and ends with any allowed character, but not underscore
        // Allowed chars: lowercase letters, digits, underscore
        return (bool) preg_match('/^(?=.{4,16}$)(?!^[0-9]+$)[a-z0-9][a-z0-9_]*[a-z0-9]$/', $username);
    }
}