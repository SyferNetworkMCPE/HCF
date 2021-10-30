<?php
declare(strict_types=1);
namespace SyferHCF\level\block;

use SyferHCF\Loader;
use SyferHCF\Task\DelayedCrossDimensionTeleportTask;
use pocketmine\network\mcpe\protocol\types\DimensionIds;
use pocketmine\{block\Block, entity\Entity, block\Solid, item\Item, level\Level, Player};

class EndPortal extends Solid {
	
    /** @var int $id */
    protected $id = self::END_PORTAL;

    /**
     * @param int $meta
     */
    public function __construct(int $meta = 0){
        return $this->meta = $meta;
    }

    /**
     * @return int
     */
    public function getLightLevel(): int {
        return 1;
    }

    /**
     * @return string
     */
    public function getName(): string {
        return "End Portal";
    }

    /**
     * @return float
     */
    public function getHardness(): float {
        return -1;
    }

    /**
     * @return float
     */
    public function getBlastResistance(): float {
        return 18000000;
    }

    /**
     * @param Item $item
     * @return bool
     */
    public function isBreakable(Item $item): bool {
        return false;
    }

    /**
     * @return bool
     */
    public function canPassThrough(): bool {
        return true;
    }

    /**
     * @return bool
     */
    public function hasEntityCollision(): bool {
        return true;
    }

    /**
     * @param Item $item
     *
     */
    public function onEntityCollide(Entity $entity): void {
        if(!isset(Loader::$onPortal[$entity->getId()])){
            Loader::$onPortal[$entity->getId()] = true;
            if($entity instanceof Player){
                if($entity->getLevel() instanceof Level){
                    if($entity->getSpawntagTime() < 2){
                        if($entity->getLevel()->getName() != Loader::$endName){
                            Loader::getInstance()->getScheduler()->scheduleDelayedTask(new DelayedCrossDimensionTeleportTask($entity, DimensionIds::THE_END, Loader::$endLevel->getSafeSpawn()), 1);
                        } else {
                            Loader::getInstance()->getScheduler()->scheduleDelayedTask(new DelayedCrossDimensionTeleportTask($entity, DimensionIds::OVERWORLD, Loader::$overworldLevel->getSafeSpawn()), 20);
                        }
                    }
                }
            }
        }
        return;
    }
}
