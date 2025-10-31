<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Service;

use Tourze\TLSX509Core\Certificate\X509Certificate;

/**
 * 证书加载器接口
 *
 * 定义从不同来源加载X.509证书的方法
 */
interface CertificateLoaderInterface
{
    /**
     * 从PEM格式字符串加载X.509证书
     *
     * @param string $pemString PEM格式的证书字符串
     *
     * @return X509Certificate 加载的证书对象
     *
     * @throws \RuntimeException 如果加载失败
     */
    public function loadFromPEMString(string $pemString): X509Certificate;

    /**
     * 从DER格式字节加载X.509证书
     *
     * @param string $derBytes DER编码的证书二进制数据
     *
     * @return X509Certificate 加载的证书对象
     *
     * @throws \RuntimeException 如果加载失败
     */
    public function loadFromDERBytes(string $derBytes): X509Certificate;

    /**
     * 从文件加载X.509证书
     *
     * @param string $filePath 证书文件路径
     * @param string $format   文件格式，可以是'PEM'或'DER'
     *
     * @return X509Certificate 加载的证书对象
     *
     * @throws \RuntimeException 如果加载失败或文件不存在
     */
    public function loadFromFile(string $filePath, string $format = 'PEM'): X509Certificate;

    /**
     * 从证书链加载多个X.509证书
     *
     * @param string $pemString 包含多个PEM格式证书的字符串
     *
     * @return array<X509Certificate> 加载的证书对象数组
     *
     * @throws \RuntimeException 如果加载失败
     */
    public function loadCertificateChain(string $pemString): array;
}
