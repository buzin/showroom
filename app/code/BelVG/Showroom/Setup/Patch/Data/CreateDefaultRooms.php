<?php

namespace BelVG\Showroom\Setup\Patch\Data;

use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;

class CreateDefaultRooms implements DataPatchInterface
{

    /**
     * @var ModuleDataSetupInterface
     */
    private ModuleDataSetupInterface $moduleDataSetup;

    public function __construct(ModuleDataSetupInterface $moduleDataSetup)
    {
        $this->moduleDataSetup = $moduleDataSetup;
    }

    /**
     * @inheritDoc
     */
    public static function getDependencies()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function getAliases()
    {
        return [];
    }

    /**
     * @inheritDoc
     */
    public function apply()
    {
        $this->moduleDataSetup->getConnection()->startSetup();

        $showrooms = [
            ['name' => 'BMW'],
            ['name' => 'Audi'],
            ['name' => 'Mercedes']
        ];
        $this->moduleDataSetup->getConnection()->insertMultiple('belvg_showroom', $showrooms);

        $this->moduleDataSetup->getConnection()->endSetup();
    }
}
