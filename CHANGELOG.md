# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]

## [1.0.0] - {{YEAR}}-XX-XX

### Added

- Initial release of `{{PACKAGE_NAME}}`
- **BaseClient**: Base client class with HTTP methods (GET, POST, PUT, DELETE)
- **BaseConfig**: Base configuration class with validation
- **Exception Classes**:
  - `BaseException`: Base exception class
  - `ConfigException`: Configuration errors
  - `HttpException`: HTTP-level errors
  - `ValidationException`: Input validation errors
- **HTTP Abstraction**:
  - `HttpClientInterface`: Injectable HTTP client interface
  - `HttpResponse`: HTTP response wrapper
  - `NativeHttpClient`: Default implementation using file_get_contents
- **Laravel Integration**:
  - `ServiceProvider`: Laravel service provider with config publishing
  - `Facade`: Laravel facade base class
- Comprehensive test suite using PHPUnit
- Full PHPDoc documentation for all public APIs

[Unreleased]: https://github.com/{{REPO_OWNER}}/{{REPO_NAME}}/compare/v1.0.0...HEAD
[1.0.0]: https://github.com/{{REPO_OWNER}}/{{REPO_NAME}}/releases/tag/v1.0.0
