<?php

namespace SyferHCF\level;

use SyferHCF\Loader;
use SyferHCF\level\block\EndPortalFrame;
use SyferHCF\level\block\EndPortal;
use SyferHCF\level\block\MonsterSpawnerBlock;
use SyferHCF\level\block\Obsidian;
use SyferHCF\level\block\Portal;

use SyferHCF\level\task\GlowstoneResetTask;

use SyferHCF\level\tile\MobSpawner;

use pocketmine\block\Block;
use pocketmine\block\BlockFactory;
use pocketmine\block\Chest;
use pocketmine\block\Fence;
use pocketmine\block\FenceGate;
use pocketmine\block\netherBrickFence;
use pocketmine\block\Tripwire;
use pocketmine\block\WoodenFence;

use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Living;

use pocketmine\item\Item;
use pocketmine\item\Potion;

use pocketmine\level\generator\GeneratorManager;
use pocketmine\level\Position;
use pocketmine\level\sound\DoorSound;

use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;

use pocketmine\network\mcpe\protocol\AddActorPacket;
use pocketmine\network\mcpe\protocol\ActorEventPacket;

use pocketmine\Player;

use pocketmine\tile\Tile;

use pocketmine\utils\TextFormat;
use ReflectionClass;
use ReflectionException;

class LevelManager {

    const STACK_TAG = "Stack";

    const STACK_SIZE = "{SIZE}";

    const STACK_NAME = "{NAME}";

    /** @var SyferHCF */
    private $core;

    /** @var GlowstoneMountain */
    private $glowstoneMountain;

    /** @var string */
    private static $nametag;
    
    /**
     * Worlds constructor.
     *
     * @param SyferHCF $core
     *
     * @throws ReflectionException
     */
    public function __construct(Loader $core) {
        $this->core = $core;
        self::$nametag = TextFormat::RESET . TextFormat::BOLD . TextFormat::GOLD . self::STACK_NAME . TextFormat::RESET . TextFormat::DARK_GRAY . " [" . TextFormat::YELLOW . "x" . self::STACK_SIZE . TextFormat::DARK_GRAY . "]";
    }
    
    /**
     * @throws ReflectionException
     */
    public function init(): void {
        $data = Loader::getDefaultConfig("Worlds");
    	$this->glowstoneMountain = new  GlowstoneMountain(new Position(-333, 85, 361, Loader::getInstance()->getServer()->getLevelByName($data["nether"])), new Position(-336, 78, 364, Loader::getInstance()->getServer()->getLevelByName($data["nether"])));
    	Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new GlowstoneResetTask($this), (20 * 60) * 15);
        Tile::registerTile(MobSpawner::class);
        
        BlockFactory::registerBlock(new EndPortalFrame(), true);
        BlockFactory::registerBlock(new Obsidian(), true);
        BlockFactory::registerBlock(new EndPortal(), true);
        BlockFactory::registerBlock(new MonsterSpawnerBlock(), true);
        BlockFactory::registerBlock(new Portal(), true);
        }

    /**
     * @param Entity $entity
     *
     * @return bool
     */
     
    public static function canStack(Entity $entity): bool {
        return $entity instanceof Living and (!$entity instanceof Human);
    }

    /**
     * @param Living $entity
     */
    public static function addToStack(Living $entity) {
        $bb = $entity->getBoundingBox()->expandedCopy(12, 12, 12);
        foreach($entity->getLevel()->getNearbyEntities($bb) as $e) {
            if($e->namedtag->hasTag(self::STACK_TAG) and $e instanceof Living and $e->getName() === $entity->getName()) {
                $entity->flagForDespawn();
                self::increaseStackSize($e);
                return;
            }
        }
        self::setStackSize($entity);
    }

    /**
     * @param Living $entity
     * @param int $size
     *
     * @return bool
     */
    public static function setStackSize(Living $entity, int $size = 1): bool {
        $entity->namedtag->setInt(self::STACK_TAG, $size);
        if($size < 1) {
            $entity->flagForDespawn();
            return false;
        }
        self::updateEntityName($entity);
        return true;
    }

    /**
     * @param Living $entity
     * @param int $size
     */
    public static function increaseStackSize(Living $entity, int $size = 1) {
        if($entity->namedtag !== null) {
            self::setStackSize($entity, $entity->namedtag->getInt(self::STACK_TAG, 0) + $size);
        }
    }

    /**
     * @param Living $entity
     * @param int $size
     */
    public static function decreaseStackSize(Living $entity, int $size = 1) {
        if($size > 0) {
            $currentSize = $entity->namedtag->getInt(self::STACK_TAG);
            $decr = min($size, $currentSize);
            $newSize = $currentSize - $decr;
            $level = $entity->getLevel();
            if(self::setStackSize($entity, $newSize)) {
                $entity->setHealth($entity->getMaxHealth());
            }
            for($i = 0; $i < $decr; ++$i) {
                foreach($entity->getDrops() as $item) {
                    $level->dropItem($entity, $item);
                }
            }
        }
    }

    /**
     * @param Living $entity
     */
    public static function updateEntityName(Living $entity): void {
        $entity->setNameTag(
            strtr(
                self::$nametag, [
                self::STACK_SIZE => $entity->namedtag->getInt(self::STACK_TAG),
                self::STACK_NAME => $entity->getName()
            ])
        );
    }

    /**
     * @return GlowstoneMountain
     */
    public function getGlowstoneMountain(): GlowstoneMountain {
        return $this->glowstoneMountain;
    }
}
