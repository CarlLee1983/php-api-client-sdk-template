# Security Policy

## Supported Versions

We actively support the following versions of `{{PACKAGE_NAME}}`:

| Version | Supported          |
| ------- | ------------------ |
| 1.x.x   | :white_check_mark: |

## Reporting a Vulnerability

We take the security of `{{PACKAGE_NAME}}` seriously. If you discover a security vulnerability, please follow these steps:

### 1. **Do Not** Open a Public Issue

Please do not report security vulnerabilities through public GitHub issues. This helps prevent exploitation before a fix is available.

### 2. Report Privately

Send your vulnerability report to:

- **GitHub Security Advisories**: [Create a security advisory](https://github.com/{{REPO_OWNER}}/{{REPO_NAME}}/security/advisories/new)
- **Subject**: `[SECURITY] {{PACKAGE_NAME}}: [Brief Description]`

### 3. Include Details

Please include the following information in your report:

- **Description**: A clear description of the vulnerability
- **Impact**: The potential impact and severity
- **Steps to Reproduce**: Detailed steps to reproduce the issue
- **Affected Versions**: Which versions are affected
- **Suggested Fix**: If you have a suggestion for fixing the issue (optional)
- **Proof of Concept**: Any code or examples demonstrating the vulnerability (optional)

### 4. What to Expect

- **Acknowledgment**: We will acknowledge receipt of your vulnerability report within 48 hours
- **Updates**: We will keep you informed about the progress of addressing the vulnerability
- **Timeline**: We aim to release a fix within 30 days for critical vulnerabilities
- **Credit**: We will credit you (if desired) in the security advisory and release notes

### 5. Security Best Practices

When using `{{PACKAGE_NAME}}`, please follow these security best practices:

#### API Key Protection
```php
// ❌ DON'T: Hard-code secrets
$config = new Config(
    apiKey: 'api_key_12345',  // Never hard-code!
    baseUri: 'https://api.example.com'
);

// ✅ DO: Use environment variables
$config = new Config(
    apiKey: getenv('API_KEY'),
    baseUri: getenv('API_BASE_URI')
);
```

#### Keep Dependencies Updated
Regularly update `{{PACKAGE_NAME}}` and its dependencies to receive security patches:

```bash
composer update {{PACKAGE_NAME}}
```

## Security Features

`{{PACKAGE_NAME}}` includes the following security features:

### 1. Input Validation
- Required field validation
- Type-safe API with PHP 8.1+ strict types

### 2. Error Handling
Custom exception classes that don't leak sensitive information in error messages:
- `ConfigException`: For configuration errors
- `ValidationException`: For input validation errors
- `HttpException`: For HTTP-level errors

### 3. Minimal Dependencies
Only essential dependencies (ext-json) to reduce the attack surface.

## Security Updates

Security updates will be released as patch versions and announced through:

- GitHub Security Advisories
- Release notes
- Packagist package updates

## Scope

This security policy applies to:

- The `{{PACKAGE_NAME}}` package
- Security issues in the core library code
- Security issues in the build process and distribution

This policy does **not** cover:

- Security issues in applications built using this library (unless caused by the library itself)
- Security issues in third-party APIs
- Social engineering attacks

## Additional Resources

- [PHP Security Best Practices](https://www.php.net/manual/en/security.php)
- [Composer Security Advisories](https://packagist.org/advisories)

## Questions?

If you have questions about this security policy, please open a GitHub issue (for non-security questions) or contact the maintainers directly.

---

**Last Updated**: {{YEAR}}
