<?php

namespace FireGento\MageBot;

use Mpociot\BotMan\BotMan;
use Psr\Log\LoggerInterface;
use Mpociot\BotMan\DriverManager;
use Mpociot\BotMan\BotManFactory;
use Magento\Catalog\Model\Product;
use Magento\Framework\Shell\Driver;
use Mpociot\BotMan\Facebook\Element;
use Mpociot\BotMan\Facebook\ListTemplate;
use Mpociot\BotMan\Facebook\ElementButton;
use FireGento\MageBot\Botman\BotmanConfig;
use Magento\Catalog\Model\ProductRepository;
use Mpociot\BotMan\Facebook\GenericTemplate;
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
    private $logger;
    /**
     * @var ProductRepository
     */
    private $productRepository;
    /**
     * @var SearchCriteriaBuilder
     */
    private $searchCriteriaBuilder;
    /**
     * @var ImageBuilder
     */
    private $imageBuilder;

    public function __construct(
        BotmanConfig $config,
        CacheInterface $cache,
        LoggerInterface $logger,
        ProductRepository $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ImageBuilder $imageBuilder
    )
    {
        $this->config = $config;
        $this->cache = $cache;
        $this->logger = $logger;
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->imageBuilder = $imageBuilder;
    }

    public function start()
    {
        DriverManager::loadDriver(BotmanWebDriver::class);

        /** @var BotMan $botman */
        $botman = BotManFactory::create($this->config->toArray(), $this->cache);
        $this->config->configureBotMan($botman);

        $botman->hears('hi|hello|hallo', function (BotMan $bot) {
            $bot->reply('Hi! Current commands: list, group');
        });

        $products = $this->productRepository->getList($this->searchCriteriaBuilder->create())->getItems();

        $botman->hears('list', function (BotMan $bot) use ($products) {

            $list = GenericTemplate::create();

            /** @var Product $product */
            foreach ($products as $product) {
                $list->addElement(
                    Element::create($product->getName())
                        ->subtitle(strip_tags(html_entity_decode($product->getShortDescription())))
                        ->image($this->imageBuilder->setProduct($product)
                            ->setImageId('product_page_image_large')
                            ->create()->getImageUrl())
                        ->addButton(ElementButton::create('Visit')
                            ->url($product->getProductUrl())
                        ));
            }

            $bot->reply($list);

        });

        $botman->hears('group', function (BotMan $bot) {
            $bot->startConversation(new CustomerGroupConversation);
        });

        $botman->listen();

    }
}