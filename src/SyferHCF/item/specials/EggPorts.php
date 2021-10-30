<?php

namespace SyferHCF\item\specials;

use pocketmine\event\entity\ProjectileHitEntityEvent;
use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\utils\TextFormat as TE;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\item\enchantment\{Enchantment, EnchantmentInstance};

class EggPorts extends CustomProjectileItem {
	
	const CUSTOM_ITEM = "CustomItem";
	
	/**
	 * EggPorts Constructor.
	 */
	public function __construct(){
		parent::__construct(self::EGG, "§l§aEggPorts", [TE::GRAY."launch it to the enemy between a radio of 7 blocks\n§7and the enemy would change from position with you\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"]);
		$this->setNamedTagEntry(new CompoundTag(self::CUSTOM_ITEM));
		$this->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
	}
	
	/**
     * @return Int
     */
    public function getMaxStackSize() : Int {
        return 16;
	}
	
	/**
	 * @return String
	 */
	public function getProjectileEntityType() : String {
		return "Egg";
	}
	
	/**
	 * @return float
	 */
	public function getThrowForce() : float {
        return 2.0;
	}

    /**
     * @param Player $player
     * @param Vector3 $directionVector
     */
    public function onClickAir(\pocketmine\Player $player, \pocketmine\math\Vector3 $directionVector) : bool {
        return true;
    }
}

?>
