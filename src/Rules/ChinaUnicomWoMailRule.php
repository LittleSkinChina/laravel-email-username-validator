<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Rules;

use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class ChinaUnicomWoMailRule implements EmailUsernameRule
{
    public static function domains(): array
    {
        return [
            'wo.cn',
        ];
    }

    public function passes(string $username, string $domain, array $parameters = []): bool
    {
        // Special case: phone number as username
        if (ctype_digit($username)) {
            return strlen($username) === 11 && $username[0] === '1';
        }

        // Length 8-20
        // Starts with a letter, ends with a alphanumeric
        // Allowed chars: letters, digits, dot, underscore, hyphen
        return (bool) preg_match('/^(?=.{8,20}$)[A-Za-z][A-Za-z0-9._-]*[A-Za-z0-9]$/', $username);
    }
}