<?php

namespace BelVG\Showroom\Model\ResourceModel\Showroom;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use BelVG\Showroom\Model\Showroom as ShowroomModel;
use BelVG\Showroom\Model\ResourceModel\Showroom as ShowroomResourceModel;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'showroom_id';
    protected $_eventPrefix = 'belvg_showroom_collection';
    protected $_eventObject = 'showroom_collection';

    /**
     * Define resource model
     *
     * @return void
     */
    protected function _construct()
    {
        $this->_init(ShowroomModel::class, ShowroomResourceModel::class);
    }
}
