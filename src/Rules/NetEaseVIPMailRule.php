<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Rules;

use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class NetEaseVIPMailRule implements EmailUsernameRule
{
    public static function domains(): array
    {
        return [
            'vip.163.com',
            'vip.126.com',
            '188.com',
        ];
    }

    public function passes(string $username, string $domain, array $parameters = []): bool
    {
        // Length 5-20
        // Starts with a letter, ends with a alphanumeric
        // Allowed chars: letters, digits, underscore
        return (bool) preg_match('/^(?=.{5,20}$)[A-Za-z][A-Za-z0-9_]*[A-Za-z0-9]$/', $username);
    }
}