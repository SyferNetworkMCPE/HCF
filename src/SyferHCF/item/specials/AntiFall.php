<?php

namespace SyferHCF\item\specials;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

class AntiFall extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * AntiFall Constructor.
	 */
	
	public function __construct(){
		parent::__construct(self::STRING, "§l§aAntiFall", [TE::GRAY."by using this special item I'll rise\n§7to heaven for 15 seconds quickly.\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"]);
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
