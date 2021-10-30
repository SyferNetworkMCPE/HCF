<?php

namespace SyferHCF\player;

use SyferHCF\{Loader, Factions};
use SyferHCF\enchantments\CustomEnchantment;
use SyferHCF\provider\YamlProvider;
use SyferHCF\API\InvMenu\Handler;

use pocketmine\math\Vector3;
use pocketmine\level\Level;

use pocketmine\utils\{Binary, Internet, Config, TextFormat as TE};
use pocketmine\item\{Item, ItemIds, ItemFactory};
use pocketmine\entity\{Effect, EffectInstance};

use pocketmine\network\mcpe\protocol\GameRulesChangedPacket;
use pocketmine\network\mcpe\protocol\ChangeDimensionPacket;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\network\mcpe\protocol\AvailableCommandsPacket;
use pocketmine\network\mcpe\protocol\types\CommandData;
use pocketmine\network\mcpe\protocol\types\CommandParameter;
use pocketmine\network\mcpe\protocol\types\CommandEnum;
use pocketmine\network\mcpe\protocol\types\CommandEnumConstraint;
use pocketmine\network\mcpe\protocol\ScriptCustomEventPacket;

class Player extends \pocketmine\Player {

    const LEADER = "Leader", CO_LEADER = "Co_Leader", MEMBER = "Member";

    const FACTION_CHAT = "Faction", PUBLIC_CHAT = "Public", ALLY_CHAT = "Ally", STAFF_CHAT = "Staff";

    /** @var Int */
    protected $bardEnergy = 0, $opbardEnergy = 0, $archerEnergy = 0, $oparcherEnergy = 0;

    /** @var Int */
    protected $combatTagTime = 0;

    /** @var Int */
    protected $enderPearlTime = 0;
    
    /** @var Int */
    protected $snowBallTime = 0;
    
    /** @var Int */
    protected $camuflajeTime = 0;
    
    /** @var Int */
    protected $refillTime = 0;

    /** @var Int */
    protected $stormBreakerTime = 0;

    /** @var int */
    protected $ninjaShearTime = 0;

    /** @var Int  */
    protected $potionCounterTime = 0;

    /** @var Int */
    protected $antiTrapperTime = 0;
    
    /** @var Int */
    protected $eggTime = 0;
    
    /** @var Int */
    protected $backstapTime = 0;
    
    /** @var Int */
    protected $opbackstapTime = 0;
    
    /** @var Int */
    protected $archerTagTime = 0;
    
    /** @var Int */
    protected $oparcherTagTime = 0;
    
    /** @var Int */
    protected $goldenAppleTime = 0;

    /** @var Int */
    protected $playerClaimCost = 0;

    /** @var Int */
    protected $movementTime = 0;
    
    /** @var Int */
    protected $mageEnergy = 0;

    /** @var Int */
    protected $teleportHomeTime = 0, $teleportStuckTime = 0, $logoutTime = 0;
    
    /** @var Int */
    protected $invincibilityTime = 0;
    
    /** @var Int */
    protected $teleportSpawn = 0;

    /** @var Int */
    protected $specialItemTime = 0; 
    
    /** @var Int */
    protected $loggerBaitTime = 0;
    
    /** @var Int */
    protected $resetItemsTime = 0;
    
    /** @var Int */
    protected $effectDisableTime = 0;
    
    /** @var Int */
    protected $loggerBait = 0;
    
    /** @var Int */
    protected $resetItems = 0;
    
    /** @var Int */
    protected $globalItemTime = 0;

    /** @var bool */
    protected $globalItem = false;

    /** @var bool */
    protected $bardEffects = false;
    
    /** @var bool  */
    protected $portalPlace = false;
    
    /** @var bool  */
    protected $update = false;
    
    /** @var bool */
    protected $refill = false;
    
    /** @var bool */
    protected $backstap = false;
    
    /** @var bool */
    protected $camuflaje = false;
    
    /** @var bool */
    protected $opbardEffects = false;
    
    /** @var bool  */
    protected $potionCounter = false;

    /** @var bool */
    protected $godMode = false;

    /** @var bool */
    protected $combatTag = false;

    /** @var bool */
    protected $enderPearl = false;

    /** @var bool */
    protected $stormBreaker = false;

    /** @var bool */
    protected $ninjaShear = false;
    
    /** @var bool */
    protected $antiTrapper = false, $antiTrapperTarget = false;

    /** @var bool */
    protected $egg = false;
    
    /** @var bool */
    protected $archerTag = false;
    
        /** @var bool */
    protected $oparcherTag = false;
    
    /** @var bool */
    protected $goldenApple = false;

    /** @var bool */
    protected $autoFeed = false;

    /** @var bool */
    protected $canInteract = false;
    
    /** @var bool */
    protected $viewingMap = false;

    /** @var bool */
    protected $invitation = false;
    
    /** @var bool */
    protected $invincibility = false;

    /** @var bool */
    protected $specialItem = false;
    
    /** @var bool */
    protected $focus = false;
    
    /** @var bool */
    protected $teleportHome = false, $teleportStuck = false, $logout = false;
    
    /** @var String */
    protected $rank = null;

    /** @var String */
    protected $prefix = null;

    /** @var String */
    protected $chat = null;
    
    /** @var String */
    protected $playerChat = null;
    
    /** @var String */
    protected $currentInvite = null;
    
    /** @var String */
    protected $currentRegion = null;

    /** @var String */
    protected $focusFaction = null;
    
    /** @var Array[] */
    protected $inventoryHandler = [];
    
    /** @var Vector3 */
    protected $ninjaPosition = null;

    /** @var Array[] */
    protected $armorEffects = [];
    
    /** @var Array[] */
    protected $playerClass = [];

    /**
     * @param Int $currentTick
     * @return bool
     */
    public function onUpdate(Int $currentTick) : bool {
        $items = $this->getArmorInventory()->getContents();
        foreach($items as $slot => $item){
            foreach($item->getEnchantments() as $enchantment){
                if($enchantment->getType() instanceof CustomEnchantment){
                    $this->addEffect($enchantment->getType()->getEffectsByEnchantment());
                }
            }
        }
        if($this->isAutoFeed()) $this->setFood(20);
        return parent::onUpdate($currentTick);
    }
    
