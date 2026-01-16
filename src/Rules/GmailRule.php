<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Rules;

use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class GmailRule implements EmailUsernameRule
{
    public static function domains(): array
    {
        return [
            'gmail.com',
            'googlemail.com',
        ];
    }

    public function passes(string $username, string $domain, array $parameters = []): bool
    {
        // For detailed rules, see: https://support.google.com/mail/answer/9211434

        $lower = strtolower($username);

        // Preserved usernames
        if (str_starts_with($lower, 'abuse') || str_starts_with($lower, 'postmaster')) {
            return false;
        }
        
        // Gmail ignores dots and anything after plus sign
        $real = str_replace('.', '', explode('+', $lower, 2)[0]);

        // Length 6-30
        // Starts with a letter, ends with a alphanumeric
        // Allowed chars: letters, digits
        return (bool) preg_match('/^(?=.{6,30}$)[A-Za-z][A-Za-z0-9]*[A-Za-z0-9]$/', $real);
    }
}