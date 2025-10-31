<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\TLSCertStore\Exception\CertificateFileException;
use Tourze\TLSCertStore\Exception\CertificateParseException;
use Tourze\TLSCertStore\Exception\EmptyCertificateException;
use Tourze\TLSCertStore\Exception\InvalidCertificateFormatException;
use Tourze\TLSCertStore\Service\X509CertificateLoader;
use Tourze\TLSCertStore\Service\X509Parser;
use Tourze\TLSX509Core\Certificate\X509Certificate;

/**
 * @internal
 */
#[CoversClass(X509CertificateLoader::class)]
final class X509CertificateLoaderTest extends TestCase
{
    private X509CertificateLoader $loader;

    private X509Parser $mockParser;

    private X509Certificate $mockCertificate;

    protected function setUp(): void
    {
        $this->mockCertificate = new class extends X509Certificate {
        };

        $this->mockParser = new class($this->mockCertificate) extends X509Parser {
            private X509Certificate $mockCertificate;

            public function __construct(X509Certificate $mockCertificate)
            {
                $this->mockCertificate = $mockCertificate;
            }

            public function parsePEM(string $pemString): X509Certificate
            {
                if ('这不是一个有效的PEM证书' === $pemString) {
                    throw new \RuntimeException('Invalid PEM');
                }

                return $this->mockCertificate;
            }

            public function parseDER(string $derBytes): X509Certificate
            {
                return $this->mockCertificate;
            }
        };

        $this->loader = new X509CertificateLoader($this->mockParser);
    }

    public function testLoadFromPEMStringWithValidPEM(): void
    {
        $pemData = '-----BEGIN CERTIFICATE-----...-----END CERTIFICATE-----';

        $certificate = $this->loader->loadFromPEMString($pemData);

        $this->assertSame($this->mockCertificate, $certificate);
    }

    public function testLoadFromPEMStringWithEmptyString(): void
    {
        $this->expectException(EmptyCertificateException::class);

        $this->loader->loadFromPEMString('');
    }

    public function testLoadFromPEMStringWithInvalidPEM(): void
    {
        $invalidPem = '这不是一个有效的PEM证书';

        $this->expectException(CertificateParseException::class);

        $this->loader->loadFromPEMString($invalidPem);
    }

    public function testLoadFromDERBytes(): void
    {
        $derBytes = "\x30\x82\x01\x02";

        $certificate = $this->loader->loadFromDERBytes($derBytes);

        $this->assertSame($this->mockCertificate, $certificate);
    }

    public function testLoadFromDERBytesWithEmptyBytes(): void
    {
        $this->expectException(EmptyCertificateException::class);

        $this->loader->loadFromDERBytes('');
    }

    public function testLoadFromFile(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test_cert_') . '.pem';
        $pemData = '-----BEGIN CERTIFICATE-----...-----END CERTIFICATE-----';
        file_put_contents($tempFile, $pemData);

        try {
            $certificate = $this->loader->loadFromFile($tempFile, 'PEM');
            $this->assertSame($this->mockCertificate, $certificate);
        } finally {
            unlink($tempFile);
        }
    }

    public function testLoadFromFileNotFound(): void
    {
        $this->expectException(CertificateFileException::class);

        $this->loader->loadFromFile('/non/existent/file.pem');
    }

    public function testLoadFromFileUnsupportedFormat(): void
    {
        $tempFile = tempnam(sys_get_temp_dir(), 'test_cert_') . '.pem';
        file_put_contents($tempFile, 'dummy content');

        try {
            $this->expectException(InvalidCertificateFormatException::class);

            $this->loader->loadFromFile($tempFile, 'INVALID');
        } finally {
            unlink($tempFile);
        }
    }

    public function testLoadCertificateChainWithValidChain(): void
    {
        $pemChain = <<<'EOT'
            -----BEGIN CERTIFICATE-----
            cert1data
            -----END CERTIFICATE-----
            -----BEGIN CERTIFICATE-----
            cert2data
            -----END CERTIFICATE-----
            EOT;

        $cert1 = new class extends X509Certificate {
        };
        $cert2 = new class extends X509Certificate {
        };

        $certificates = $this->loader->loadCertificateChain($pemChain);

        $this->assertCount(2, $certificates);
        $this->assertInstanceOf(X509Certificate::class, $certificates[0]);
        $this->assertInstanceOf(X509Certificate::class, $certificates[1]);
    }

    public function testLoadCertificateChainWithEmptyString(): void
    {
        $this->expectException(EmptyCertificateException::class);

        $this->loader->loadCertificateChain('');
    }
}
