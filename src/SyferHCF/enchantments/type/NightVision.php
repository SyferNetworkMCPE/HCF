<?php

namespace SyferHCF\enchantments\type;

use pocketmine\utils\TextFormat as TE;

use SyferHCF\enchantments\CustomEnchantment;

use pocketmine\entity\{Effect, EffectInstance};

class NightVision extends CustomEnchantment {

    /**
     * NightVisionEnchantment Constructor.
     */
    public function __construct(){
        parent::__construct($this->getId(), $this->getName(), self::RARITY_COMMON, self::SLOT_ARMOR, self::SLOT_NONE, 2);
    }

    /**
     * @return Int
     */
    public function getId() : Int {
        return 50;
    }

    /**
     * @return String
     */
    public function getName() : String {
        return "Night Vision";
    }
    
    /**
     * @return String
     */
    public function getNameWithFormat() : String {
    	return TE::RESET.TE::YELLOW."Night Vision II";
    }

        /**
     * @return EffectInstance
     */
    public function getEffectsByEnchantment() : EffectInstance {
        return new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 60, ($this->getMaxLevel() - 1));
    }
    
    /**
     * @return Int
     */
    public function getEnchantmentPrice() : Int {
    	return 35000;
   }
}

?>