    /**
     * @param String $server
     * @return bool
     */
    public function transferToServer(?String $server) : bool {
    	$pk = new ScriptCustomEventPacket();
        $pk->eventName = "bungecoord:main";
		$pk->eventData = Binary::writeShort(strlen("Connect")) . "Connect" . Binary::writeShort(strlen($server)) . $server;
        $this->sendDataPacket($pk);
        return true;
    }

     /**
     * @param String $rank
     * If the value is null then the Member rank is placed
     */
    public function setRank(?String $rank = null){
    	$this->rank = $rank;
    }
    
    /**
     * @return String
     */
    public function getRank() : String {
    	return $this->rank === null ? "Member" : $this->rank;
    }

    /**
     * @param String $prefix
     */
    public function setPrefix(String $prefix = null){
        $this->prefix = $prefix;
    }

    /**
     * @return String|null
     */
    public function getPrefix() : ?String {
        return $this->prefix;
    }

    /**
     * @return Int|null
     */
    public function getBardEnergy() : ?Int {
        return $this->bardEnergy;
    }

    /**
     * @param Int $bardEnergy
     */
    public function setBardEnergy(Int $bardEnergy){
        $this->bardEnergy = $bardEnergy;
    }
    
   /**
     * @return Int|null
     */
    public function getOpBardEnergy() : ?Int {
        return $this->opbardEnergy;
    }

    /**
     * @param Int $opbardEnergy
     */
    public function setOpBardEnergy(Int $opbardEnergy){
        $this->opbardEnergy = $opbardEnergy;
    }
    
    /**
     * @return Int|null
     */
    public function getOpArcherEnergy() : ?Int {
        return $this->oparcherEnergy;
    }

    /**
     * @param Int $oparcherEnergy
     */
    public function setOpArcherEnergy(Int $oparcherEnergy){
        $this->oparcherEnergy = $oparcherEnergy;
    }
    
    /**
     * @return Int|null
     */
    public function getArcherEnergy() : ?Int {
        return $this->archerEnergy;
    }

    /**
     * @param Int $archerEnergy
     */
    public function setArcherEnergy(Int $archerEnergy){
        $this->archerEnergy = $archerEnergy;
    }
    
    /**
     * @return int|null
     */
    public function getMageEnergy(): ?int
    {
        return $this->mageEnergy;
    }

    /**
     * @param int $mageEnergy
     */
    public function setMageEnergy(int $mageEnergy)
    {
        $this->mageEnergy = $mageEnergy;
    }
    
    /**
     * @param Int $itemId|null
     * @return Int
     */
    public function getBardEnergyCost(Int $itemId = null) : Int {
    	$energyCost = null;
    	switch($itemId){
    		case ItemIds::SUGAR:
    		$energyCost = 20;
    		break;
    		case ItemIds::IRON_INGOT:
    		$energyCost = 30;
    		break;
    		case ItemIds::BLAZE_POWDER:
    		$energyCost = 40;
    		break;
    		case ItemIds::GHAST_TEAR:
    		$energyCost = 35;
    		break;
    		case ItemIds::FEATHER:
    		$energyCost = 30;
    		break;
    		case ItemIds::DYE:
    		$energyCost = 30;
    		break;
    		case ItemIds::MAGMA_CREAM:
    		$energyCost = 25;
    		break;
    		case ItemIds::SPIDER_EYE:
    		$energyCost = 40;
    		break;
    	}
    	return $energyCost;
    }
    
    /**
     * @param Int $itemId|null
     * @return Int
     */
    public function getOpBardEnergyCost(Int $itemId = null) : Int {
    	$energyCost = null;
    	switch($itemId){
    		case ItemIds::SUGAR:
    		$energyCost = 15;
    		break;
    		case ItemIds::IRON_INGOT:
    		$energyCost = 20;
    		break;
    		case ItemIds::BLAZE_POWDER:
    		$energyCost = 30;
    		break;
    		case ItemIds::GHAST_TEAR:
    		$energyCost = 25;
    		break;
    		case ItemIds::FEATHER:
    		$energyCost = 20;
    		break;
    		case ItemIds::DYE:
    		$energyCost = 20;
    		break;
    		case ItemIds::MAGMA_CREAM:
    		$energyCost = 15;
    		break;
    		case ItemIds::SPIDER_EYE:
    		$energyCost = 30;
    		break;
    	}
    	return $energyCost;
    }
    
    /**
     * @param int|null $itemId
     * @return int|null
     */
    public function getMageEnergyCost(int $itemId = null): ?int
    {
        $energyCost = null;

        switch ($itemId) {
            case ItemIds::SEEDS:
            $energyCost = 35;
            break;
            case ItemIds::COAL:
            $energyCost = 25;
            break;
            case ItemIds::SPIDER_EYE:
            $energyCost = 40;
            break;
            case ItemIds::ROTTEN_FLESH:
            $energyCost = 40;
            break;
            case ItemIds::GOLD_NUGGET:
            $energyCost = 35;
            break;
            case ItemIds::DYE:
            $energyCost = 30;
            break;
        }
        return $energyCost;
    }

    /**
     * @return bool
     */
    public function isGodMode() : bool {
        return $this->godMode;
    }

    /**
     * @param bool $godMode
     */
    public function setGodMode(bool $godMode){
        $this->godMode = $godMode;
    }
    
    public function getEndPortalPlace() : Bool {
        return $this->portalPlace;
    }

    public function setEndPortalPlace(bool $value){
        return $this->portalPlace = $value;
    }

    /**
     * @return bool
     */
    public function isCombatTag() : bool {
        return $this->combatTag;
    }

    /**
     * @param bool $combatTag
     */
    public function setCombatTag(bool $combatTag){
        $this->combatTag = $combatTag;
    }
    
    /**
     * @param Int $combatTagTime
     */
    public function setCombatTagTime(Int $combatTagTime){
    	$this->combatTagTime = $combatTagTime;
    }
    
    /**
     * @return Int
     */
    public function getCombatTagTime() : Int {
    	return $this->combatTagTime;
    }
    
    /**
     * @return bool
     */
    public function isEnderPearl() : bool {
        return $this->enderPearl;
    }
    
    /**
     * @param bool $enderPearl
     */
    public function setEnderPearl(bool $enderPearl){
        $this->enderPearl = $enderPearl;
    }
    
    /**
     * @param Int $enderPearlTime
     */
    public function setEnderPearlTime(Int $enderPearlTime){
    	$this->enderPearlTime = $enderPearlTime;
    }
    
