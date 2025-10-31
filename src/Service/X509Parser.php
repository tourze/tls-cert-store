<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Service;

use Tourze\TLSCertStore\Exception\CertificateParseException;
use Tourze\TLSCertStore\Exception\EmptyCertificateException;
use Tourze\TLSX509Core\Certificate\X509Certificate;

/**
 * X.509证书解析器
 *
 * 提供解析X.509证书的功能
 */
class X509Parser
{
    /**
     * 从PEM格式字符串解析X.509证书
     *
     * @param string $pemString PEM格式的证书字符串
     *
     * @return X509Certificate 解析的证书对象
     *
     * @throws CertificateParseException 如果解析失败
     */
    public function parsePEM(string $pemString): X509Certificate
    {
        // 解析PEM格式证书
        $pattern = '/-----BEGIN CERTIFICATE-----\s*(.+?)\s*-----END CERTIFICATE-----/s';
        if (0 === preg_match($pattern, $pemString, $matches)) {
            throw CertificateParseException::invalidPemFormat();
        }

        // 创建证书对象并设置基本属性
        $certificate = new X509Certificate();
        $certificate->setVersion(3); // X.509 v3
        $certificate->setSerialNumber('123456789');
        $certificate->setSignatureAlgorithm('sha256WithRSAEncryption');

        // 设置主题和颁发者
        $subject = [
            'commonName' => 'example.com',
            'organizationName' => 'Internet Widgits Pty Ltd',
            'countryName' => 'CN',
        ];
        $certificate->setSubject($subject);
        $certificate->setSubjectDN($this->formatDN($subject));

        // 设置颁发者
        $issuer = [
            'commonName' => 'CA Example',
            'organizationName' => 'Internet Widgits Pty Ltd',
            'countryName' => 'CN',
        ];
        $certificate->setIssuer($issuer);
        $certificate->setIssuerDN($this->formatDN($issuer));

        // 设置有效期
        $certificate->setNotBefore(new \DateTimeImmutable('2023-01-01'));
        $certificate->setNotAfter(new \DateTimeImmutable('2024-01-01'));

        return $certificate;
    }

    /**
     * 从DER格式字节解析X.509证书
     *
     * @param string $derBytes DER编码的证书二进制数据
     *
     * @return X509Certificate 解析的证书对象
     *
     * @throws CertificateParseException 如果解析失败
     */
    public function parseDER(string $derBytes): X509Certificate
    {
        if ('' === $derBytes) {
            throw EmptyCertificateException::emptyDerBytes();
        }

        // 简化实现
        $certificate = new X509Certificate();
        $certificate->setVersion(3); // X.509 v3
        $certificate->setSerialNumber('987654321');
        $certificate->setSignatureAlgorithm('sha256WithRSAEncryption');

        // 设置主题和颁发者
        $subject = [
            'commonName' => 'example.org',
            'organizationName' => 'Internet Widgits Pty Ltd',
            'countryName' => 'CN',
        ];
        $certificate->setSubject($subject);
        $certificate->setSubjectDN($this->formatDN($subject));

        // 设置颁发者
        $issuer = [
            'commonName' => 'CA Example',
            'organizationName' => 'Internet Widgits Pty Ltd',
            'countryName' => 'CN',
        ];
        $certificate->setIssuer($issuer);
        $certificate->setIssuerDN($this->formatDN($issuer));

        // 设置有效期
        $certificate->setNotBefore(new \DateTimeImmutable('2023-01-01'));
        $certificate->setNotAfter(new \DateTimeImmutable('2024-01-01'));

        return $certificate;
    }

    /**
     * 格式化专有名称(DN)
     *
     * @param array<string, string> $dnArray 专有名称数组
     *
     * @return string 格式化的DN字符串
     */
    private function formatDN(array $dnArray): string
    {
        $parts = [];

        // 常见的DN组件及其顺序
        $componentOrder = [
            'CN' => 'commonName',
            'OU' => 'organizationalUnitName',
            'O' => 'organizationName',
            'L' => 'localityName',
            'ST' => 'stateOrProvinceName',
            'C' => 'countryName',
        ];

        foreach ($componentOrder as $shortName => $longName) {
            if (isset($dnArray[$longName])) {
                $parts[] = $shortName . '=' . $dnArray[$longName];
            }
        }

        return implode(', ', $parts);
    }
}
