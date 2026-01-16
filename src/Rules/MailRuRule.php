<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Rules;

use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class MailRuRule implements EmailUsernameRule
{
    public static function domains(): array
    {
        return [
            'mail.ru',
            'list.ru',
            'inbox.ru',
            'bk.ru',
            'internet.ru',
            'vk.com',
        ];
    }

    public function passes(string $username, string $domain, array $parameters = []): bool
    {
        // - Length 5-31
        // - Starts and ends with a alphanumeric
        // - Allowed chars: letters, digits, dot, underscore, hyphen
        // - No consecutive dots, underscores, hyphens, or dot-underscore / dot-hyphen / underscore-hyphen combinations
        return (bool) preg_match('/^(?=.{5,31}$)(?!.*[._-]{2})[A-Za-z0-9][A-Za-z0-9._-]*[A-Za-z0-9]$/', $username);
    }
}