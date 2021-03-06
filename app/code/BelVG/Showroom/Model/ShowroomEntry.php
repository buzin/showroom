<?php

namespace BelVG\Showroom\Model;

use BelVG\Showroom\Model\ResourceModel\ShowroomEntry as ShowroomEntryResourceModel;
use Magento\Framework\DataObject\IdentityInterface;
use Magento\Framework\Model\AbstractModel;

class ShowroomEntry extends AbstractModel implements IdentityInterface
{
    const CACHE_TAG = 'belvg_showroom_entry';
    protected $_cacheTag = 'belvg_showroom_entry';
    protected $_eventPrefix = 'belvg_showroom_entry';

    protected function _construct()
    {
        $this->_init(ShowroomEntryResourceModel::class);
    }

    /**
     * @return string[]
     */
    public function getIdentities(): array
    {
        return [self::CACHE_TAG . '_' . $this->getId()];
    }

    /**
     * @return array
     */
    public function getDefaultValues(): array
    {
        return [];
    }
}
