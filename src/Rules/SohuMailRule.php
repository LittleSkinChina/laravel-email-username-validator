<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Rules;

use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class SohuMailRule implements EmailUsernameRule
{
    public static function domains(): array
    {
        return [
            'sohu.com',
            'me.sohu.com',
        ];
    }

    public function passes(string $username, string $domain, array $parameters = []): bool
    {
        // Special case: phone number as username
        if (ctype_digit($username)) {
            return strlen($username) === 11 && $username[0] === '1';
        }

        // Length 6-16
        // Starts with a letter, ends with any allowed character
        // Allowed chars: letters, digits, underscore
        return (bool) preg_match('/^(?=.{6,16}$)[A-Za-z][A-Za-z0-9_]*$/', $username);
    }
}