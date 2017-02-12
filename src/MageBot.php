<?php

namespace FireGento\MageBot;

use Mpociot\BotMan\BotMan;
use Mpociot\BotMan\DriverManager;
use Mpociot\BotMan\BotManFactory;
use FireGento\MageBot\Botman\BotmanConfig;
use Magento\Catalog\Model\ProductRepository;
use Mpociot\BotMan\Interfaces\CacheInterface;
use FireGento\MageBot\Botman\BotmanWebDriver;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use FireGento\MageBot\Conversations\CustomerGroupConversation;

/**
 * Entry point for MageBot features (Magento independent)
 *
 * @package FireGento
 */
class MageBot
{
    /** @var BotmanConfig */
    private $config;
    /** @var  CacheInterface */
    private $cache;
    /**
     * @var CustomerGroupConversation
     */
    private $customerGroupConversation;

    public function __construct(
        BotmanConfig $config,
        CacheInterface $cache,
        CustomerGroupConversation $customerGroupConversation
    )
    {
        $this->config = $config;
        $this->cache = $cache;
        $this->customerGroupConversation = $customerGroupConversation;
    }

    public function start()
    {
        DriverManager::loadDriver(BotmanWebDriver::class);

        /** @var BotMan $botman */
        $botman = BotManFactory::create($this->config->toArray(), $this->cache);
        $this->config->configureBotMan($botman);

        $botman->hears('hi|hello|hallo', function (BotMan $bot) {
            $bot->reply('Hi! We sell really great stuff. But first, I\'ll ask you a few questions to understand your need.');
            $bot->startConversation($this->customerGroupConversation);
        });

        $botman->listen();

    }
}