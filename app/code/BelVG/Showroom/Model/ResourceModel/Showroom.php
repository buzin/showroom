<?php


namespace BelVG\Showroom\Model\ResourceModel;


use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Showroom extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('belvg_showroom', 'showroom_id');
    }
}
