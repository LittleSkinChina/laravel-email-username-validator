<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Rules;

use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class ICloudMailRule implements EmailUsernameRule
{
    public static function domains(): array
    {
        return [
            'icloud.com',
            'me.com',
            'mac.com',
            'privaterelay.appleid.com',
        ];
    }

    public function passes(string $username, string $domain, array $parameters = []): bool
    {
        // - Length 3-20
        // - Starts with a letter, ends with an alphanumeric
        // - Allowed chars: letters, digits, dot, underscore, hyphen (for Hide My Email only)
        // - No consecutive dots, underscores or hyphens
        return (bool) preg_match('/^(?=.{3,20}$)(?!.*\.\.)(?!.*__)(?!.*--)[A-Za-z][A-Za-z0-9._-]*[A-Za-z0-9]$/', $username);
    }
}