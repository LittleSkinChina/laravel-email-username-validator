<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Rules;

use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class SinaVIPMailRule implements EmailUsernameRule
{
    public static function domains(): array
    {
        return [
            'vip.sina.com',
            'vip.sina.com.cn',
            'vip.sina.cn',
        ];
    }

    public function passes(string $username, string $domain, array $parameters = []): bool
    {
        // Length 4-16 (See note below)
        // Starts and ends with any allowed character, but not underscore
        // Allowed chars: lowercase letters, digits, underscore
        return (bool) preg_match('/^(?=.{4,16}$)(?!^[0-9]+$)[a-z0-9][a-z0-9_]*[a-z0-9]$/', $username);

        // Note: Sina VIP Mail actually allows shorter username (1 character minimum),
        // but these premium usernames require an expensive initial fee and a paid subscription to maintain,
        // so we enforce stricter rules here.
        // See https://mail.sina.com.cn/register/reg_vipmail.php for details of premium usernames.
        // If you want to allow those, create your own rule.
    }
}