    /**
     * @return Int
     */
    public function getEnderPearlTime() : Int {
    	return $this->enderPearlTime;
    }
    
    /**
     * @return bool
     */
    public function isStormBreaker() : bool {
        return $this->stormBreaker;
    }
    
    /**
     * @param bool $stormBreaker
     */
    public function setStormBreaker(bool $stormBreaker){
        $this->stormBreaker = $stormBreaker;
    }
    
    /**
     * @param Int $stormBreakerTime
     */
    public function setStormBreakerTime(Int $stormBreakerTime){
    	$this->stormBreakerTime = $stormBreakerTime;
    }
    
    /**
     * @return Int
     */
    public function getStormBreakerTime() : Int {
    	return $this->stormBreakerTime;
    }
    
    /**
     * @return bool
     */
    public function isNinjaShear() : bool {
        return $this->ninjaShear;
    }
    
    /**
     * @param bool $ninjaShear
     */
    public function setNinjaShear(bool $ninjaShear){
        $this->ninjaShear = $ninjaShear;
    }
    
    /**
     * @param Int $ninjaShearTime
     */
    public function setNinjaShearTime(Int $ninjaShearTime){
    	$this->ninjaShearTime = $ninjaShearTime;
    }
    
    /**
     * @return Int
     */
    public function getNinjaShearTime() : Int {
    	return $this->ninjaShearTime;
    }
    
    /**
     * @param Vector3 $ninjaPosition
     */
    public function setNinjaShearPosition(?Vector3 $ninjaPosition){
        $this->ninjaPosition = $ninjaPosition;
    }

    /**
     * @return Vector3
     */
    public function getNinjaShearPosition() : ?Vector3 {
        return $this->ninjaPosition;
    }
    
    /**
     * @return bool
     */
    public function isCamuflaje() : bool {
        return $this->camuflaje;
    }
    
    /**
     * @param bool $camuflaje
     */
    public function setCamuflaje(bool $camuflaje){
        $this->camuflaje = $camuflaje;
    }
    
    /**
     * @param Int $camuflajeTime
     */
    public function setCamuflajeTime(Int $camuflajeTime){
    	$this->camuflajeTime = $camuflajeTime;
    }
    
    /**
     * @return Int
     */
    public function getCamuflajeTime() : Int {
    	return $this->camuflajeTime;
    }
    
    /**
     * @return bool
     */
    public function isRefill() : bool {
        return $this->refill;
    }
    
    /**
     * @param bool $refill
     */
    public function setRefill(bool $refill){
        $this->refill = $refill;
    }
    
    /**
     * @param Int $refillTime
     */
    public function setRefillTime(Int $refillTime){
    	$this->refillTime = $refillTime;
    }
    
    /**
     * @return Int
     */
    public function getRefillTime() : Int {
    	return $this->refillTime;
    }
    
    /**
     * @return bool
     */
    public function isAntiTrapperTarget() : bool {
        return $this->antiTrapperTarget;
    }

    /**
     * @param bool $antiTrapperTarget
     */
    public function setAntiTrapperTarget(bool $antiTrapperTarget){
        $this->antiTrapperTarget = $antiTrapperTarget;
    }

    /**
     * @return bool
     */
    public function isAntiTrapper() : bool {
        return $this->antiTrapper;
    }
    
    /**
     * @param bool $antiTrapper
     */
    public function setAntiTrapper(bool $antiTrapper){
        $this->antiTrapper = $antiTrapper;
    }
    
    /**
     * @param Int $antiTrapperTime
     */
    public function setAntiTrapperTime(Int $antiTrapperTime){
    	$this->antiTrapperTime = $antiTrapperTime;
    }
    
    /**
     * @return Int
     */
    public function getAntiTrapperTime() : Int {
    	return $this->antiTrapperTime;
    }
    
    /**
     * @return bool
     */
    public function isBackStap() : bool {
    	return $this->backstap;
    }
    
    /**
     * @param bool $backstap
     */
    public function setBackstap(bool $backstap){
    	$this->backstap = $backstap;
    }
    
    /**
     * @param Int $backstapTime
     */
    public function setBackstapTime(Int $backstapTime){
    	$this->backstapTime = $backstapTime;
    }
    
    /**
     * @return Int
     */
    public function getBackstapTime() : Int {
    	return $this->backstapTime;
    }
    
    /**
     * @return bool
     */
    public function isOpBackStap() : bool {
    	return $this->opbackstap;
    }
    
    /**
     * @param bool $opbackstap
     */
    public function setOpBackstap(bool $opbackstap){
    	$this->opbackstap = $opbackstap;
    }
    
    /**
     * @param Int $opbackstapTime
     */
    public function setOpBackstapTime(Int $opbackstapTime){
    	$this->opbackstapTime = $opbackstapTime;
    }
    
    /**
     * @return Int
     */
    public function getOpBackstapTime() : Int {
    	return $this->opbackstapTime;
    }
    
    /**
     * @return bool
     */
    public function isArcherTag() : bool {
    	if(isset(Loader::$mark[$this->getName()])){
    	    return true;
        }
    	return false;
    }
    
    /**
     * @param bool $archerTag
     */
    public function setArcherTag(bool $archerTag){
    	$this->archerTag = $archerTag;
    }
    
    /**
     * @param Int $archerTagTime
     */
    public function setArcherTagTime(Int $archerTagTime){
    	$this->archerTagTime = $archerTagTime;
    }
    
    /**
     * @return Int
     */
    public function getArcherTagTime() : Int {
    	return $this->archerTagTime;
    }
    
    /**
     * @return bool
     */
    public function isOpArcherTag() : bool {
    	if(isset(Loader::$mark[$this->getName()])){
    	    return true;
        }
    	return false;
    }
    
    /**
     * @param bool $oparcherTag
     */
    public function setOpArcherTag(bool $oparcherTag){
    	$this->oparcherTag = $oparcherTag;
    }
    
    /**
     * @param Int $archerTagTime
     */
    public function setOpArcherTagTime(Int $oparcherTagTime){
    	$this->oparcherTagTime = $oparcherTagTime;
    }
    
    /**
     * @return Int
     */
    public function getOpArcherTagTime() : Int {
    	return $this->oparcherTagTime;
    }

