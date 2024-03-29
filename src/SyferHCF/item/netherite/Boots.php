<?php

namespace SyferHCF\item\netherite;

use SyferHCF\item\Items;

use pocketmine\item\Armor;

class Boots extends Armor {

    /**
     * Boots Constructor.
     * @param Int $meta
     */
    public function __construct(Int $meta = 0){
        parent::__construct(Items::NETHERITE_BOOTS, $meta, "Netherite Boots");
    }

    /**
     * @return Int
     */
    public function getDefensePoints() : Int {
        return 3;
    }

    /**
     * @return Int
     */
    public function getMaxDurability() : Int {
        return 481;
    }
}

?>
