<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Rules;

use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class PerfectWorldMailRule implements EmailUsernameRule
{
    public static function domains(): array
    {
        return [
            'email.cn',
            '*.email.cn',
            '88.com',
            '111.com',
        ];
    }

    public function passes(string $username, string $domain, array $parameters = []): bool
    {
        if (str_ends_with($domain, 'email.cn')) {
            $exp = explode('.', $domain);
            if (count($exp) === 3) { // Email address with custom subdomain
                // Subdomain & Username: 2-20 alphanumeric characters
                return (bool) preg_match('/^(?=.{2,20}$)[A-Za-z0-9]+$/', $exp[0]) && (bool) preg_match('/^(?=.{2,20}$)[A-Za-z0-9]+$/', $username);
            } else {
                // Length: 6-20
                // Allowed chars: letters, digits, dot
                // Starts with an letter, ends with an alphanumeric
                // Maximum 1 dot allowed
                return (bool) preg_match('/^(?=.{6,20}$)(?=[^.]*\.?[^.]*$)[A-Za-z](?!.*([.]{2}))[A-Za-z0-9.]*[A-Za-z0-9]$/', $username);
            }
        }

        // Length: 6-20 (See note below)
        // Allowed chars: letters, digits, dot
        // Start with a letter, ends with an alphanumeric
        // Maximum 1 dot allowed
        return (bool) preg_match('/^(?=.{6,20}$)[A-Za-z](?!.*[._-]{2})[A-Za-z0-9._-]*[A-Za-z0-9]$/', $username);

        // Note: Perfect World Mail actually allows shorter username (4 character minimum, may starts with digit),
        // but these premium usernames require an initial fee while the mall is already down for a long period,
        // so we enforce stricter rules here.
        // See https://www.88.com/register for details of premium usernames.
        // If you want to allow those, create your own rule.
    }
}