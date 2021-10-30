<?php

namespace SyferHCF\item\specials;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

class RageBall extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * RageBall Constructor.
	 */
	
	public function __construct(){
		parent::__construct(self::FIRE_CHARGE, "§l§aRageBall", [TE::GRAY."when using this item you will receive the effects\n§7of strength 2 for 15 seconds and slowness 3 for 10 seconds\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"]);
		$this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
		$this->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
	}
	
	/**
     * @return Int
     */
    public function getMaxStackSize() : Int {
        return 16;
    }
}

?>
