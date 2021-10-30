<?php

namespace SyferHCF\item\specials;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

class EffectsBard extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * EffectsBard Constructor.
	 */
	public function __construct(){
		parent::__construct(self::FIRE, "§l§aEffectsBard", [TE::GRAY."by using this item you will receive force 2, resistance 3, speed2, jump 3 for 10 seconds.\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"]);
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
