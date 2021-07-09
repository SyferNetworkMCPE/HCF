<?php


namespace BullHCF\item;


use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\utils\TextFormat;

class PackageItem extends Item
{
    public function __construct()
    {
        parent::__construct(ItemIds::ENDER_CHEST, 0, TextFormat::colorize("Package Item", "&"));
        $this->setCount(1);
        $this->setDamage(0);
    }
}