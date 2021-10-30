<?php

namespace SyferHCF\enchantments\type;

use pocketmine\utils\TextFormat as TE;

use SyferHCF\enchantments\CustomEnchantment;

use pocketmine\entity\{Effect, EffectInstance};

class WaterBreathing extends CustomEnchantment {

    /**
     * WaterBreathingEnchantment Constructor.
     */
    public function __construct(){
        parent::__construct($this->getId(), $this->getName(), self::RARITY_COMMON, self::SLOT_ARMOR, self::SLOT_NONE, 2);
    }

    /**
     * @return Int
     */
    public function getId() : Int {
        return 51;
    }

    /**
     * @return String
     */
    public function getName() : String {
        return "Water Breathing";
    }
    
    /**
     * @return String
     */
    public function getNameWithFormat() : String {
    	return TE::RESET.TE::AQUA."Water Breathing II";
    }

        /**
     * @return EffectInstance
     */
    public function getEffectsByEnchantment() : EffectInstance {
        return new EffectInstance(Effect::getEffect(Effect::WATER_BREATHING), 60, ($this->getMaxLevel() - 1));
    }
    
    /**
     * @return Int
     */
    public function getEnchantmentPrice() : Int {
    	return 35000;
   }
}

?>