    /**
     * @return bool
     */
    public function isEgg() : bool {
        return $this->egg;
    }

    /**
     * @param bool $egg
     */
    public function setEgg(bool $egg){
        $this->egg = $egg;
    }

    /**
     * @param Int $eggTime
     */
    public function setEggTime(Int $eggTime){
        $this->eggTime = $eggTime;
    }

    /**
     * @return Int
     */
    public function getEggTime() : Int {
        return $this->eggTime;
    }

    /**
     * @return bool
     */
    public function isSpecialItem() : bool {
        return $this->specialItem;
    }

    /**
     * @param bool $specialItem
     */
    public function setSpecialItem(bool $specialItem){
        $this->specialItem = $specialItem;
    }

    /**
     * @param Int $specialItemTime
     */
    public function setSpecialItemTime(Int $specialItemTime){
        $this->specialItemTime = $specialItemTime;
    }

    /**
     * @return Int
     */
    public function getSpecialItemTime() : Int {
        return $this->specialItemTime;
    }

    /**
     * @return bool
     */
    public function isPotionCounter() : bool {
        return $this->potionCounter;
    }

    /**
     * @param bool $potionCounter
     */
    public function setPotionCounter(bool $potionCounter){
        $this->potionCounter = $potionCounter;
    }

    /**
     * @param Int $potionCounterTime
     */
    public function setPotionCounterTime(Int $potionCounterTime){
        $this->potionCounterTime = $potionCounterTime;
    }

    /**
     * @return Int
     */
    public function getPotionCounterTime() : Int {
        return $this->potionCounterTime;
    }
    
    /**
     * @return bool
     */
    public function isLoggerBait() : bool {
        return $this->loggerBait;
    }
    
    /**
     * @param bool $loggerBait
     */
    public function setLoggerBait(bool $loggerBait){
        $this->loggerBait = $loggerBait;
    }
    
    /**
     * @param Int $loggerBaitTime
     */
    public function setLoggerBaitTime(Int $loggerBait){
        $this->loggerBaitTime = $loggerBait;
    }
    
    /**
     * @return Int
     */
    public function getLoggerBaitTime() : Int {
        return $this->loggerBaitTime;
    }
    
    /**
     * @return bool
     */
    public function isResetItems() : bool {
        return $this->resetItems;
    }
    
    /**
     * @param bool $resetItems
     */
    public function setResetItems(bool $resetItems){
        $this->resetItems = $resetItems;
    }
    
    /**
     * @param Int $resetItemsTime
     */
    public function setResetItemsTime(Int $resetItems){
        $this->resetItemsTime = $resetItems;
    }
    
    /**
     * @return Int
     */
    public function getResetItemsTime() : Int {
        return $this->resetItemsTime;
    }
    
    /**
     * @return bool
     */
    public function isGoldenGapple() : bool {
    	return $this->goldenApple;
    }
    
    /**
     * @param bool $goldenApple
     */
    public function setGoldenApple(bool $goldenApple){
    	$this->goldenApple = $goldenApple;
    }
    
    /**
     * @param Int $goldenAppleTime
     */
    public function setGoldenAppleTime(Int $goldenAppleTime){
    	$this->goldenAppleTime = $goldenAppleTime;
    }
    
    /**
     * @return Int
     */
    public function getGoldenAppleTime() : Int {
    	return $this->goldenAppleTime;
    }

    /**
     * @return bool
     */
    public function isAutoFeed() : bool {
        return $this->autoFeed;
    }

    /**
     * @param bool $autoFeed
     */
    public function setAutoFeed(bool $autoFeed){
        $this->autoFeed = $autoFeed;
    }

    /**
     * @return bool
     */
    public function isTeleportingHome() : bool {
        return $this->teleportHome;
    }

    /**
     * @param bool $teleportHome
     */
    public function setTeleportingHome(bool $teleportHome){
        $this->teleportHome = $teleportHome;
    }

    /**
     * @param Int $teleportHomeTime
     */
    public function setTeleportingHomeTime(Int $teleportHomeTime){
        $this->teleportHomeTime = $teleportHomeTime;
    }

    /**
     * @return Int
     */
    public function getTeleportingHomeTime() : Int {
        return $this->teleportHomeTime;
    }
    
    /**
     * @return bool
     */
    public function isLogout() : bool {
        return $this->logout;
    }

    /**
     * @param bool $logout
     */
    public function setLogout(bool $logout){
        $this->logout = $logout;
    }

    /**
     * @param Int $logoutTime
     */
    public function setLogoutTime(Int $logoutTime){
        $this->logoutTime = $logoutTime;
    }

    /**
     * @return Int
     */
    public function getLogoutTime() : Int {
        return $this->logoutTime;
    }
    
    /**
     * @return bool
     */
    public function isTeleportingStuck() : bool {
        return $this->teleportStuck;
    }

    /**
     * @param bool $teleportStuck
     */
    public function setTeleportingStuck(bool $teleportStuck){
        $this->teleportStuck = $teleportStuck;
    }

    /**
     * @param Int $teleportStuckTime
     */
    public function setTeleportingStuckTime(Int $teleportStuckTime){
        $this->teleportStuckTime = $teleportStuckTime;
    }

    /**
     * @return Int
     */
    public function getTeleportingStuckTime() : Int {
        return $this->teleportStuckTime;
    }
    
    /**
     * @return bool
     */
    public function isTeleportingSpawn() : bool {
        return $this->teleportSpawn;
    }

    /**
     * @param bool $teleportSpawn
     */
    public function setTeleportingSpawn(bool $teleportSpawn){
        $this->teleportSpawn = $teleportSpawn;
    }

    /**
     * @param Int $teleportSpawnTime
     */
    public function setTeleportingSpawnTime(Int $teleportSpawnTime){
        $this->teleportSpawnTime = $teleportSpawnTime;
    }

    /**
     * @return Int
     */
    public function getTeleportingSpawnTime() : Int {
        return $this->teleportSpawnTime;
    }
    
    /**
     * @return bool
     */
    public function isInvincibility() : bool {
    	return $this->invincibility;
    }
    
    /**
     * @param bool $invincibility
     */
    public function setInvincibility(bool $invincibility){
    	$this->invincibility = $invincibility;
    }
    
    /**
     * @param Int $invincibilityTime
     */
    public function setInvincibilityTime(Int $invincibilityTime){
    	$this->invincibilityTime = $invincibilityTime;
    }
    
