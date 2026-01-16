<?php

namespace LittleSkin\LaravelEmailUsernameValidator;

use Illuminate\Contracts\Translation\Translator;
use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;
use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRuleWithMessage;
use LittleSkin\LaravelEmailUsernameValidator\Support\EmailAddress;

class EmailUsernameValidator
{
    public function __construct(
        protected readonly RuleRegistry $registry,
        protected readonly Translator $translator,
        protected readonly array $messageDefinition,
        protected readonly bool $unknownDomainPass = true,
    ) {}

    /**
     * Validate an email address' local-part against domain-specific rules.
     * Returns [bool pass, string message, array meta]
     */
    public function validate(string $email, array $parameters = []): array
    {
        $parsed = EmailAddress::parse($email);
        if (!$parsed || $parsed->local === null || $parsed->domain === null) {
            return [false, $this->translator->get($this->messageDefinition['invalid-email-address']), ['domain' => null]];
        }

        $rule = $this->registry->forDomain($parsed->domain);
        if ($rule === null) {
            if ($this->unknownDomainPass) {
                return [true, '', ['domain' => $parsed->domain]];
            }
            return [false, $this->translator->get($this->messageDefinition['unknown-domain-not-allowed']), ['domain' => $parsed->domain]];
        }

        $username = $parsed->local;
        $domain = $parsed->domain;

        $failureMessage = null;
        /** @var EmailUsernameRule $rule */
        if ($rule->passes($username, $domain, $parameters)) {
            return [true, '', ['domain' => $domain]];
        }
        if ($rule instanceof EmailUsernameRuleWithMessage) {
            $failureMessage = $rule->message();
        }

        return [false, $failureMessage ?? $this->translator->get($this->messageDefinition['invalid-username']), ['domain' => $domain]];
    }
}
