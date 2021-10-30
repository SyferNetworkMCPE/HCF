<?php

namespace SyferHCF\item\specials;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

class UnBan extends Custom {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * UnBan Constructor.
	 */
	public function __construct(){
		parent::__construct(self::PAPER, "§l§4UNBAN §r§7(§dx1 Use§7)", [TE::BOLD.TE::LIGHT_PURPLE."UNBAN USE".TE::RESET."\n".TE::GRAY."If you get this item you can go to discord and".TE::RESET."\n".TE::GRAY."claim it so that our staffs know that if you have it".TE::RESET."\n\n".TE::DARK_PURPLE."Avaiable exclusively at §dCitadel Crate"]);
		$this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
		$this->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
	}
	
	/**
     * @return Int
     */
    public function getMaxStackSize() : Int {
        return 1;
    }
}

?>