    /**
     * @return Int
     */
    public function getInvincibilityTime() : Int {
    	return $this->invincibilityTime;
    }

    /**
     * @return bool
     */
    public function isViewingMap() : bool {
    	return $this->viewingMap;
    }
    
    /**
     * @param bool $viewingMap
     */
    public function setViewingMap(bool $viewingMap){
    	$this->viewingMap = $viewingMap;
    }

    /**
     * @param mixed $movementTime
     */
    public function setMovementTime($movementTime){
        $this->movementTime = $movementTime;
    }

    /**
     * @return bool
     */
    public function isMovementTime() : bool {
        return (time() - $this->movementTime) < 0;
    }

    /**
     * @return bool
     */
    public function isInteract() : bool {
        return $this->canInteract;
    }

    /**
     * @param bool $canInteract
     */
    public function setInteract(bool $canInteract){
        $this->canInteract = $canInteract;
    }
    
    /**
     * @return void
     */
    public function addTool() : void {
    	$item = Item::get(ItemIds::STONE_HOE, 0, 1)->setCustomName(TE::LIGHT_PURPLE."Claim Hoe")->setLore([TE::GRAY."Touch First Position, Break Second Position!"]);
		$this->getInventory()->addItem($item);
    }
    
    /**
     * @return void
     */
    public function removeTool() : void {
    	$this->getInventory()->removeItem(Item::get(ItemIds::STONE_HOE, 0, 1));
    }

    /**
     * @return Int
     */
    public function getClaimCost() : Int {
        return $this->playerClaimCost;
    }

    /**
     * @param Int $playerClaimCost
     */
    public function setClaimCost(Int $playerClaimCost){
        $this->playerClaimCost = $playerClaimCost;
    }

    /** 
     * @param String $chat
     */
    public function setChat(String $chat){
        $this->chat = $chat;
    }

    /**
     * @return String
     */
    public function getChat() : ?String {
        return $this->chat === null ? "Public" : $this->chat;
    }

    /**
     * @return bool
     */
    public function isInvited() : bool {
        return $this->invitation;
    }

    /**
     * @param bool $invitation
     */
    public function setInvite(bool $invitation){
        $this->invitation = $invitation;
    }
    
    /**
     * @return String
     */
    public function getCurrentInvite() : String {
    	return $this->currentInvite;
    }
    
    /**
     * @param String $currentInvite
     */
    public function setCurrentInvite(String $currentInvite){
    	$this->currentInvite = $currentInvite;
    }

    /**
     * @return bool
     */
    public function isFocus() : bool {
        return $this->focus;
    }

    /**
     * @param bool $focus
     */
    public function setFocus(bool $focus){
        $this->focus = $focus;
    }

    /**
     * @param String $focusFaction
     */
    public function setFocusFaction(String $focusFaction){
        $this->focusFaction = $focusFaction;
    }

    /**
     * @return void
     */
    public function getFocusFaction() : String {
        return $this->focusFaction;
    }

    /**
     * @return String
     */
    public function getRegion() : String {
    	return $this->currentRegion === null ? "Unknown" : $this->currentRegion;
    }
    
    /**
     * @param String $currentRegion
     */
    public function setRegion(String $currentRegion){
    	$this->currentRegion = $currentRegion;
    }
    
    /**
     * @return String
     */
    public function getCurrentRegion() : String {
    	if(Factions::isSpawnRegion($this)){
    		return "Spawn";
    	}else{
    		return Factions::getRegionName($this) ?? "Wilderness";
    	}
    }
    
    /**
     * @param Handler $inventoryHandler
     */
    public function setInventoryHandler(Handler $inventoryHandler){
    	$this->inventoryHandler[$this->getName()] = $inventoryHandler;
    }
    
    /**
     * @return Handler
     */
    public function getInventoryHandler() : ?Handler {
    	return $this->inventoryHandler[$this->getName()] ?? null;
    }
    
    /**
     * @return void
     */
    public function unsetInventoryHandler() : void {
    	unset($this->inventoryHandler[$this->getName()]);
    }
    
    /**
     * @return bool
     */
    public function isInventoryHandler() : bool {
    	if(isset($this->inventoryHandler[$this->getName()])){
    		return true;
    	}else{
    		return false;
    	}
    	return false;
    }
    
    /**
     * @return Int
     */
    public function getLives() : Int {
        return PlayerBase::getData($this->getName())->get("lives") === null ? 0 : PlayerBase::getData($this->getName())->get("lives");
    }
    
    /**
     * @param Int $lives
     */
    public function setLives(Int $lives){
        PlayerBase::setData($this->getName(), "lives", $lives);
    }

    /**
     * @param Int $lives
     */
    public function reduceLives(Int $lives){
        PlayerBase::setData($this->getName(), "lives", $this->getLives() - $lives);
    }

    /**
     * @param Int $lives
     */
    public function addLives(Int $lives){
        PlayerBase::setData($this->getName(), "lives", $this->getLives() + $lives);
    }

    /**
     * @return Int
     */
    public function getBalance() : Int {
    	return PlayerBase::getData($this->getName())->get("balance") === null ? 0 : PlayerBase::getData($this->getName())->get("balance");
    }

    /**
     * @param Int $balance
     */
    public function setBalance(Int $balance){
    	PlayerBase::setData($this->getName(), "balance", $balance);
    }
    

    /**
     * @param Int $balance
     */
    public function reduceBalance(Int $balance){
        PlayerBase::setData($this->getName(), "balance", $this->getBalance() - $balance);
    }

    /**
     * @param Int $balance
     */
    public function addBalance(Int $balance){
        PlayerBase::setData($this->getName(), "balance", $this->getBalance() + $balance);
    }

    /**
     * @param Int $kills
     */
    public function setKills(Int $kills){
        PlayerBase::setData($this->getName(), "kills", $kills);
    }

    /**
     * @return Int
     */
    public function getKills() : Int {
        return PlayerBase::getData($this->getName())->get("kills") === null ? 0 : PlayerBase::getData($this->getName())->get("kills");
    }

    /**
     * @param Int $kills
     */
    public function reduceKills(Int $kills = 1){
        PlayerBase::setData($this->getName(), "kills", $this->getKills() - $kills);
    }
    
