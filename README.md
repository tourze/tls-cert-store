# TLS Cert Store

[![Latest Version](https://img.shields.io/packagist/v/tourze/tls-cert-store.svg?style=flat-square)](https://packagist.org/packages/tourze/tls-cert-store)
[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue.svg)](https://packagist.org/packages/tourze/tls-cert-store)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![Build Status](https://img.shields.io/github/actions/workflow/status/owner/repo/ci.yml)](https://github.com/owner/repo/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/owner/repo)](https://codecov.io/github/owner/repo)

[English](README.md) | [中文](README.zh-CN.md)

A comprehensive TLS certificate storage and management component that provides X.509 certificate loading, parsing, and storage functionality for TLS protocol implementations.

## Features

- **Certificate Loading**: Load X.509 certificates from various sources (PEM, DER, files)
- **Certificate Parsing**: Parse X.509 certificate fields such as subject, issuer, validity period
- **Certificate Storage**: Standard interface for certificate storage with in-memory implementation
- **Certificate Chain Support**: Handle certificate chains for complete trust verification
- **Exception Handling**: Comprehensive exception types for different error scenarios

## Installation

```bash
composer require tourze/tls-cert-store
```

## Requirements

- PHP 8.1 or higher
- `tourze/tls-common`: Basic data structures, interfaces and utility functions
- `tourze/tls-x509-core`: Core X.509 certificate implementation

## Quick Start

### Loading Certificates

```php
use Tourze\TLSCertStore\Repository\X509CertificateLoader;

// Create certificate loader
$loader = new X509CertificateLoader();

// Load certificate from PEM string
$pemString = file_get_contents('certificate.pem');
$certificate = $loader->loadFromPEMString($pemString);

// Load certificate from file
$certificate = $loader->loadFromFile('/path/to/certificate.pem');

// Load certificate chain
$pemChain = file_get_contents('certificate-chain.pem');
$certificates = $loader->loadCertificateChain($pemChain);
```

### Storing Certificates

```php
use Tourze\TLSCertStore\Storage\InMemoryCertificateStore;

// Create in-memory certificate store
$store = new InMemoryCertificateStore();

// Add certificate
$store->addCertificate('server-cert', $certificate);

// Get certificate
$certificate = $store->getCertificate('server-cert');

// Check if certificate exists
$exists = $store->hasCertificate('server-cert');

// Remove certificate
$store->removeCertificate('server-cert');

// Get all certificate aliases
$aliases = $store->getAliases();

// Get certificate count
$count = $store->count();

// Clear all certificates
$store->clear();
```

### Certificate Chain Operations

```php
// Import certificate chain
$aliases = $store->importCertificateChain('chain', $certificates);

// Export certificate as PEM
$pem = $store->exportAsPEM('server-cert');
```

## API Reference

### CertificateLoaderInterface

- `loadFromPEMString(string $pemString): X509Certificate`
- `loadFromDERBytes(string $derBytes): X509Certificate`
- `loadFromFile(string $filePath, string $format = 'PEM'): X509Certificate`
- `loadCertificateChain(string $pemString): array`

### CertificateStoreInterface

- `addCertificate(string $alias, X509Certificate $certificate): bool`
- `getCertificate(string $alias): ?X509Certificate`
- `removeCertificate(string $alias): bool`
- `hasCertificate(string $alias): bool`
- `getAliases(): array`
- `count(): int`
- `clear(): void`
- `importCertificateChain(string $baseAlias, array $certificates): array`
- `exportAsPEM(string $alias): string`

## Exception Handling

The library provides specific exception types for different error scenarios:

- `CertificateFileException`: File-related errors
- `CertificateParseException`: Parsing errors
- `CertificateStoreException`: Storage errors
- `EmptyCertificateException`: Empty certificate data
- `InvalidCertificateFormatException`: Unsupported formats

## Migration from tls-certificate

If you previously used certificate storage functionality from the `tls-certificate` package, please refer to [MIGRATION.md](MIGRATION.md) for migration guidelines.

## Development

```bash
# Install dependencies
composer install

# Run tests
./vendor/bin/phpunit

# Run PHPStan analysis
./vendor/bin/phpstan analyse src/
```

## Testing

The package includes comprehensive test coverage:

- Unit tests for all exception types
- Integration tests for certificate loading and storage
- Test data with various certificate formats

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests for new functionality
5. Run the test suite
6. Submit a pull request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.