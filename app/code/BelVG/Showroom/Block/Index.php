<?php

namespace BelVG\Showroom\Block;

use Magento\Framework\View\Element\Template;

class Index extends \Magento\Framework\View\Element\Template
{
    protected $_showroomFactory;

    public function __construct(Template\Context $context, $data,
        \BelVG\Showroom\Model\ShowroomFactory $showroomFactory
    )
    {
        $this->_showroomFactory = $showroomFactory;
        parent::__construct($context, $data);
    }

    public function getTest()
    {
        return 'Test';
    }

    public function getShowrooms()
    {
        $data = [];
        $showroom = $this->_showroomFactory->create();
        $collection = $showroom->getCollection();
        foreach($collection as $item) {
            $data[] = $item->getData();
        }
        return $data;
    }

}
