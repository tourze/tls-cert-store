<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Exception;

/**
 * 无效证书格式异常
 *
 * 当证书格式不支持时抛出
 */
class InvalidCertificateFormatException extends \RuntimeException
{
    /**
     * 创建不支持的格式异常
     *
     * @param string $format 格式名称
     */
    public static function unsupportedFormat(string $format): self
    {
        return new self('不支持的证书格式: ' . $format);
    }
}
