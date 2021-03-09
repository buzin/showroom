<?php

namespace BelVG\Showroom\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class ShowroomEntry extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('belvg_showroom_entry', 'entry_id');
    }
}
