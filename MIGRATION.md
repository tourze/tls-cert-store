# 从 tls-certificate 迁移到 tls-cert-store

本文档描述了将证书存储相关功能从 `tls-certificate` 包迁移到 `tls-cert-store` 包的过程。

## 迁移的类和接口

以下是从 `tls-certificate` 迁移到 `tls-cert-store` 的类和接口：

| 原始位置 | 新位置 |
|---------|--------|
| `Tourze\TLSCertificate\Certificate\X509CertificateLoader` | `Tourze\TLSCertStore\Repository\X509CertificateLoader` |

## 新增的类和接口

以下是在 `tls-cert-store` 中新增的类和接口：

| 类/接口名称 | 描述 |
|------------|------|
| `Tourze\TLSCertStore\Repository\CertificateLoaderInterface` | 定义证书加载的接口 |
| `Tourze\TLSCertStore\Repository\X509Parser` | 提供 X.509 证书解析功能 |
| `Tourze\TLSCertStore\Storage\CertificateStoreInterface` | 定义证书存储的接口 |
| `Tourze\TLSCertStore\Storage\InMemoryCertificateStore` | 提供基于内存的证书存储实现 |
| `Tourze\TLSCertStore\Exception\CertificateStoreException` | 处理证书存储相关的异常 |

## 使用示例更新

如果您之前使用了 `Tourze\TLSCertificate\Certificate\X509CertificateLoader`，请更新引用为 `Tourze\TLSCertStore\Repository\X509CertificateLoader`：

```php
// 旧代码
use Tourze\TLSCertificate\Certificate\X509CertificateLoader;

// 新代码
use Tourze\TLSCertStore\Repository\X509CertificateLoader;
```

## 功能增强

`tls-cert-store` 包提供了更全面的证书存储功能，包括：

1. 证书加载：支持从 PEM、DER 和文件加载证书
2. 证书解析：解析 X.509 证书字段
3. 证书存储：提供内存存储实现和存储接口
4. 异常处理：专门的异常类处理证书存储错误

## 测试

迁移后的代码包含了完整的单元测试，可以通过以下命令运行：

```bash
./vendor/bin/phpunit packages/tls-cert-store/tests
```

## 依赖关系

`tls-cert-store` 依赖于：

- `tourze/tls-common`: 基础数据结构和工具函数
- `tourze/tls-x509-core`: X.509 证书的核心实现 