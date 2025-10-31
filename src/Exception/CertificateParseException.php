<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Exception;

/**
 * 证书解析异常
 *
 * 当证书解析操作发生错误时抛出
 */
class CertificateParseException extends \RuntimeException
{
    /**
     * 创建PEM解析失败异常
     *
     * @param string          $reason   失败原因
     * @param \Throwable|null $previous 前一个异常
     */
    public static function pemParseFailed(string $reason, ?\Throwable $previous = null): self
    {
        return new self('无法解析PEM证书: ' . $reason, 0, $previous);
    }

    /**
     * 创建DER解析失败异常
     *
     * @param string          $reason   失败原因
     * @param \Throwable|null $previous 前一个异常
     */
    public static function derParseFailed(string $reason, ?\Throwable $previous = null): self
    {
        return new self('无法解析DER证书: ' . $reason, 0, $previous);
    }

    /**
     * 创建证书链解析失败异常
     *
     * @param string          $reason   失败原因
     * @param \Throwable|null $previous 前一个异常
     */
    public static function chainParseFailed(string $reason, ?\Throwable $previous = null): self
    {
        return new self('无法解析证书链: ' . $reason, 0, $previous);
    }

    /**
     * 创建无效PEM格式异常
     */
    public static function invalidPemFormat(): self
    {
        return new self('无效的PEM格式证书');
    }

    /**
     * 创建未找到PEM证书异常
     */
    public static function noPemCertificateFound(): self
    {
        return new self('未找到PEM格式的证书');
    }
}
