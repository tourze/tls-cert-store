<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Exception;

/**
 * 证书存储异常
 *
 * 当证书存储操作发生错误时抛出
 */
class CertificateStoreException extends \RuntimeException
{
    /**
     * 创建证书不存在异常
     *
     * @param string $alias 证书别名
     */
    public static function certificateNotFound(string $alias): self
    {
        return new self(sprintf('Certificate with alias "%s" not found', $alias));
    }

    /**
     * 创建证书已存在异常
     *
     * @param string $alias 证书别名
     */
    public static function certificateAlreadyExists(string $alias): self
    {
        return new self(sprintf('Certificate with alias "%s" already exists', $alias));
    }

    /**
     * 创建存储操作失败异常
     *
     * @param string $operation 操作名称
     * @param string $reason    失败原因
     */
    public static function operationFailed(string $operation, string $reason): self
    {
        return new self(sprintf('Certificate store operation "%s" failed: %s', $operation, $reason));
    }

    /**
     * 创建无效证书格式异常
     *
     * @param string $format 格式名称
     */
    public static function invalidFormat(string $format): self
    {
        return new self(sprintf('Invalid certificate format: "%s"', $format));
    }
}
