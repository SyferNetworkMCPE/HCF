<?php

namespace SyferHCF\entities;

use SyferHCF\{Loader, Factions};
use SyferHCF\player\Player;

use SyferHCF\API\projectile\Throwable;

use pocketmine\utils\TextFormat as TE;
use pocketmine\math\Vector3;
use pocketmine\entity\Entity;
use pocketmine\nbt\tag\CompoundTag;

use pocketmine\math\RayTraceResult;

use pocketmine\level\{Position, Level};
use pocketmine\item\{Item, ItemIds};

use pocketmine\block\{Block, BlockIds, FenceGate, Slab};

use pocketmine\event\entity\EntityDamageEvent;

use pocketmine\level\sound\EndermanTeleportSound;

class EnderPearl extends Throwable {

	const NETWORK_ID = self::ENDER_PEARL;

	/** @var float */
    public $width = 0.5, $length = 0.5, $height = 0.5;

	/** @var float */
    protected $gravity = 0.03, $drag = 0.01;
	
	/** @var Player */
    public $player;

    /** @var Vector3 */
    public $position;
	
	/** @var bool */
    public $conditional = false;
    
    /**
     * ThrownEnderPearl Constructor.
     * @param Level $level
     * @param CompoundTag $nbt
     * @param Entity $entityName
     */
    public function __construct(Level $level, CompoundTag $nbt, ?Entity $entityName = null){
        parent::__construct($level, $nbt, $entityName);
        $this->setPositionPlayer(new Vector3(0, 0, 0, $this->getLevel()));
        $this->player = $entityName;
    }

    /**
     * @return void
     */
    public function teleportPlayer() : void {
        if($this->player === null){
            $this->kill();
            return;
        }
        if(!$this->player->isOnline()){
			$this->kill();
			return;
        }
        if($this->isFence()){
			$this->kill();
			$this->player->setEnderPearl(false);
			$this->player->sendTip(TE::YELLOW."Your EnderPearl was returned, to avoid glitching");
			return;
		}
		if($this->player instanceof Player && $this->player->isCombatTag()){
			if(Factions::isSpawnRegion($this)){
				$this->kill();
				$this->player->setEnderPearl(false);
				$this->player->sendTip(TE::YELLOW."Your EnderPearl was returned, to avoid glitching");
				return;
			}
		}
        if(!$this->conditional){
			$this->conditional = true;
			if($this->y > 0){
				$this->player->teleport($this->getPositionPlayer());
				$this->player->attack(new EntityDamageEvent($this->player, EntityDamageEvent::CAUSE_FALL, 2));
                if($this->isPearling()){
					$direction = $this->player->getDirectionVector()->multiply(3);
                    $this->player->teleport(Position::fromObject($this->player->add($direction->x, (int)$direction->y + 1, $direction->z), $this->player->getLevel()));
                    $this->player->attack(new EntityDamageEvent($this->player, EntityDamageEvent::CAUSE_FALL, 2));
                }
			}
			$this->kill();
		}
    }

    /**
     * @param Vector3 $position
     */
    public function setPositionPlayer(Vector3 $position){
        $this->position = $position;
    }

    /**
     * @return Vector3|null
     */
    public function getPositionPlayer() : ?Vector3 {
        return $this->position;
    }

    /**
     * @return void
     */
    public function teleportToPosition() : void {
        $x = $this->x;
        $y = (int)$this->y;
        $z = $this->z;
        $new = $this->getPosition();
        if($this->getPositionPlayer() === null) return;
        if($new->distanceSquared($this->getPositionPlayer()) > 1){
            $this->setPositionPlayer(new Vector3($x, $y, $z, $this->getLevel()));
        }
	}

    /**
	 * @return bool
	 */
	public function isFence() : bool {
		for($x = ((int)$this->x); $x <= ((int)$this->x); $x++){
			for($z = ((int)$this->z); $z <= ((int)$this->z); $z++){
				$block = $this->getLevel()->getBlockAt($x, $this->y, $z);
				if($block instanceof FenceGate||$block instanceof Fence){
					return true;
				}else{
					return false;
				}
			}
		}
		return false;
    }
    
    /**
	 * @return bool
	 */
	public function isPearling() : bool {
		for($x = ((int)$this->x + 1); $x <= ((int)$this->x - 1); $x++){
			for($z = ((int)$this->z + 1); $z <= ((int)$this->z - 1); $z++){
				$block = $this->getLevel()->getBlockAt($x, $this->y, $z);
				if($block instanceof Slab||$block instanceof Stair){
					return true;
				}else{
					return false;
				}
			}
		}
		return false;
	}

    /** 
	 * @param Int $currentTick
	 * @return bool
	 */
	public function onUpdate(Int $currentTick) : bool {
		if($this->closed){
			return false;
		}
		/** @var Vector3 */
		$this->teleportToPosition();
		
		$this->timings->startTiming();
		$hasUpdate = parent::onUpdate($currentTick);
		
		if($this->isCollided){
			$hasUpdate = true;
			$this->teleportPlayer();
		}
		$this->timings->stopTiming();
		return $hasUpdate;
    }
}

?>
