<?php
namespace FireGento\MageBot\Botman;

use Magento\TestFramework\ObjectManager;
use Mpociot\BotMan\Interfaces\CacheInterface;

class CacheTest extends \PHPUnit_Framework_TestCase
{
    /** @var  ObjectManager */
    private $objectManager;
    /** @var  CacheInterface */
    private $cache;

    protected function setUp()
    {
        $this->objectManager = ObjectManager::getInstance();
        $this->cache = $this->objectManager->get(CacheInterface::class);
    }
    public function testInstantiation()
    {
        $this->assertInstanceOf(BotmanCacheMagento::class, $this->cache);
    }
    public function testSaveCache()
    {
        $this->cache->put('foo', 'bar', 1);
        $this->assertEquals('bar', $this->cache->get('foo'));
    }
    public function testSaveCacheWithArray()
    {
        $this->cache->put('foo', ['bar' => 'bar'], 1);
        $this->assertEquals(['bar' => 'bar'], $this->cache->get('foo'));
    }
}