<?php

namespace SyferHCF\item\specials;

use SyferHCF\Loader;

use SyferHCF\packages\PackageManager;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

use pocketmine\Player;
use pocketmine\block\Block;
use pocketmine\math\Vector3;
use pocketmine\item\ItemFactory;

class PartnerPackages extends Custom {

    const CUSTOM_ITEM = "CustomItem";

    /**
	 * PartnerPackages Constructor.
	 */
	public function __construct(){
		parent::__construct(self::LIT_PUMPKIN, "§d§k¡!§r §l§6Partner Packages §r§d§k¡!§r", [TE::GRAY."click on a block to open and get the rewards".TE::GRAY."\n\n".TE::DARK_PURPLE."Available in our Store §dsyferhcf.tebex.io"]);
		$this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
		$this->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
	}
}

?>
