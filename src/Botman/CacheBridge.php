<?php

namespace FireGento\MageBot\Botman;

use Magento\Framework\App\CacheInterface as MagentoCache;
use Mpociot\BotMan\Interfaces\CacheInterface as BotmanCache;

/**
 * Connects Magento cache as storage for Botman
 *
 * @todo consider moving into different namespace to keep \Botman Magento independent
 */
class CacheBridge implements BotmanCache
{
    const CACHE_TAG_BOTMAN = 'botman';

    /** @var  MagentoCache */
    private $magentoCache;

    public function __construct(MagentoCache $magentoCache)
    {
        $this->magentoCache = $magentoCache;
    }

    public function has($key)
    {
        return $this->magentoCache->load($key) !== false;
    }

    public function get($key, $default = null)
    {
        if (! $this->has($key)) {
            return $default;
        }
        return $this->deserializeValue($this->magentoCache->load($key));
    }

    public function pull($key, $default = null)
    {
        $value = $this->get($key, $default);
        $this->magentoCache->remove($key);
        return $value;
    }

    public function put($key, $value, $minutes)
    {
        $this->magentoCache->save($this->serializeValue($value), $key, [self::CACHE_TAG_BOTMAN], $minutes * 60);
    }

    private function serializeValue($value) : string
    {
        return \serialize($value);
    }

    private function deserializeValue(string $value)
    {
        return \unserialize($value, ['allowed_classes' => true]);
    }

}