    /**
     * @param Int $kills
     */
    public function addKills(Int $kills = 1){
    	PlayerBase::setData($this->getName(), "kills", $this->getKills() + $kills);
    }
    
    /**
     * @param Int $deaths
     */
    public function setDeaths(Int $deaths){
        PlayerBase::setData($this->getName(), "deaths", $deaths);
    }

    /**
     * @return Int
     */
    public function getDeaths() : Int {
        return PlayerBase::getData($this->getName())->get("deaths") === null ? 0 : PlayerBase::getData($this->getName())->get("deaths");
    }

    /**
     * @param Int $deaths
     */
    public function reduceDeaths(Int $deaths = 1){
        PlayerBase::setData($this->getName(), "deaths", $this->getDeaths() - $deaths);
    }
    
    /**
     * @param Int $deaths
     */
    public function addDeaths(Int $deaths = 1){
    	PlayerBase::setData($this->getName(), "deaths", $this->getDeaths() + $deaths);
    }

    /**
     * @param String $kitName
     */
    public function getKitTime(String $kitName){
        return YamlProvider::getKitTime($this->getName(), $kitName);
    }

    /**
     * @param String $kitName
     */
    public function resetKitTime(String $kitName){
        YamlProvider::reset($this->getName(), $kitName, time() + (4 * 3600));
    }
    
    /**
     * @return Int
     */
    public function getTimeBrewerRemaining() : Int {
        return PlayerBase::getData($this->getName())->get("brewer");
    }

    /**
     * @return void
     */
    public function resetBrewerTime() : void {
        PlayerBase::setData($this->getName(), "brewer", time() + (5 * 3600));
    }

    /**
     * @return Int
     */
    public function getTimeReclaimRemaining() : Int {
        return PlayerBase::getData($this->getName())->get("reclaim");
    }

    /**
     * @return void
     */
    public function resetReclaimTime() : void {
        PlayerBase::setData($this->getName(), "reclaim", time() + (1 * 86400));
    }

    /**
     * @return Int
     */
    public function getKothHostTimeRemaining() : Int {
        return PlayerBase::getData($this->getName())->get("koth_host");
    }

    /**
     * @return void
     */
    public function resetKothHostTime() : void {
        PlayerBase::setData($this->getName(), "koth_host", $this->getRank() === "Nebula" ? time() + (2 * 3600) : time() + (3 * 3600));
    }
    
    /**
     * @return Int
     */
    public function getCitadelHostTimeRemaining() : Int {
        return PlayerBase::getData($this->getName())->get("citadel_host");
    }

    /**
     * @return void
     */
    public function resetCitadelHostTime() : void {
        PlayerBase::setData($this->getName(), "citadel_host", $this->getRank() === "Knigth" ? time() + (2 * 3600) : time() + (3 * 3600));
    }
    
    /**
     * @return bool
     */
     public function isBardClass() : bool {
    	if(!$this->isOnline()) return false;
		if($this->getArmorInventory()->getHelmet()->getId() === ItemIds::GOLD_HELMET && $this->getArmorInventory()->getChestplate()->getId() === ItemIds::GOLD_CHESTPLATE && $this->getArmorInventory()->getLeggings()->getId() === ItemIds::GOLD_LEGGINGS && $this->getArmorInventory()->getBoots()->getId() === ItemIds::GOLD_BOOTS){
			return true;
		}else{
			return false;
		}
		return false;
    }
    
    /**
     * @return bool
     */
     public function isOpBardClass() : bool {
    	if(!$this->isOnline()) return false;
		if($this->getArmorInventory()->getHelmet()->getId() === ItemIds::GOLD_HELMET && $this->getArmorInventory()->getChestplate()->getId() === ItemIds::GOLD_CHESTPLATE && $this->getArmorInventory()->getLeggings()->getId() === ItemIds::GOLD_LEGGINGS && $this->getArmorInventory()->getBoots()->getId() === ItemIds::CHAINMAIL_BOOTS){
			return true;
		}else{
			return false;
		}
		return false;
    }
    
    /**
     * @return bool
     */
    public function isMageClass(): bool
    {
        if (!$this->isOnline())
            return false;

        if ($this->getArmorInventory()->getHelmet()->getId() === ItemIds::GOLD_HELMET && $this->getArmorInventory()->getChestplate()->getId() === ItemIds::CHAINMAIL_CHESTPLATE && $this->getArmorInventory()->getLeggings()->getId() === ItemIds::CHAINMAIL_LEGGINGS && $this->getArmorInventory()->getBoots()->getId() === ItemIds::GOLD_BOOTS) {
            return true;
        }
        return false;
    }
    
    /**
     * @return bool
     */
    public function isArcherClass() : bool {
    	if(!$this->isOnline()) return false;
		if($this->getArmorInventory()->getHelmet()->getId() === ItemIds::LEATHER_HELMET && $this->getArmorInventory()->getChestplate()->getId() === ItemIds::LEATHER_CHESTPLATE && $this->getArmorInventory()->getLeggings()->getId() === ItemIds::LEATHER_LEGGINGS && $this->getArmorInventory()->getBoots()->getId() === ItemIds::LEATHER_BOOTS){
			return true;
		}else{
			return false;
		}
		return false;
    }
    
    /**
     * @return bool
     */
    public function isOpArcherClass() : bool {
    	if(!$this->isOnline()) return false;
		if($this->getArmorInventory()->getHelmet()->getId() === ItemIds::LEATHER_HELMET && $this->getArmorInventory()->getChestplate()->getId() === ItemIds::LEATHER_CHESTPLATE && $this->getArmorInventory()->getLeggings()->getId() === ItemIds::LEATHER_LEGGINGS && $this->getArmorInventory()->getBoots()->getId() === ItemIds::CHAINMAIL_BOOTS){
			return true;
		}else{
			return false;
		}
		return false;
    }
    
    /**
     * @return bool
     */
    public function isMinerClass() : bool {
    	if(!$this->isOnline()) return false;
		if($this->getArmorInventory()->getHelmet()->getId() === ItemIds::IRON_HELMET && $this->getArmorInventory()->getChestplate()->getId() === ItemIds::IRON_CHESTPLATE && $this->getArmorInventory()->getLeggings()->getId() === ItemIds::IRON_LEGGINGS && $this->getArmorInventory()->getBoots()->getId() === ItemIds::IRON_BOOTS){
			return true;
		}else{
			return false;
		}
		return false;
    }

