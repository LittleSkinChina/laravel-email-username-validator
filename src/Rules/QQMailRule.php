<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Rules;

use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class QQMailRule implements EmailUsernameRule
{
    public static function domains(): array
    {
        return [
            'qq.com',
            'vip.qq.com',
            'foxmail.com',
        ];
    }

    public function passes(string $username, string $domain, array $parameters = []): bool
    {
        // QQ ID as username: 5-11 digits only
        if (ctype_digit($username) and $domain === 'qq.com') {
            $length = strlen($username);
            return $length >= 5 && $length <= 11;
        }

        // - Length 3-20
        // - Starts with a letter, ends with an alphanumeric
        // - Allowed chars: letters, digits, dot, underscore, hyphen
        // - No consecutive dots, underscores, or hyphens
        return (bool) preg_match('/^(?=.{3,20}$)[A-Za-z](?!.*[._-]{2})[A-Za-z0-9._-]*[A-Za-z0-9]$/', $username);
    }
}