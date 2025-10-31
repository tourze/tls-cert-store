<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Service;

use Tourze\TLSCertStore\Exception\CertificateFileException;
use Tourze\TLSCertStore\Exception\CertificateParseException;
use Tourze\TLSCertStore\Exception\EmptyCertificateException;
use Tourze\TLSCertStore\Exception\InvalidCertificateFormatException;
use Tourze\TLSX509Core\Certificate\X509Certificate;

/**
 * X.509证书加载器
 *
 * 提供从不同来源加载X.509证书的功能
 */
class X509CertificateLoader implements CertificateLoaderInterface
{
    /**
     * @var X509Parser X.509解析器
     */
    private X509Parser $parser;

    /**
     * 构造函数
     */
    public function __construct(?X509Parser $parser = null)
    {
        $this->parser = $parser ?? new X509Parser();
    }

    public function loadFromPEMString(string $pemString): X509Certificate
    {
        if ('' === $pemString) {
            throw EmptyCertificateException::emptyPemString();
        }

        try {
            return $this->parser->parsePEM($pemString);
        } catch (\Throwable $e) {
            throw CertificateParseException::pemParseFailed($e->getMessage(), $e);
        }
    }

    public function loadFromDERBytes(string $derBytes): X509Certificate
    {
        if ('' === $derBytes) {
            throw EmptyCertificateException::emptyDerBytes();
        }

        try {
            return $this->parser->parseDER($derBytes);
        } catch (\Throwable $e) {
            throw CertificateParseException::derParseFailed($e->getMessage(), $e);
        }
    }

    public function loadFromFile(string $filePath, string $format = 'PEM'): X509Certificate
    {
        if (!file_exists($filePath)) {
            throw CertificateFileException::fileNotFound($filePath);
        }

        $fileContent = file_get_contents($filePath);
        if (false === $fileContent) {
            throw CertificateFileException::fileReadFailed($filePath);
        }

        if ('PEM' === strtoupper($format)) {
            return $this->loadFromPEMString($fileContent);
        }
        if ('DER' === strtoupper($format)) {
            return $this->loadFromDERBytes($fileContent);
        }
        throw InvalidCertificateFormatException::unsupportedFormat($format);
    }

    public function loadCertificateChain(string $pemString): array
    {
        if ('' === $pemString) {
            throw EmptyCertificateException::emptyPemString();
        }

        try {
            // 解析多个PEM块
            $pattern = '/-----BEGIN CERTIFICATE-----.*?-----END CERTIFICATE-----/s';
            preg_match_all($pattern, $pemString, $matches);

            if ([] === $matches[0]) {
                throw CertificateParseException::noPemCertificateFound();
            }

            $certificates = [];
            foreach ($matches[0] as $pemBlock) {
                $certificates[] = $this->loadFromPEMString($pemBlock);
            }

            return $certificates;
        } catch (\Throwable $e) {
            throw CertificateParseException::chainParseFailed($e->getMessage(), $e);
        }
    }
}
