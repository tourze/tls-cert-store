<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Exception;

/**
 * 空证书异常
 *
 * 当证书内容为空时抛出
 */
class EmptyCertificateException extends \RuntimeException
{
    /**
     * 创建PEM字符串为空异常
     */
    public static function emptyPemString(): self
    {
        return new self('PEM字符串为空');
    }

    /**
     * 创建DER字节为空异常
     */
    public static function emptyDerBytes(): self
    {
        return new self('DER字节为空');
    }
}
