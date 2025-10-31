<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use Tourze\TLSCertStore\Exception\CertificateParseException;

/**
 * @internal
 */
#[CoversClass(CertificateParseException::class)]
final class CertificateParseExceptionTest extends AbstractExceptionTestCase
{
    /**
     * 测试创建PEM解析失败异常
     */
    public function testPemParseFailed(): void
    {
        $reason = 'Invalid PEM format';
        $exception = CertificateParseException::pemParseFailed($reason);

        $this->assertInstanceOf(CertificateParseException::class, $exception);
        $this->assertEquals('无法解析PEM证书: ' . $reason, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }

    /**
     * 测试创建带前一个异常的PEM解析失败异常
     */
    public function testPemParseFailedWithPrevious(): void
    {
        $reason = 'Invalid PEM format';
        $previous = new \RuntimeException('Previous exception');
        $exception = CertificateParseException::pemParseFailed($reason, $previous);

        $this->assertInstanceOf(CertificateParseException::class, $exception);
        $this->assertEquals('无法解析PEM证书: ' . $reason, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    /**
     * 测试创建DER解析失败异常
     */
    public function testDerParseFailed(): void
    {
        $reason = 'Invalid DER format';
        $exception = CertificateParseException::derParseFailed($reason);

        $this->assertInstanceOf(CertificateParseException::class, $exception);
        $this->assertEquals('无法解析DER证书: ' . $reason, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }

    /**
     * 测试创建带前一个异常的DER解析失败异常
     */
    public function testDerParseFailedWithPrevious(): void
    {
        $reason = 'Invalid DER format';
        $previous = new \RuntimeException('Previous exception');
        $exception = CertificateParseException::derParseFailed($reason, $previous);

        $this->assertInstanceOf(CertificateParseException::class, $exception);
        $this->assertEquals('无法解析DER证书: ' . $reason, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    /**
     * 测试创建证书链解析失败异常
     */
    public function testChainParseFailed(): void
    {
        $reason = 'Invalid certificate chain';
        $exception = CertificateParseException::chainParseFailed($reason);

        $this->assertInstanceOf(CertificateParseException::class, $exception);
        $this->assertEquals('无法解析证书链: ' . $reason, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertNull($exception->getPrevious());
    }

    /**
     * 测试创建带前一个异常的证书链解析失败异常
     */
    public function testChainParseFailedWithPrevious(): void
    {
        $reason = 'Invalid certificate chain';
        $previous = new \RuntimeException('Previous exception');
        $exception = CertificateParseException::chainParseFailed($reason, $previous);

        $this->assertInstanceOf(CertificateParseException::class, $exception);
        $this->assertEquals('无法解析证书链: ' . $reason, $exception->getMessage());
        $this->assertEquals(0, $exception->getCode());
        $this->assertSame($previous, $exception->getPrevious());
    }

    /**
     * 测试创建无效PEM格式异常
     */
    public function testInvalidPemFormat(): void
    {
        $exception = CertificateParseException::invalidPemFormat();

        $this->assertInstanceOf(CertificateParseException::class, $exception);
        $this->assertEquals('无效的PEM格式证书', $exception->getMessage());
    }

    /**
     * 测试创建未找到PEM证书异常
     */
    public function testNoPemCertificateFound(): void
    {
        $exception = CertificateParseException::noPemCertificateFound();

        $this->assertInstanceOf(CertificateParseException::class, $exception);
        $this->assertEquals('未找到PEM格式的证书', $exception->getMessage());
    }
}
