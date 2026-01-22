<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Rules;

use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class YahooMailRule implements EmailUsernameRule
{
    public static function domains(): array
    {
        return [
            'yahoo.com',
            'ymail.com',
            'myyahoo.com',
        ];
        // Extend this rule for more Yahoo Mail domains
    }

    public function passes(string $username, string $domain, array $parameters = []): bool
    {
        // - Length 4-32
        // - Starts with a letter, ends with an alphanumeric
        // - Allowed chars: letters, digits, dot, underscore
        // - Maximum one dot allowed
        // - No consecutive dots, underscores, or dot-underscore combinations
        return (bool) preg_match('/^(?=.{4,32}$)(?=[^.]*\.?[^.]*$)[A-Za-z](?!.*([._]{2}|(\._)|(_\.)))[A-Za-z0-9._]*[A-Za-z0-9]$/', $username);
    }
}