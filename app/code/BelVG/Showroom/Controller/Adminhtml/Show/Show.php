<?php

namespace BelVG\Showroom\Controller\Adminhtml\Show;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\View\Result\Page;
use Magento\Framework\View\Result\PageFactory;

class Show extends Action implements HttpGetActionInterface
{
    const MENU_ID = 'BelVG_Showroom::showroom_entries';

    protected PageFactory $pageFactory;

    /**
     * Show constructor.
     * @param Context $context
     * @param PageFactory $pageFactory
     */
    public function __construct(
        Context $context,
        PageFactory $pageFactory
    ) {
        parent::__construct($context);
        $this->pageFactory = $pageFactory;
    }

    /**
     * @return Page
     */
    public function execute(): Page
    {
        $page = $this->pageFactory->create();
        $page->setActiveMenu(static::MENU_ID);
        $page->getConfig()->getTitle()->prepend(__('Entries'));

        return $page;
    }
}
