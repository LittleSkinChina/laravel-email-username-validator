<?php

namespace LittleSkin\LaravelEmailUsernameValidator;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;

class EmailUsernameValidatorServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__.'/../config/email-username-validator.php', 'email-username-validator');

        $this->app->singleton(RuleRegistry::class, function ($app) {
            $classes = (array) $app['config']->get('email-username-validator.rules', []);
            return new RuleRegistry($app, $classes);
        });

        $this->app->singleton(EmailUsernameValidator::class, function ($app) {
            $unknownPass = (bool) $app['config']->get('email-username-validator.unknown-domain-pass', true);
            return new EmailUsernameValidator(
                $app->make(RuleRegistry::class),
                $app['translator'],
                $app['config']->get('email-username-validator.messages'),
                $unknownPass,
            );
        });
    }

    public function boot(): void
    {
        // Load package translations under the namespace 'email-username'
        $this->loadTranslationsFrom(__DIR__.'/../lang', 'email-username');

        $this->publishes([
            __DIR__.'/../config/email-username-validator.php' => $this->app->configPath('email-username-validator.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/../lang' => $this->app->langPath('vendor/email-username')
        ], 'lang');

        $messageDefinition = (array) $this->app['config']->get('email-username-validator.messages');

        // Register a Validator extension: email_username
        ValidatorFacade::extend('email_username', function (string $attribute, mixed $value, array $parameters, Validator $validator): bool {
            /** @var EmailUsernameValidator $ev */
            $ev = $this->app->make(EmailUsernameValidator::class);
            [$pass, $message, $meta] = $ev->validate((string) $value, $parameters);

            // Stash message per attribute if failed; Laravel will use default message,
            // but we provide a replacer for :domain.
            if (!$pass && $message) {
                // Allow users to override message via standard custom messages.
                // Here we set a fallback only if none provided.
                $validator->setCustomMessages([
                    $attribute.'.email_username' => $message,
                ]);
            }

            return $pass;
        }, $this->app['translator']->get($messageDefinition['invalid-username']));

        // Replacer to inject :domain placeholder and apply translatable/configurable message
        ValidatorFacade::replacer('email_username', function (string $message, string $attribute, string $rule, array $parameters, Validator $validator): string {
            $domain = null;
            $data = $validator->getData();
            if (isset($data[$attribute]) && is_string($data[$attribute]) && str_contains($data[$attribute], '@')) {
                $parts = explode('@', $data[$attribute]);
                $domain = strtolower(end($parts));
            }

            return str_replace(':domain', (string) $domain, $message);
        });
    }
}
