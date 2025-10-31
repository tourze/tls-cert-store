<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Exception;

/**
 * 证书文件异常
 *
 * 当证书文件操作发生错误时抛出
 */
class CertificateFileException extends \RuntimeException
{
    /**
     * 创建文件不存在异常
     *
     * @param string $filePath 文件路径
     */
    public static function fileNotFound(string $filePath): self
    {
        return new self('证书文件不存在: ' . $filePath);
    }

    /**
     * 创建文件读取失败异常
     *
     * @param string $filePath 文件路径
     */
    public static function fileReadFailed(string $filePath): self
    {
        return new self('无法读取证书文件: ' . $filePath);
    }
}
