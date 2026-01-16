<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Rules;

use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class ChinaMobile139MailRule implements EmailUsernameRule
{
    public static function domains(): array
    {
        return [
            '139.com',
        ];
    }

    public function passes(string $username, string $domain, array $parameters = []): bool
    {
        $length = strlen($username);

        // Special case: phone number as username
        if (ctype_digit($username)) {
            return $length === 11 && $username[0] === '1';
        }

        // Special case: premium short usernames (4-6 digits)
        if ($length >= 4 && $length <= 6) {
            if (preg_match('/^(?=.{4,6}$)[A-Za-z0-9]+$/', $username)) {
                return true;
            }
        }

        // Length 5-15
        // Starts with a letter, ends with any allowed character
        // Allowed chars: letters, digits, dot, underscore, hyphen
        return (bool) preg_match('/^(?=.{5,15}$)[A-Za-z][A-Za-z0-9._-]*$/', $username);

        // Note: China Mobile Mail also support "CMCC Authentication ID" username,
        // which is a system-generated numeric ID and not user-selectable,
        // but the format is not publicly documented, so we rejected it here.
        // If you want to allow those, create your own rule.
    }
}