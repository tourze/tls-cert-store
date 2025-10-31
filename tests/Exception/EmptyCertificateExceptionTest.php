<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use Tourze\TLSCertStore\Exception\EmptyCertificateException;

/**
 * @internal
 */
#[CoversClass(EmptyCertificateException::class)]
final class EmptyCertificateExceptionTest extends AbstractExceptionTestCase
{
    /**
     * 测试创建PEM字符串为空异常
     */
    public function testEmptyPemString(): void
    {
        $exception = EmptyCertificateException::emptyPemString();

        $this->assertInstanceOf(EmptyCertificateException::class, $exception);
        $this->assertEquals('PEM字符串为空', $exception->getMessage());
    }

    /**
     * 测试创建DER字节为空异常
     */
    public function testEmptyDerBytes(): void
    {
        $exception = EmptyCertificateException::emptyDerBytes();

        $this->assertInstanceOf(EmptyCertificateException::class, $exception);
        $this->assertEquals('DER字节为空', $exception->getMessage());
    }
}
