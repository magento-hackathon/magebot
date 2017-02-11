<?php
namespace FireGento\MageBot\Controller\Index;

use FireGento\MageBot\MageBot;
use Magento\Framework\App\Action\Action;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\ResultFactory;

class Index extends Action
{
    /** @var  MageBot */
    private $mageBot;

    public function __construct(Context $context, MageBot $mageBot)
    {
        parent::__construct($context);
        $this->mageBot = $mageBot;
    }


    public function execute()
    {
        $this->mageBot->start();
        return $this->emptyResult();
    }

    /**
     * @return \Magento\Framework\Controller\Result\Raw
     */
    private function emptyResult()
    {
        /** @var \Magento\Framework\Controller\Result\Raw $resultRaw */
        $resultRaw = $this->resultFactory->create(ResultFactory::TYPE_RAW);
        return $resultRaw;
    }

}