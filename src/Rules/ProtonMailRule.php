<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Rules;

use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class ProtonMailRule implements EmailUsernameRule
{
    public static function domains(): array
    {
        return [
            'proton.me',
            'protonmail.com',
            'pm.me',
        ];
    }

    public function passes(string $username, string $domain, array $parameters = []): bool
    {
        // - Length 6-40
        // - Starts with a alphanumeric, ends with a alphanumeric
        // - Allowed chars: letters, digits, dot, underscore, hyphen
        // - No consecutive dots, underscores, hyphens, or dot-underscore / dot-hyphen / hyphen-underscore combinations
        return (bool) preg_match('/^(?=.{6,40}$)(?!.*[._-]{2})[A-Za-z0-9][A-Za-z0-9._-]*[A-Za-z0-9]$/', $username);
    }
}