    /**
     * @return bool
     */
    public function isRogueClass() : bool {
        if(!$this->isOnline()) return false;
		if($this->getArmorInventory()->getHelmet()->getId() === ItemIds::CHAINMAIL_HELMET && $this->getArmorInventory()->getChestplate()->getId() === ItemIds::CHAINMAIL_CHESTPLATE && $this->getArmorInventory()->getLeggings()->getId() === ItemIds::CHAINMAIL_LEGGINGS && $this->getArmorInventory()->getBoots()->getId() === ItemIds::CHAINMAIL_BOOTS){
			return true;
		}else{
			return false;
		}
		return false;
    }
    
    /**
     * @return bool
     */
    public function isOpRogueClass() : bool {
        if(!$this->isOnline()) return false;
		if($this->getArmorInventory()->getHelmet()->getId() === ItemIds::CHAINMAIL_HELMET && $this->getArmorInventory()->getChestplate()->getId() === ItemIds::CHAINMAIL_CHESTPLATE && $this->getArmorInventory()->getLeggings()->getId() === ItemIds::CHAINMAIL_LEGGINGS && $this->getArmorInventory()->getBoots()->getId() === ItemIds::LEATHER_BOOTS){
			return true;
		}else{
			return false;
		}
		return false;
    }
    // IMPLEMENTIONS DEEPZH OPCLASS:
    
