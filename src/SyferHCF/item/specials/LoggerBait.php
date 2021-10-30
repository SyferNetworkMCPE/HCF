<?php

namespace SyferHCF\item\specials;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

class LoggerBait extends Custom {

    const CUSTOM_ITEM = "CustomItem";

    /**
     * LoggerBait Constructor.
     */
     
    public function __construct(){
        parent::__construct(self::SPAWN_EGG, "§l§aLogger Bait", [TE::GRAY."put it in the place where you want to\n§7simulate your disconnection from the server\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"]);
        $this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
        $this->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
    }

    /**
     * @return Int
     */
    public function getMaxStackSize() : Int {
        return 64;
    }
}

?>
