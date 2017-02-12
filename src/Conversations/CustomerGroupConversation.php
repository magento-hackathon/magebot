<?php

namespace FireGento\MageBot\Conversations;

use Magento\Catalog\Model\Product;
use Mpociot\BotMan\Answer;
use Mpociot\BotMan\Conversation;
use Mpociot\BotMan\Facebook\Element;
use Mpociot\BotMan\Facebook\ElementButton;
use Mpociot\BotMan\Facebook\ButtonTemplate;
use Magento\Catalog\Model\ProductRepository;
use Magento\Catalog\Block\Product\ImageBuilder;
use Magento\Framework\Api\SearchCriteriaBuilder;
use Mpociot\BotMan\Facebook\GenericTemplate;

class CustomerGroupConversation extends Conversation
{
    protected $group;

    protected $weight;
    private $productRepository;
    private $searchCriteriaBuilder;
    private $imageBuilder;

    public function __construct(
        ProductRepository $productRepository,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        ImageBuilder $imageBuilder
    )
    {
        $this->productRepository = $productRepository;
        $this->searchCriteriaBuilder = $searchCriteriaBuilder;
        $this->imageBuilder = $imageBuilder;
    }

    public function askForMainGroup()
    {
        $botResponse = ButtonTemplate::create('What is your goal?')
            ->addButton(ElementButton::create('Loose Weight')->type('postback')->payload('weight'))
            ->addButton(ElementButton::create('Eat Healthy')->type('postback')->payload('eat'))
            ->addButton(ElementButton::create('Become Fit')->type('postback')->payload('fit'));

        $this->ask($botResponse, function (Answer $answer) {

            $this->group = $answer->getText();

            if($answer->getText() === "weight") {
                $this->askForWeight();
            } else if ($answer->getText() === "eat") {
                // Cookbooks

                $this->say('Ok, members who want to keep their weight and eat healthy like our cookbooks:');

                $products = $this->productRepository->getList($this->searchCriteriaBuilder->create())->getItems();
                $this->returnProducts($products);

                $this->askFinalQuestion();
            } else {
                // Fitness Products

                $this->say('Ok, sounds great. Take a look at these fitness products:');

                $products = $this->productRepository->getList($this->searchCriteriaBuilder->create())->getItems();
                $this->returnProducts($products);

                $this->askFinalQuestion();
            }
        });
    }

    private function askForWeight()
    {
        $botResponse = ButtonTemplate::create('Do you want to lose more than 10kg?')
            ->addButton(ElementButton::create('Yes')->type('postback')->payload('yes'))
            ->addButton(ElementButton::create('No')->type('postback')->payload('no'));

        $this->ask($botResponse, function (Answer $answer) {

            if($answer->getText() === "yes") {

                // ask for member status

                $this->askForMemberStatus();

            } else {

                $this->say('Excellent, take a look at these products:');

                //$products = $this->productRepository->getList($this->searchCriteriaBuilder->create())->getItems();
                //$this->returnProducts($products);

                //$this->askFinalQuestion();
            }

        });
    }

    public function run()
    {
        $this->askForMainGroup();
    }

    private function askForMemberStatus()
    {
        $this->say('Loosing much Weight works best in a group.');

        $botResponse = ButtonTemplate::create('Are you currenty a Weight Watchers member?')
            ->addButton(ElementButton::create('Yes')->type('postback')->payload('yes'))
            ->addButton(ElementButton::create('No')->type('postback')->payload('no'));

        $this->ask($botResponse, function (Answer $answer) {

            if($answer->getText() === "yes") {

                $this->say('Nice, you can try our cookbooks for healthier eating to speed up your progress or take alook at our fitness stuff');

                $products = $this->productRepository->getList($this->searchCriteriaBuilder->create())->getItems();
                $this->returnProducts($products);

                $this->askFinalQuestion();

            } else {

                $this->say('We like to support you with our best program:');

                $this->say(GenericTemplate::create()
                    ->addElement(
                    Element::create('Feel Good')
                        ->subtitle('Das Weight Watchers Programm')
                        ->image('https://www.weightwatchers.com/de/sites/de/files/styles/wwvs_bts_image_thumbnail_large/public/welcher-sporttyp-bist-du_thumbnail.png?itok=oUIUqiuY')
                        ->addButton(ElementButton::create('Visit')
                            ->url('https://www.weightwatchers.com/de/programm')
                        ))
                );

                $this->askFinalQuestion();

            }

        });
    }

    private function askFinalQuestion()
    {
        $response = ButtonTemplate::create('Anything else I can help you with?')
            ->addButton(ElementButton::create('Yes')->type('postback')->payload('yes'))
            ->addButton(ElementButton::create('No')->type('postback')->payload('no'));

        $this->ask($response, function (Answer $answer) {

            if($answer->getText() === "yes") {
                $this->askForMainGroup();
            } else {
                $this->say('Ok, goodbye!');
            }
        });
    }

    private function returnProducts($products)
    {
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

        $this->say($list);
    }
}