<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Tests\Service;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\TLSCertStore\Exception\CertificateParseException;
use Tourze\TLSCertStore\Exception\EmptyCertificateException;
use Tourze\TLSCertStore\Service\X509Parser;
use Tourze\TLSX509Core\Certificate\X509Certificate;

/**
 * @internal
 */
#[CoversClass(X509Parser::class)]
final class X509ParserTest extends TestCase
{
    private X509Parser $parser;

    protected function setUp(): void
    {
        $this->parser = new X509Parser();
    }

    public function testParsePEMWithEmptyString(): void
    {
        $this->expectException(CertificateParseException::class);

        $this->parser->parsePEM('');
    }

    public function testParsePEMWithInvalidPEM(): void
    {
        $this->expectException(CertificateParseException::class);

        $this->parser->parsePEM('这不是一个有效的PEM证书');
    }

    public function testParsePEMWithValidPEM(): void
    {
        $validPem = <<<'EOT'
            -----BEGIN CERTIFICATE-----
            MIIDazCCAlOgAwIBAgIUdFjlgzrOQzGR90mhYBHEbBJA0CEwDQYJKoZIhvcNAQEL
            -----END CERTIFICATE-----
            EOT;

        $certificate = $this->parser->parsePEM($validPem);

        $this->assertInstanceOf(X509Certificate::class, $certificate);
        $subject = $certificate->getSubject();
        $this->assertIsArray($subject);
        $this->assertArrayHasKey('commonName', $subject);
        $this->assertArrayHasKey('organizationName', $subject);
        $this->assertEquals('example.com', $subject['commonName']);
        $this->assertEquals('Internet Widgits Pty Ltd', $subject['organizationName']);
    }

    public function testParseDER(): void
    {
        $derBytes = "\x30\x82\x01\x02";

        $certificate = $this->parser->parseDER($derBytes);

        $this->assertInstanceOf(X509Certificate::class, $certificate);
        $subject = $certificate->getSubject();
        $this->assertIsArray($subject);
        $this->assertArrayHasKey('organizationName', $subject);
        $this->assertArrayHasKey('commonName', $subject);
        $this->assertEquals('Internet Widgits Pty Ltd', $subject['organizationName']);
        $this->assertEquals('example.org', $subject['commonName']);
        $this->assertEquals('987654321', $certificate->getSerialNumber());
    }

    public function testParseDERWithEmptyBytes(): void
    {
        $this->expectException(EmptyCertificateException::class);

        $this->parser->parseDER('');
    }

    public function testFormatDN(): void
    {
        $dnArray = [
            'commonName' => 'example.com',
            'organizationName' => 'Example Inc',
            'countryName' => 'US',
        ];

        $method = new \ReflectionMethod($this->parser, 'formatDN');
        $method->setAccessible(true);

        $result = $method->invoke($this->parser, $dnArray);

        $this->assertEquals('CN=example.com, O=Example Inc, C=US', $result);
    }
}
