<?php

declare(strict_types=1);

namespace Tourze\TLSCertStore\Tests\Storage;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\TestCase;
use Tourze\TLSCertStore\Storage\InMemoryCertificateStore;
use Tourze\TLSX509Core\Certificate\X509Certificate;

/**
 * @internal
 */
#[CoversClass(InMemoryCertificateStore::class)]
final class InMemoryCertificateStoreTest extends TestCase
{
    private InMemoryCertificateStore $store;

    private X509Certificate $mockCertificate;

    protected function setUp(): void
    {
        $this->store = new InMemoryCertificateStore();
        $this->mockCertificate = new class extends X509Certificate {
            public function toPEM(): string
            {
                return "-----BEGIN CERTIFICATE-----\nMock Certificate\n-----END CERTIFICATE-----";
            }
        };
    }

    public function testAddCertificate(): void
    {
        $result = $this->store->addCertificate('test-cert', $this->mockCertificate);

        $this->assertTrue($result);
        $this->assertTrue($this->store->hasCertificate('test-cert'));
        $this->assertSame($this->mockCertificate, $this->store->getCertificate('test-cert'));
    }

    public function testAddAndGetCertificate(): void
    {
        $this->assertTrue($this->store->addCertificate('test-cert', $this->mockCertificate));
        $this->assertSame($this->mockCertificate, $this->store->getCertificate('test-cert'));
    }

    public function testGetCertificateNonExistent(): void
    {
        $this->assertNull($this->store->getCertificate('non-existent'));
    }

    public function testHasCertificate(): void
    {
        $this->store->addCertificate('test-cert', $this->mockCertificate);

        $this->assertTrue($this->store->hasCertificate('test-cert'));
        $this->assertFalse($this->store->hasCertificate('non-existent'));
    }

    public function testRemoveCertificate(): void
    {
        $this->store->addCertificate('test-cert', $this->mockCertificate);

        $this->assertTrue($this->store->removeCertificate('test-cert'));
        $this->assertFalse($this->store->hasCertificate('test-cert'));
        $this->assertFalse($this->store->removeCertificate('non-existent'));
    }

    public function testGetAliases(): void
    {
        $this->store->addCertificate('cert1', $this->mockCertificate);
        $this->store->addCertificate('cert2', $this->mockCertificate);

        $aliases = $this->store->getAliases();

        $this->assertCount(2, $aliases);
        $this->assertContains('cert1', $aliases);
        $this->assertContains('cert2', $aliases);
    }

    public function testCount(): void
    {
        $this->assertEquals(0, $this->store->count());

        $this->store->addCertificate('cert1', $this->mockCertificate);
        $this->assertEquals(1, $this->store->count());

        $this->store->addCertificate('cert2', $this->mockCertificate);
        $this->assertEquals(2, $this->store->count());

        $this->store->removeCertificate('cert1');
        $this->assertEquals(1, $this->store->count());
    }

    public function testClear(): void
    {
        $this->store->addCertificate('cert1', $this->mockCertificate);
        $this->store->addCertificate('cert2', $this->mockCertificate);

        $this->assertTrue($this->store->clear());
        $this->assertEquals(0, $this->store->count());
    }

    public function testExportAsPEM(): void
    {
        $this->store->addCertificate('test-cert', $this->mockCertificate);

        $pem = $this->store->exportAsPEM('test-cert');
        $this->assertEquals("-----BEGIN CERTIFICATE-----\nMock Certificate\n-----END CERTIFICATE-----", $pem);

        $this->assertNull($this->store->exportAsPEM('non-existent'));
    }

    public function testImportCertificateChain(): void
    {
        $chain = [
            $this->mockCertificate,
            $this->mockCertificate,
            $this->mockCertificate,
        ];

        $aliases = $this->store->importCertificateChain('chain', $chain);

        $this->assertCount(3, $aliases);
        $this->assertEquals(['chain-1', 'chain-2', 'chain-3'], $aliases);
        $this->assertEquals(3, $this->store->count());
    }

    public function testGetAll(): void
    {
        $this->store->addCertificate('cert1', $this->mockCertificate);
        $this->store->addCertificate('cert2', $this->mockCertificate);

        $all = $this->store->getAll();

        $this->assertCount(2, $all);
        $this->assertSame($this->mockCertificate, $all['cert1']);
        $this->assertSame($this->mockCertificate, $all['cert2']);
    }
}
