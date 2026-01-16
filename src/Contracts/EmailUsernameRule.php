<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Contracts;

interface EmailUsernameRule
{
    /**
     * List of domains this rule applies to.
     * Examples: 'gmail.com', 'example.co.uk', '*.corp.example.com'
     *
     * @return array<int,string>
     */
    public static function domains(): array;

    /**
     * Validate the local-part (username) for the given domain.
     * 
     * @return bool True if valid, false if invalid.
     */
    public function passes(string $username, string $domain, array $parameters = []): bool;
}
