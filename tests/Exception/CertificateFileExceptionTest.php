<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use Tourze\TLSCertStore\Exception\CertificateFileException;

/**
 * @internal
 */
#[CoversClass(CertificateFileException::class)]
final class CertificateFileExceptionTest extends AbstractExceptionTestCase
{
    /**
     * 测试创建文件不存在异常
     */
    public function testFileNotFound(): void
    {
        $filePath = '/path/to/nonexistent/cert.pem';
        $exception = CertificateFileException::fileNotFound($filePath);

        $this->assertInstanceOf(CertificateFileException::class, $exception);
        $this->assertEquals('证书文件不存在: ' . $filePath, $exception->getMessage());
    }

    /**
     * 测试创建文件读取失败异常
     */
    public function testFileReadFailed(): void
    {
        $filePath = '/path/to/unreadable/cert.pem';
        $exception = CertificateFileException::fileReadFailed($filePath);

        $this->assertInstanceOf(CertificateFileException::class, $exception);
        $this->assertEquals('无法读取证书文件: ' . $filePath, $exception->getMessage());
    }
}
