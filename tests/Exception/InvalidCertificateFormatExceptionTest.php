<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Tests\Exception;

use PHPUnit\Framework\Attributes\CoversClass;
use Tourze\PHPUnitBase\AbstractExceptionTestCase;
use Tourze\TLSCertStore\Exception\InvalidCertificateFormatException;

/**
 * @internal
 */
#[CoversClass(InvalidCertificateFormatException::class)]
final class InvalidCertificateFormatExceptionTest extends AbstractExceptionTestCase
{
    /**
     * 测试创建不支持的格式异常
     */
    public function testUnsupportedFormat(): void
    {
        $format = 'PKCS12';
        $exception = InvalidCertificateFormatException::unsupportedFormat($format);

        $this->assertInstanceOf(InvalidCertificateFormatException::class, $exception);
        $this->assertEquals('不支持的证书格式: PKCS12', $exception->getMessage());
    }
}
