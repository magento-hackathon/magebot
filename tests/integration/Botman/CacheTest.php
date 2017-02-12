<?php
namespace FireGento\MageBot\Botman;

use Magento\TestFramework\ObjectManager;
use Mpociot\BotMan\Conversation;
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
        $this->assertInstanceOf(CacheBridge::class, $this->cache);
    }
    /**
     * @dataProvider dataSaveCache
     */
    public function testSaveCache($key, $value)
    {
        $this->cache->put($key, $value, 1);
        $this->assertEquals($value, $this->cache->get($key));
    }
    public static function dataSaveCache()
    {
        return [
            'string' => ['foo', 'bar'],
            'array' => ['foo', ['bar' => 'bar']],
            'array_with_object' => [
                'foo',
                [
                    'conversation' => new ConversationStub,
                ]
            ],
        ];
    }
}

/**
 * Anonymous classes cannot be serialized by design, so we need a real stub
 */
class ConversationStub extends Conversation
{
    public function run()
    {
    }
}