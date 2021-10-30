<?php

namespace SyferHCF\level;

use SyferHCF\Loader;
use SyferHCF\player\Player;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Living;
use pocketmine\entity\object\ItemEntity;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDespawnEvent;
use pocketmine\event\entity\EntitySpawnEvent;
use pocketmine\event\Listener;
use pocketmine\level\Position;
use pocketmine\scheduler\Task;
use pocketmine\tile\Tile;
use pocketmine\utils\TextFormat;
use ReflectionException;

class LevelListener implements Listener {

    /** @var SyferHCF */
    protected $plugin;

    /** @var Entity[] */
    private $entities = [];

    /** @var string[] */
    private $ids = [];

    /**
     * LevelListener constructor.
     *
     * @param SyferHCF $plugin
     */
    public function Level__construct(Loader $plugin) {
        $this->plugin = $plugin;
    }
   
    /**
     * @priority HIGHEST
     *
     * @param BlockBreakEvent $event
     *
     * @throws ReflectionException
     */
    public function onBlockBreak(BlockBreakEvent $event): void {
        $block = $event->getBlock();
        $player = $event->getPlayer();
        if($block->getId() === Block::STONE) {
            if(mt_rand(1, 5000) === mt_rand(1, 5000)) {
                Loader::getInstance()->getScheduler()->scheduleDelayedTask(new class($player, $block) extends Task {

                    /** @var Player */
                    private $player;

                    /** @var Position */
                    private $position;

                    /**
                     *  constructor.
                     *
                     * @param Player $player
                     * @param Position $position
                     */
                    public function __construct(Player $player, Position $position) {
                        $this->player = $player;
                        $this->position = $position;
                    }

                    /**
                     * @param int $currentTick
                     */
                    public function onRun(int $currentTick) {
                        $types = [
                            Entity::CAVE_SPIDER,
                            Entity::RABBIT,
                            Entity::SKELETON,
                            Entity::SLIME,
                            Entity::COW,
                            Entity::ZOMBIE
                        ];
                        $type = $types[array_rand($types)];
                        $this->position->getLevel()->setBlock($this->position, Block::get(Block::MOB_SPAWNER), true, true);
                        $tile = $this->position->getLevel()->getTile($this->position);
                        if(!$tile instanceof MobSpawner) {
                            $nbt = MobSpawner::createNBT($this->position);
                            $nbt->setString(Tile::TAG_ID, Tile::MOB_SPAWNER);
                            /** @var MobSpawner $spawnerTile */
                            $tile = Tile::createTile("MobSpawner", $this->position->getLevel(), $nbt);
                        }
                        $tile->setSpawnEntityType($type);
                        $tile->spawnToAll();
                        $name = $tile->getEntityType();
                        $this->player->addTitle(TextFormat::GREEN . "Spawner found!", TextFormat::GRAY . $name . " Spawner");
                    }
                }, 1);
            }
        }
    }

    /**
     * @priority HIGHEST
     *
     * @param EntitySpawnEvent $event
     */
    public function onEntitySpawn(EntitySpawnEvent $event): void {
        $entity = $event->getEntity();
        if($entity instanceof Human) {
            return;
        }
        $uuid = uniqid();
        if($entity instanceof Living or $entity instanceof ItemEntity){
            if(count($this->entities) > 400) {
                $despawn = array_shift($this->entities);
                if(!$despawn->isClosed()) {
                    $despawn->flagForDespawn();
                }
            }
            $this->ids[$entity->getId()] = $uuid;
            $this->entities[$uuid] = $entity;
            if(LevelManager::canStack($entity)) {
                LevelManager::addToStack($entity);
            }
        }
    }

    /**
     * @priority HIGHEST
     *
     * @param EntityDespawnEvent $event
     */
    public function onEntityDespawn(EntityDespawnEvent $event): void {
        $entity = $event->getEntity();
        if(!isset($this->ids[$entity->getId()])) {
            return;
        }
        $uuid = $this->ids[$entity->getId()];
        unset($this->ids[$entity->getId()]);
        if(isset($this->entities[$uuid])) {
            unset($this->entities[$uuid]);
        }
    }

    /**
     * @priority LOWEST
     *
     * @param EntityDamageEvent $event
     */
    public function onEntityDamage(EntityDamageEvent $event): void {
        $entity = $event->getEntity();
        if($event instanceof EntityDamageByEntityEvent) {
            if($entity->getHealth() <= $event->getFinalDamage() and $entity->namedtag->hasTag(LevelManager::STACK_TAG) and
                $entity instanceof Living) {
                $damager = $event->getDamager();
                if($damager instanceof Player) {
                    $damager->addXp($entity->getXpDropAmount() * 1.5);
                }
                LevelManager::decreaseStackSize($entity);
                $event->setCancelled();
            }
        }
    }
}