    /**
     * @return void
     */
    public function checkClass() : void {
        if($this->isBardClass()){
            if(!isset($this->armorEffects[$this->getName()]["Bard"])){
                $this->armorEffects[$this->getName()]["Bard"] = $this;
                $this->sendMessage(" \n§dActivate Class §l§7--> §aBard\n ");
            }
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 240, 1));
        }elseif($this->isArcherClass()){
        	if(!isset($this->armorEffects[$this->getName()]["Archer"])){
                $this->armorEffects[$this->getName()]["Archer"] = $this;
                $this->sendMessage(" \n§dActivate Class §l§7--> §aArcher\n ");
            }
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 240, 2));
        }elseif($this->isRogueClass()){
            if(!isset($this->armorEffects[$this->getName()]["Rogue"])){
                $this->armorEffects[$this->getName()]["Rogue"] = $this;
                $this->sendMessage(" \n§dActivate Class §l§7--> §aRogue\n ");
            }
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 240, 2));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 240, 0));
            // OP ROGUE:
        if(!isset($this->armorEffects[$this->getName()]["OpRogue"])){
                $this->armorEffects[$this->getName()]["OpRogue"] = $this;
                $this->sendMessage(" \n§dActivate Class §l§7--> §aOpRogue\n ");
            }
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 240, 0));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 240, 2));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 240, 0));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 240, 0));
        }elseif($this->isOpBardClass()) {
          // DEEPZH:
          if(!isset($this->playerClass[$this->getName()]["OpBard"])){
              $this->playerClass[$this->getName()]["OpBard"] = $this;
              $this->sendMessage(" \n§dActivate Class §l§7--> §aOpBard\n ");
          }
          $this->addEffect(new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 240, 0));
          $this->addEffect(new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 240, 1));
          $this->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 240, 2));
          $this->addEffect(new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 240, 2));
        }elseif($this->isMageClass()) {
          // TODO:
          if(!isset($this->playerClass[$this->getName()]["Mage"])){
              $this->playerClass[$this->getName()]["Mage"] = $this;
              $this->sendMessage(" \n§dActivate Class §l§7--> §aMage\n ");
          }
          $this->addEffect(new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 240, 1));
          $this->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 240, 1));
          $this->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 240, 2));
        }elseif($this->isMinerClass()){
        	if(!isset($this->armorEffects[$this->getName()]["Miner"])){
                $this->armorEffects[$this->getName()]["Miner"] = $this;
                $this->sendMessage(" \n§dActivate Class §l§7--> §aMiner\n ");
            }
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::NIGHT_VISION), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::HASTE), 240, 2));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::FIRE_RESISTANCE), 240, 1));
            $this->addEffect(new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 240, 1));
            if($this->getY() < 40){
            }
        }else{
        	if(isset($this->armorEffects[$this->getName()]["Bard"])){
        	    $this->sendMessage(" \n§dDeactivated Class §l§7--> §aBard\n ");
        		$this->removeEffect(Effect::SPEED);
                $this->removeEffect(Effect::REGENERATION);
        		unset($this->armorEffects[$this->getName()]["Bard"]);
        	}
        	if(isset($this->armorEffects[$this->getName()]["Archer"])){
        	    $this->sendMessage(" \n§dDeactivated Class §l§7--> §aArcher\n ");
        		$this->removeEffect(Effect::SPEED);
                $this->removeEffect(Effect::REGENERATION);
                $this->removeEffect(Effect::FIRE_RESISTANCE);
        		unset($this->armorEffects[$this->getName()]["Archer"]);
            }
            if(isset($this->armorEffects[$this->getName()]["OpBard"])){
            	$this->sendMessage(" \n§dDeactivated Class §l§7--> §aOpBard\n ");
        		$this->removeEffect(Effect::SPEED);
                $this->removeEffect(Effect::REGENERATION);
                $this->removeEffect(Effect::FIRE_RESISTANCE);
                $this->removeEffect(Effect::RESISTANCE);
        		unset($this->armorEffects[$this->getName()]["OpBard"]);
            }
            if(isset($this->armorEffects[$this->getName()]["Rogue"])){
            	$this->sendMessage(" \n§dDeactivated Class §l§7--> §aRogue\n ");
        		$this->removeEffect(Effect::SPEED);
                $this->removeEffect(Effect::REGENERATION);
                $this->removeEffect(Effect::FIRE_RESISTANCE);
                $this->removeEffect(Effect::JUMP_BOOST);
        		unset($this->armorEffects[$this->getName()]["Rogue"]);
        	}
            if(isset($this->armorEffects[$this->getName()]["OpRogue"])){
            	$this->sendMessage(" \n§dDeactivated Class §l§7--> §aOpRogue\n ");
        		$this->removeEffect(Effect::SPEED);
                $this->removeEffect(Effect::REGENERATION);
                $this->removeEffect(Effect::RESISTANCE);
                $this->removeEffect(Effect::JUMP_BOOST);
                $this->removeEffect(Effect::FIRE_RESISTANCE);
                $this->removeEffect(Effect::INVISIBILITY);
        		unset($this->armorEffects[$this->getName()]["OpRogue"]);
        	}
        	if(isset($this->armorEffects[$this->getName()]["Miner"])){
        	    $this->sendMessage(" \n§dDeactivated Class §l§7--> §aMiner\n ");
                $this->removeEffect(Effect::HASTE);
                $this->removeEffect(Effect::NIGHT_VISION);
                $this->removeEffect(Effect::FIRE_RESISTANCE);
                $this->removeEffect(Effect::INVISIBILITY);
                unset($this->armorEffects[$this->getName()]["Miner"]);
            }
        }
    }

    /**
	 * @return void
	 */
	public function changeWorld() : void {
		Loader::getInstance()->getServer()->loadLevel(Loader::getDefaultConfig("Worlds")["End"]);
		$data = Loader::getDefaultConfig("Worlds");
		if($this->getLevel()->getFolderName() === Loader::getInstance()->getServer()->getDefaultLevel()->getName()){
			Loader::getInstance()->getServer()->loadLevel(Loader::getDefaultConfig("Worlds")["End"]);
			$this->teleport(Loader::getInstance()->getServer()->getLevelByName(Loader::getDefaultConfig("Worlds")["End"])->getSafeSpawn());
		}else
        	if($this->getLevel()->getFolderName() === $data["End"]){
                $args = explode(":", Loader::getDefaultConfig("End_Exit"));
                $this->teleport(new Position($args[0], $args[1], $args[2], Loader::getInstance()->getServer()->getDefaultLevel()));
            }
    }

    /**
     * @param Item $item
     * @param bool $conditional
     */
    public function dropItem(Item $item, bool $conditional = false) : bool {
        if(!$conditional){
            parent::dropItem($item);
        }else{
            if(!$this->spawned||!$this->isAlive()){
                return false;
            }
            if($item->isNull()){
                $this->server->getLogger()->debug($this->getName()." attempted to drop a null item (".$item.")");
                return true;
            }
            $this->level->dropItem($this->add(0, 1.0, 0), $item);
            return true;
        }
        return true;
    }
    
    /**
     * @return void
     */
    public function addPermissionsPlayer() : void {
        $permission = Loader::getInstance()->getPermission($this);
		if($this->getRank() === "Member"){
            $permission->setPermission("free.kit.use", true);
            $permission->setPermission("member.reclaim", true);
		}
        if($this->getRank() === "Member_COL"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
        }
        if($this->getRank() === "Member_MEX"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
        }
        if($this->getRank() === "Member_ARG"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
        }
        if($this->getRank() === "Member_ESP"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
        }
		if($this->getRank() === "Owner"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
        }
        if($this->getRank() === "Owner_666"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
        }
        if($this->getRank() === "Co-Owner"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
        }
        if($this->getRank() === "PlataformAdmin"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
        }
        if($this->getRank() === "PlataformAdmin_COL"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
        }
        if($this->getRank() === "PlataformAdmin_MEX"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
        }
        if($this->getRank() === "PlataformAdmin_ESP"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
        }
        if($this->getRank() === "PlataformAdmin_ARG"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
        }
		if($this->getRank() === "Developer"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "SrAdmin"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "SrAdmin_COL"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "SrAdmin_MEX"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "SrAdmin_ESP"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "SrAdmin_ARG"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "Admin"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Admin_COL"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Admin_MEX"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Admin_ESP"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Admin_ARG"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "JrAdmin"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "JrAdmin_COL"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "JrAdmin_MEX"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "JrAdmin_ESP"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "JrAdmin_ARG"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "SrMod"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "SrMod_COL"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "SrMod_MEX"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "SrMod_ESP"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "SrMod_ARG"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Mod+"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Mod+_COL"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Mod+_MEX"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Mod+_ESP"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Mod+_ARG"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Mod"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Mod_COL"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Mod_MEX"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Mod_ESP"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Mod_ARG"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "Trial-Mod"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Trial-Mod_COL"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Trial-Mod_MEX"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Trial-Mod_ESP"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Trial-Mod_ARG"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "Partner"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Partner_COL"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Partner_MEX"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Partner_ESP"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Partner_ARG"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "Famous"){
            $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Famous_COL"){
            $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Famous_MEX"){
            $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Famous_ESP"){
            $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Famous_ARG"){
            $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Refys+"){
            $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Refys+_COL"){
            $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Refys+_MEX"){
            $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Refys+_ESP"){
            $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Refys+_ARG"){
            $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "Refys"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Refys_COL"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Refys_MEX"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Refys_ESP"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Refys_ARG"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "Poison"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Poison_COL"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Poison_MEX"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Poison_ESP"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Poison_ARG"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "Forest"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Forest_COL"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Forest_MEX"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Forest_ESP"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Forest_ARG"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
        if($this->getRank() === "MiniYT"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "MiniYT_COL"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "MiniYT_MEX"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "MiniYT_ESP"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "MiniYT_ARG"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "YouTuber"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
             }
        }
        if($this->getRank() === "YouTuber_COL"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
             }
        }
        if($this->getRank() === "YouTuber_MEX"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
             }
        }
        if($this->getRank() === "YouTuber_ESP"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
             }
        }
        if($this->getRank() === "YouTuber_ARG"){
			$file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
             }
        }
        if($this->getRank() === "Booster"){
		    $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Booster_COL"){
		    $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Booster_MEX"){
		    $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Booster_ESP"){
		    $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "Booster_ARG"){
		    $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "BoosterCloned"){
		    $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "BoosterCloned_COL"){
		    $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "BoosterCloned_MEX"){
		    $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "BoosterCloned_ESP"){
		    $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
		if($this->getRank() === "BoosterCloned_ARG"){
		    $file = Loader::getConfiguration("permissions");
            foreach($file->get($this->getRank()) as $permissions){
                $permission->setPermission($permissions, true);
            }
		}
    }

    /**
     * @return void
     */
    public function removePermissionsPlayer() : void {
        unset(Loader::getInstance()->permission[$this->getName()]);
    }

    /**
     * @return void
     */
    public function showCoordinates() : void {
        $pk = new GameRulesChangedPacket();
        $pk->gameRules = ["showcoordinates" => [1, "true", true]];
        $this->dataPacket($pk);
    }
}

?>
