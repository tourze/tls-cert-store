<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Storage;

use Tourze\TLSX509Core\Certificate\X509Certificate;

/**
 * 基于内存的证书存储
 *
 * 提供证书的内存存储实现，适用于临时存储或测试
 */
class InMemoryCertificateStore implements CertificateStoreInterface
{
    /**
     * @var array<string, X509Certificate> 存储的证书
     */
    private array $certificates = [];

    public function addCertificate(string $alias, X509Certificate $certificate): bool
    {
        $this->certificates[$alias] = $certificate;

        return true;
    }

    public function getCertificate(string $alias): ?X509Certificate
    {
        return $this->certificates[$alias] ?? null;
    }

    public function removeCertificate(string $alias): bool
    {
        if ($this->hasCertificate($alias)) {
            unset($this->certificates[$alias]);

            return true;
        }

        return false;
    }

    public function hasCertificate(string $alias): bool
    {
        return isset($this->certificates[$alias]);
    }

    public function getAliases(): array
    {
        return array_keys($this->certificates);
    }

    public function count(): int
    {
        return count($this->certificates);
    }

    public function clear(): bool
    {
        $this->certificates = [];

        return true;
    }

    public function exportAsPEM(string $alias): ?string
    {
        $certificate = $this->getCertificate($alias);

        if (null === $certificate) {
            return null;
        }

        return $certificate->toPEM();
    }

    /**
     * 导入证书链
     *
     * @param string $alias        证书链的别名前缀
     * @param array<int, X509Certificate>  $certificates 证书链
     *
     * @return array<string> 导入的证书别名
     */
    public function importCertificateChain(string $alias, array $certificates): array
    {
        $aliases = [];

        foreach ($certificates as $index => $certificate) {
            if ($certificate instanceof X509Certificate) {
                $currentAlias = $alias . '-' . ($index + 1);
                $this->addCertificate($currentAlias, $certificate);
                $aliases[] = $currentAlias;
            }
        }

        return $aliases;
    }

    /**
     * 获取所有存储的证书
     *
     * @return array<string, X509Certificate> 所有证书
     */
    public function getAll(): array
    {
        return $this->certificates;
    }
}
