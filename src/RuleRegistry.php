<?php

namespace LittleSkin\LaravelEmailUsernameValidator;

use Illuminate\Contracts\Container\Container;
use LittleSkin\LaravelEmailUsernameValidator\Contracts\EmailUsernameRule;

class RuleRegistry
{
    /** @var array<string,EmailUsernameRule> */
    protected array $byDomain = [];

    /** @var array<int,EmailUsernameRule> */
    protected array $wildcards = [];

    public function __construct(
        protected readonly Container $container,
        /** @var array<int,class-string<EmailUsernameRule>> */
        protected readonly array $ruleClasses
    ) {
        $this->buildIndex();
    }

    protected function buildIndex(): void
    {
        foreach ($this->ruleClasses as $class) {
            /** @var EmailUsernameRule $instance */
            $instance = $this->container->make($class);
            $domains = $class::domains();
            foreach ($domains as $domain) {
                $domain = strtolower(trim($domain));
                if (str_contains($domain, '*')) {
                    $this->wildcards[] = $instance;
                    // instance will be checked at runtime for wildcard support
                } else {
                    // Only keep the first rule registered for a concrete domain.
                    $this->byDomain[$domain] ??= $instance;
                }
            }
        }
    }

    /**
     * @return EmailUsernameRule|null The rule that applies to the given domain, or null if none.
     */
    public function forDomain(string $domain): ?EmailUsernameRule
    {
        $domain = strtolower($domain); // Normalize to compare domains case-insensitively.
        if (isset($this->byDomain[$domain])) {
            return $this->byDomain[$domain]; // Exact domains map to a single rule.
        }

        // Match wildcard patterns declared by rules' static::domains()
        foreach ($this->wildcards as $rule) { // Each wildcard rule may include multiple pattern strings.
            foreach ($rule::domains() as $pattern) { // Inspect every pattern declared on the rule class.
                $pattern = strtolower(trim($pattern)); // Ensure wildcard pattern comparisons are normalized.
                if (!str_contains($pattern, '*')) { // Skip non-wildcard entries that already exist in exact map.
                    continue;
                }
                $regex = '/^' . str_replace(['*', '.'], ['[^.]+', '\\.'], $pattern) . '$/i'; // Wildcards now match exactly one subdomain level.
                if (preg_match($regex, $domain) === 1) { // If the requested domain matches the wildcard regex...
                    return $rule; // Return the first wildcard rule that matches to keep domain -> rule one-to-one.
                }
            }
        }

        return null;
    }
}
