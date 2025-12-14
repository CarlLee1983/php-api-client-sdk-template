# Contributing to {{REPO_NAME}}

Thank you for your interest in contributing to `{{PACKAGE_NAME}}`! We welcome contributions from the community.

[繁體中文](./CONTRIBUTING_ZH.md) | English

## Code of Conduct

By participating in this project, you agree to maintain a respectful and inclusive environment for everyone.

## How to Contribute

### Reporting Bugs

1. **Search existing issues** to avoid duplicates
2. **Use the bug report template** when creating a new issue
3. **Include** PHP version, package version, and steps to reproduce

### Suggesting Features

1. **Search existing issues** to see if it's already proposed
2. **Use the feature request template** when creating a new issue
3. **Explain the use case** and why it would be beneficial

### Pull Requests

1. **Fork the repository** and create a new branch
2. **Follow coding standards** (PSR-12)
3. **Write tests** for new functionality
4. **Update documentation** if needed
5. **Submit the pull request** with a clear description

## Development Setup

### Prerequisites

- PHP 8.1+
- Composer

### Installation

```bash
git clone https://github.com/{{REPO_OWNER}}/{{REPO_NAME}}.git
cd {{REPO_NAME}}
composer install
```

### Running Tests

```bash
# Run all tests
composer test

# Run tests with coverage
composer test:coverage
```

## Coding Standards

This project follows **PSR-12** coding standards.

### Code Style

- Use strict types: `declare(strict_types=1);`
- Use readonly properties where applicable
- Add PHPDoc for all public methods
- Keep methods focused and small

### Example

```php
<?php

declare(strict_types=1);

namespace {{NAMESPACE}};

/**
 * Example class demonstrating coding standards.
 */
final class Example
{
    /**
     * Create a new instance.
     *
     * @param string $value The value to store
     */
    public function __construct(
        public readonly string $value
    ) {
    }

    /**
     * Get the formatted value.
     *
     * @return string The formatted value
     */
    public function getFormatted(): string
    {
        return strtoupper($this->value);
    }
}
```

## Commit Messages

We follow [Conventional Commits](https://www.conventionalcommits.org/):

- `feat:` New features
- `fix:` Bug fixes
- `docs:` Documentation changes
- `test:` Test additions or modifications
- `refactor:` Code refactoring
- `chore:` Maintenance tasks

### Examples

```
feat: add support for custom HTTP clients
fix: handle empty response body correctly
docs: update installation instructions
test: add tests for validation exception
```

## Release Process

Releases are automated via GitHub Actions when a version tag is pushed:

```bash
git tag v1.0.0
git push origin v1.0.0
```

---

Thank you for contributing to `{{PACKAGE_NAME}}`! 🎉
