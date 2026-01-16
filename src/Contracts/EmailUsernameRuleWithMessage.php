<?php

namespace LittleSkin\LaravelEmailUsernameValidator\Contracts;

interface EmailUsernameRuleWithMessage extends EmailUsernameRule
{
    /**
     * Failure message for this rule.
     * 
     * @return string
     */
    public function message(): string;
}
