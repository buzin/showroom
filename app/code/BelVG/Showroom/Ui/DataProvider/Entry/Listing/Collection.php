<?php

namespace BelVG\Showroom\Ui\DataProvider\Entry\Listing;

use Magento\Framework\View\Element\UiComponent\DataProvider\SearchResult;

class Collection extends SearchResult
{
    /**
     * Override _initSelect to add custom columns
     *
     * @return void
     */
    protected function _initSelect()
    {
        parent::_initSelect();
        $this->getSelect()->joinLeft(
            ['showroom_table' => $this->getTable('belvg_showroom')],
            'main_table.showroom_id = showroom_table.showroom_id',
            ['showroom_name' => 'name']
        );
        $this->addFilterToMap('showroom_name', 'showroom_table.name');
        $this->addFilterToMap('name', 'main_table.name');
    }
}

