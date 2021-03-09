<?php

namespace BelVG\Showroom\Model\ResourceModel\ShowroomEntry;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use BelVG\Showroom\Model\ShowroomEntry as ShowroomEntryModel;
use BelVG\Showroom\Model\ResourceModel\ShowroomEntry as ShowroomEntryResourceModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'entry_id';
    protected $_eventPrefix = 'belvg_showroom_entry_collection';
    protected $_eventObject = 'showroom_entry_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ShowroomEntryModel::class, ShowroomEntryResourceModel::class);
    }
}
