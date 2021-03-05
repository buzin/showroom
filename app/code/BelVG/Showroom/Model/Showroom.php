<?php

namespace BelVG\Showroom\Model;

use BelVG\Showroom\Model\ResourceModel\Showroom as ShowroomResourceModel;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class Showroom extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'belvg_showroom';

    protected $_cacheTag = 'belvg_showroom';

    protected $_eventPrefix = 'belvg_showroom';

    protected function _construct()
    {
        $this->_init(ShowroomResourceModel::class);
    }

    public function getIdentities()
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    public function getDefaultValues()
    {
        $values = [];

        return $values;
    }
}
