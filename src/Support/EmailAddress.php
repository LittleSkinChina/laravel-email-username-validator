<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Support;

class EmailAddress
{
    public function __construct(
        public readonly string $raw,
        public readonly ?string $local = null,
        public readonly ?string $domain = null,
    ) {}

    public static function parse(string $email): ?self
    {
        $email = trim($email);
        if ($email === '') {
            return null;
        }

        $at = strrpos($email, '@');
        if ($at === false) {
            return null;
        }

        $local = substr($email, 0, $at);
        $domain = substr($email, $at + 1);

        if ($local === '' || $domain === '') {
            return null;
        }

        // Basic domain normalization: lowercase; whitespace stripped already.
        $domain = strtolower($domain);

        return new self($email, $local, $domain);
    }
}
