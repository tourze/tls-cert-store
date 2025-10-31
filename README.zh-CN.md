# TLS 证书存储库

[![Latest Version](https://img.shields.io/packagist/v/tourze/tls-cert-store.svg?style=flat-square)](https://packagist.org/packages/tourze/tls-cert-store)
[![PHP Version](https://img.shields.io/badge/php-%5E8.1-blue.svg)](https://packagist.org/packages/tourze/tls-cert-store)
[![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)](LICENSE)
[![Build Status](https://img.shields.io/github/actions/workflow/status/owner/repo/ci.yml)](https://github.com/owner/repo/actions)
[![Code Coverage](https://img.shields.io/codecov/c/github/owner/repo)](https://codecov.io/github/owner/repo)

[English](README.md) | [中文](README.zh-CN.md)

一个全面的 TLS 证书存储和管理组件，为 TLS 协议实现提供 X.509 证书加载、解析和存储功能。

## 功能特性

- **证书加载**：从各种来源（PEM、DER、文件）加载 X.509 证书
- **证书解析**：解析 X.509 证书字段，如主题、颁发者、有效期
- **证书存储**：标准的证书存储接口，提供内存实现
- **证书链支持**：处理证书链以实现完整的信任验证
- **异常处理**：为不同错误场景提供全面的异常类型

## 安装

```bash
composer require tourze/tls-cert-store
```

## 系统要求

- PHP 8.1 或更高版本
- `tourze/tls-common`：基础数据结构、接口和工具函数
- `tourze/tls-x509-core`：核心 X.509 证书实现

## 快速开始

### 加载证书

```php
use Tourze\TLSCertStore\Repository\X509CertificateLoader;

// 创建证书加载器
$loader = new X509CertificateLoader();

// 从 PEM 字符串加载证书
$pemString = file_get_contents('certificate.pem');
$certificate = $loader->loadFromPEMString($pemString);

// 从文件加载证书
$certificate = $loader->loadFromFile('/path/to/certificate.pem');

// 加载证书链
$pemChain = file_get_contents('certificate-chain.pem');
$certificates = $loader->loadCertificateChain($pemChain);
```

### 存储证书

```php
use Tourze\TLSCertStore\Storage\InMemoryCertificateStore;

// 创建内存证书存储
$store = new InMemoryCertificateStore();

// 添加证书
$store->addCertificate('server-cert', $certificate);

// 获取证书
$certificate = $store->getCertificate('server-cert');

// 检查证书是否存在
$exists = $store->hasCertificate('server-cert');

// 移除证书
$store->removeCertificate('server-cert');

// 获取所有证书别名
$aliases = $store->getAliases();

// 获取证书数量
$count = $store->count();

// 清除所有证书
$store->clear();
```

### 证书链操作

```php
// 导入证书链
$aliases = $store->importCertificateChain('chain', $certificates);

// 导出证书为 PEM 格式
$pem = $store->exportAsPEM('server-cert');
```

## API 参考

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

## 异常处理

该库为不同的错误场景提供特定的异常类型：

- `CertificateFileException`：文件相关错误
- `CertificateParseException`：解析错误
- `CertificateStoreException`：存储错误
- `EmptyCertificateException`：空证书数据
- `InvalidCertificateFormatException`：不支持的格式

## 从 tls-certificate 迁移

如果您之前使用了 `tls-certificate` 包中的证书存储功能，请参阅 [MIGRATION.md](MIGRATION.md) 了解迁移指南。

## 开发

```bash
# 安装依赖
composer install

# 运行测试
./vendor/bin/phpunit

# 运行 PHPStan 分析
./vendor/bin/phpstan analyse src/
```

## 测试

该包包含全面的测试覆盖：

- 所有异常类型的单元测试
- 证书加载和存储的集成测试
- 各种证书格式的测试数据

## 贡献

1. Fork 仓库
2. 创建功能分支
3. 进行更改
4. 为新功能添加测试
5. 运行测试套件
6. 提交拉取请求

## 许可证

本项目采用 MIT 许可证 - 详情请参阅 [LICENSE](LICENSE) 文件。