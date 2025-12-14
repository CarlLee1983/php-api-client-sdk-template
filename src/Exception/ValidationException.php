<?php

declare(strict_types=1);

namespace {{NAMESPACE}}\Exception;

/**
 * Exception thrown when input validation fails.
 */
class ValidationException extends BaseException
{
    /**
     * @var array<string, string[]> Validation errors by field
     */
    private array $errors;

    /**
     * Create a new validation exception.
     *
     * @param string $message The error message
     * @param array<string, string[]> $errors Validation errors by field
     */
    public function __construct(string $message, array $errors = [])
    {
        $this->errors = $errors;
        parent::__construct($message);
    }

    /**
     * Get all validation errors.
     *
     * @return array<string, string[]>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Get errors for a specific field.
     *
     * @param string $field The field name
     * @return string[]
     */
    public function getFieldErrors(string $field): array
    {
        return $this->errors[$field] ?? [];
    }

    /**
     * Check if a specific field has errors.
     *
     * @param string $field The field name
     */
    public function hasFieldError(string $field): bool
    {
        return isset($this->errors[$field]) && !empty($this->errors[$field]);
    }

    /**
     * Create a validation exception for a required field.
     *
     * @param string $field The field name
     */
    public static function required(string $field): self
    {
        return new self(
            "The {$field} field is required",
            [$field => ["The {$field} field is required"]]
        );
    }

    /**
     * Create a validation exception for an invalid field value.
     *
     * @param string $field The field name
     * @param string $reason The reason the value is invalid
     */
    public static function invalid(string $field, string $reason): self
    {
        return new self(
            "The {$field} field is invalid: {$reason}",
            [$field => [$reason]]
        );
    }
}
