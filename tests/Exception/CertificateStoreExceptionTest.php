<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use Tourze\TLSCertStore\Exception\CertificateStoreException;

/**
 * @internal
 */
#[CoversClass(CertificateStoreException::class)]
final class CertificateStoreExceptionTest extends AbstractExceptionTestCase
{
    /**
     * 测试创建证书不存在异常
     */
    public function testCertificateNotFound(): void
    {
        $alias = 'test-cert';
        $exception = CertificateStoreException::certificateNotFound($alias);

        $this->assertInstanceOf(CertificateStoreException::class, $exception);
        $this->assertEquals('Certificate with alias "test-cert" not found', $exception->getMessage());
    }

    /**
     * 测试创建证书已存在异常
     */
    public function testCertificateAlreadyExists(): void
    {
        $alias = 'existing-cert';
        $exception = CertificateStoreException::certificateAlreadyExists($alias);

        $this->assertInstanceOf(CertificateStoreException::class, $exception);
        $this->assertEquals('Certificate with alias "existing-cert" already exists', $exception->getMessage());
    }

    /**
     * 测试创建存储操作失败异常
     */
    public function testOperationFailed(): void
    {
        $operation = 'store';
        $reason = 'disk full';
        $exception = CertificateStoreException::operationFailed($operation, $reason);

        $this->assertInstanceOf(CertificateStoreException::class, $exception);
        $this->assertEquals('Certificate store operation "store" failed: disk full', $exception->getMessage());
    }

    /**
     * 测试创建无效证书格式异常
     */
    public function testInvalidFormat(): void
    {
        $format = 'PKCS12';
        $exception = CertificateStoreException::invalidFormat($format);

        $this->assertInstanceOf(CertificateStoreException::class, $exception);
        $this->assertEquals('Invalid certificate format: "PKCS12"', $exception->getMessage());
    }
}
