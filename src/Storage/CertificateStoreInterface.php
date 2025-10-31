<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Storage;

use Tourze\TLSX509Core\Certificate\X509Certificate;

/**
 * 证书存储接口
 *
 * 定义证书存储的基本操作
 */
interface CertificateStoreInterface
{
    /**
     * 添加证书到存储
     *
     * @param string          $alias       证书别名
     * @param X509Certificate $certificate 要存储的证书
     *
     * @return bool 是否成功添加
     */
    public function addCertificate(string $alias, X509Certificate $certificate): bool;

    /**
     * 获取存储中的证书
     *
     * @param string $alias 证书别名
     *
     * @return X509Certificate|null 如果存在则返回证书，否则返回null
     */
    public function getCertificate(string $alias): ?X509Certificate;

    /**
     * 删除存储中的证书
     *
     * @param string $alias 证书别名
     *
     * @return bool 是否成功删除
     */
    public function removeCertificate(string $alias): bool;

    /**
     * 检查证书是否存在
     *
     * @param string $alias 证书别名
     *
     * @return bool 如果存在则返回true
     */
    public function hasCertificate(string $alias): bool;

    /**
     * 获取存储中所有证书的别名
     *
     * @return array<string> 所有证书的别名
     */
    public function getAliases(): array;

    /**
     * 获取存储中的证书数量
     *
     * @return int 证书数量
     */
    public function count(): int;

    /**
     * 清除所有证书
     *
     * @return bool 是否成功清除
     */
    public function clear(): bool;

    /**
     * 导出证书为PEM格式
     *
     * @param string $alias 证书别名
     *
     * @return string|null 证书的PEM格式，如果不存在则返回null
     */
    public function exportAsPEM(string $alias): ?string;

    /**
     * 导入证书链
     *
     * @param string $alias        证书链的别名前缀
     * @param array<int, X509Certificate>  $certificates 证书链
     *
     * @return array<string> 导入的证书别名
     */
    public function importCertificateChain(string $alias, array $certificates): array;
}
