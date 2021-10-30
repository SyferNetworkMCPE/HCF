<?php

namespace SyferHCF\listeners;

use SyferHCF\{Loader, Factions};
use SyferHCF\player\Player;

use SyferHCF\utils\Time;
use SyferHCF\packages\PackageManager;

use SyferHCF\Task\{EnderPearlTask};

use SyferHCF\Task\specials\{ResetItemsTask, RefillTask, SpecialItemsTask, AntiTrapperTask, SpecialItemTask, LoggerBaitTask, StormBreakerTask, PotionCounterTask, SnowballTask, NinjaShearTask, CamuflajeTask};
use SyferHCF\Task\delayedtask\{StormBreakerDelayed, NinjaShearDelayed};

use SyferHCF\item\specials\{Custom, CustomProjectileItem, StormBreaker, AntiTrapper, Refill, PrePearl, Strength, RemoveEffects, Camuflaje, Cocaine, CloseCall, Resistance, Invisibility, RageBall, PotionCounter, Firework, ResetItems, PartnerPackages, SecondChance, LoggerBait, EffectsBard, NoPotions, NinjaShear, AntiFall};

use pocketmine\utils\TextFormat as TE;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\scheduler\ClosureTask;

use pocketmine\item\{Item, ItemIds, ItemFactory};
use pocketmine\entity\{Effect, EffectInstance, Entity, Villager, Human};
use pocketmine\block\{Fence, FenceGate, Fire};

use pocketmine\event\block\{BlockBreakEvent, BlockPlaceEvent};
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent, ProjectileHitEvent, ProjectileHitEntityEvent};
use pocketmine\event\player\PlayerInteractEvent;

use pocketmine\level\sound\BlazeShootSound;
use pocketmine\level\particle\HugeExplodeParticle;
class SpecialItems implements Listener {

    /**
     * SpecialItems Constructor.
     */
    public function __construct()
    {

    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onEntityDamageEvent(EntityDamageEvent $event): void
    {
        $player = $event->getEntity();
        if ($event instanceof EntityDamageByEntityEvent) {
            $damager = $event->getDamager();
            if ($player instanceof Player && $damager instanceof Player) {
                if ($event->getCause() === EntityDamageEvent::CAUSE_ENTITY_ATTACK) {
                    $item = $damager->getInventory()->getItemInHand();
                    if (!Factions::isSpawnRegion($damager) && $item instanceof StormBreaker && $item->getNamedTagEntry(StormBreaker::CUSTOM_ITEM) instanceof CompoundTag) {

                        if (Factions::inFaction($player->getName()) && Factions::inFaction($damager->getName()) && Factions::getFaction($player->getName()) === Factions::getFaction($damager->getName())) return;

                        if ($damager->isStormBreaker()) {
                            $damager->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($damager->getStormBreakerTime())], Loader::getConfiguration("messages")->get("stormbreaker_cooldown")));
                            $event->setCancelled(true);
                            return;
                        }
                        $damager->sendMessage(str_replace(["&", "{playerName}"], ["§", $player->getName()], Loader::getConfiguration("messages")->get("stormbreaker_was_used_correctly")));

                        # This task is executed after a few seconds, to remove the player's helmet
                        Loader::getInstance()->getScheduler()->scheduleDelayedTask(new StormBreakerDelayed($player), 40);

                        $item->reduceUses($damager);
                        $damager->setStormBreaker(true);
                        Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new StormBreakerTask($damager), 20);
                    }

                    if (!Factions::isSpawnRegion($damager) && $item instanceof AntiTrapper && $item->getNamedTagEntry(AntiTrapper::CUSTOM_ITEM) instanceof CompoundTag) {

                        if (Factions::inFaction($player->getName()) && Factions::inFaction($damager->getName()) && Factions::getFaction($player->getName()) === Factions::getFaction($damager->getName())) return;

                        if ($damager->isAntiTrapper()) {
                            $damager->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($damager->getAntiTrapperTime())], Loader::getConfiguration("messages")->get("antitrapper_cooldown")));
                            $event->setCancelled(true);
                            return;
                        }
                        $item->reduceUses($damager);
                        $damager->setAntiTrapper(true);
                        //here we place the time for which the player cannot place blocks
                        $player->setAntiTrapperTarget(true);
                        Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new AntiTrapperTask($damager, $player), 20);
                    }
                    if (!Factions::isSpawnRegion($damager) && $item instanceof PotionCounter && $item->getNamedTagEntry(PotionCounter::CUSTOM_ITEM) instanceof CompoundTag) {

                        if (Factions::inFaction($player->getName()) && Factions::inFaction($damager->getName()) && Factions::getFaction($player->getName()) === Factions::getFaction($damager->getName())) return;

                        if ($damager->isPotionCounter()) {
                            $damager->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($damager->getPotionCounterTime())], Loader::getConfiguration("messages")->get("potioncounter_cooldown")));
                            $event->setCancelled(true);
                            return;
                        }
                        $item->reduceUses($damager);

                        $inventory = [];
                        $enderchest = [];
                        foreach ($player->getInventory()->getContents() as $slot => $item) {
                            if ($item->getId() === 438 && $item->getDamage() === 22) {
                                $inventory[] = $item;
                            }
                        }
                        foreach ($player->getEnderChestInventory()->getContents() as $slot => $item) {
                            if ($item->getId() === 438 && $item->getDamage() === 22) {
                                $enderchest[] = $item;
                            }
                        }
                        $damager->sendMessage(str_replace(["&", "{playerName}", "{potionsTotal}"], ["§", $player->getName(), count($inventory)], Loader::getConfiguration("messages")->get("potioncounter_count_target_inventory_potion")));
                        $damager->sendMessage(str_replace(["&", "{playerName}", "{potionsTotal}"], ["§", $player->getName(), count($enderchest)], Loader::getConfiguration("messages")->get("potioncounter_count_target_enderchest_potion")));

                        $damager->setPotionCounter(true);
                        Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new PotionCounterTask($damager), 20);
                    }
                }
            }
        }
    }

    /**
     * @param BlockBreakEvent $event
     * @return void
     */
    public function onBlockBreak(BlockBreakEvent $event): void
    {
        $player = $event->getPlayer();
        if ($player->isAntiTrapperTarget()) {
            $player->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getAntiTrapperTime())], Loader::getConfiguration("messages")->get("antitrapper_target_cooldown")));
            $event->setCancelled(true);
        }
    }

    /**
     * @param BlockPlaceEvent $event
     * @return void
     */
    public function onBlockPlace(BlockPlaceEvent $event): void
    {
        $player = $event->getPlayer();
        if ($player->isAntiTrapperTarget()) {
            $player->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getAntiTrapperTime())], Loader::getConfiguration("messages")->get("antitrapper_target_cooldown")));
            $event->setCancelled(true);
        }
    }

    /**
     * @param PlayerInteractEvent $event
     * @return void
     */
    public function onPlayerInteractEvent(PlayerInteractEvent $event): void
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $event->getItem();
        if ($player instanceof Player) {
            if ($player->isAntiTrapperTarget()) {
                if ($block instanceof Fence || $block instanceof FenceGate) {
                    $player->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getAntiTrapperTime())], Loader::getConfiguration("messages")->get("antitrapper_target_cooldown")));
                    $event->setCancelled(true);
                }
            }
            if ($item instanceof Strength && $item->getNamedTagEntry(Strength::CUSTOM_ITEM) instanceof CompoundTag) {
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR) {
                    if ($player->isSpecialItem()) {
                        $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                        $event->setCancelled(true);
                        return;
                    }
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 15 * 10, 1));

                    # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                    if (Factions::inFaction($player->getName())) {
                        foreach (Factions::getPlayers(Factions::getFaction($player->getName())) as $value) {
                            $online = Loader::getInstance()->getServer()->getPlayer($value);
                            if ($online instanceof Player && $online->distanceSquared($player) < 30) {
                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 15 * 10, 1));
                            }
                        }
                    }
                    $item->setCount($item->getCount() - 1);
                    $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                    $player->setSpecialItem(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
                }
            }
            if ($item instanceof AntiFall && $item->getNamedTagEntry(AntiFall::CUSTOM_ITEM) instanceof CompoundTag) {
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR) {
                    if ($player->isSpecialItem()) {
                        $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                        $event->setCancelled(true);
                        return;
                    }
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::LEVITATION), 15 * 15, 4));

                    # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                    if (Factions::inFaction($player->getName())) {
                        foreach (Factions::getPlayers(Factions::getFaction($player->getName())) as $value) {
                            $online = Loader::getInstance()->getServer()->getPlayer($value);
                            if ($online instanceof Player && $online->distanceSquared($player) < 30) {
                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::LEVITATION), 15 * 15, 4));
                            }
                        }
                    }
                    $item->setCount($item->getCount() - 1);
                    $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                    $player->setSpecialItem(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
                }
            }
            if ($item instanceof RageBall && $item->getNamedTagEntry(RageBall::CUSTOM_ITEM) instanceof CompoundTag) {
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR) {
                    if ($player->isSpecialItem()) {
                        $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                        $event->setCancelled(true);
                        return;
                    }
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 15 * 15, 2));
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SLOWNESS), 15 * 15, 1));

                    # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                    if (Factions::inFaction($player->getName())) {
                        foreach (Factions::getPlayers(Factions::getFaction($player->getName())) as $value) {
                            $online = Loader::getInstance()->getServer()->getPlayer($value);
                            if ($online instanceof Player && $online->distanceSquared($player) < 30) {
                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 15 * 15, 2));
                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::SLOWNESS), 15 * 15, 1));
                            }
                        }
                    }
                    $item->setCount($item->getCount() - 1);
                    $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                    $player->setSpecialItem(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
                }
            }
            if ($item instanceof NoPotions && $item->getNamedTagEntry(NoPotions::CUSTOM_ITEM) instanceof CompoundTag) {
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR) {
                    if ($player->isSpecialItem()) {
                        $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                        $event->setCancelled(true);
                        return;
                    }
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 20 * 15, 4));

                    # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                    if (Factions::inFaction($player->getName())) {
                        foreach (Factions::getPlayers(Factions::getFaction($player->getName())) as $value) {
                            $online = Loader::getInstance()->getServer()->getPlayer($value);
                            if ($online instanceof Player && $online->distanceSquared($player) < 30) {
                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 20 * 15, 4));
                            }
                        }
                    }
                    $item->setCount($item->getCount() - 1);
                    $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                    $player->setSpecialItem(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
                }
            }
            if ($item instanceof LoggerBait && $item->getNamedTagEntry(LoggerBait::CUSTOM_ITEM) instanceof CompoundTag) {
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
                    if ($player->isLoggerBait()) {
                        $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getLoggerBaitTime())], Loader::getConfiguration("messages")->get("loggerbait_cooldown")));
                        $event->setCancelled(true);
                        return;
                    }
                    $nbt = Entity::createBaseNBT(new Vector3((float)$player->getX(), (float)$player->getY(), (float)$player->getZ()));
                    $human = new Villager($player->getLevel(), $nbt);
                    $human->setNameTagVisible(true);
                    $human->setNameTagAlwaysVisible(true);
                    $human->yaw = $player->getYaw();
                    $human->pitch = $player->getPitch();
                    $human->setNameTag(TE::GRAY . "(Combat-Logger) " . TE::LIGHT_PURPLE . $player->getName() . TE::GRAY);
                    $human->spawnToAll();
                    $player->setLoggerBait(true);
                    $item->setCount($item->getCount() - 1);
                    $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                    $player->setLoggerBait(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new LoggerBaitTask($player), 20);
                }
            }
            if ($item instanceof EffectsBard && $item->getNamedTagEntry(EffectsBard::CUSTOM_ITEM) instanceof CompoundTag) {
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR) {
                    if ($player->isSpecialItem()) {
                        $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                        $event->setCancelled(true);
                        return;
                    }
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 15 * 9, 1)); 
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 3 * 9, 2));
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 9, 2)); 
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 20 * 9, 2));


                    # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                    if (Factions::inFaction($player->getName())) {
                        foreach (Factions::getPlayers(Factions::getFaction($player->getName())) as $value) {
                            $online = Loader::getInstance()->getServer()->getPlayer($value);
                            if ($online instanceof Player && $online->distanceSquared($player) < 30) {
                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 15 * 9, 1));
                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 20 * 9, 2)); 
                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::RESISTANCE), 20 * 9, 2));
                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::JUMP_BOOST), 20 * 9, 2));
                            }
                        }
                    }
                    $item->setCount($item->getCount() - 1);
                    $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                    $player->setSpecialItem(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
                }
            }
            if ($item instanceof Resistance && $item->getNamedTagEntry(Resistance::CUSTOM_ITEM) instanceof CompoundTag) {
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR) {
                    if ($player->isSpecialItem()) {
                        $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                        $event->setCancelled(true);
                        return;
                    }
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 15 * 10, 2));

                    # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                    if (Factions::inFaction($player->getName())) {
                        foreach (Factions::getPlayers(Factions::getFaction($player->getName())) as $value) {
                            $online = Loader::getInstance()->getServer()->getPlayer($value);
                            if ($online instanceof Player && $online->distanceSquared($player) < 30) {
                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 15 * 10, 2));
                            }
                        }
                    }
                    $item->setCount($item->getCount() - 1);
                    $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                    $player->setSpecialItem(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
                }
            }
            if ($item instanceof Cocaine && $item->getNamedTagEntry(Cocaine::CUSTOM_ITEM) instanceof CompoundTag) {
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR) {
                    if ($player->isSpecialItem()) {
                        $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                        $event->setCancelled(true);
                        return;
                    }
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 12 * 12, 3));

                    # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                    if (Factions::inFaction($player->getName())) {
                        foreach (Factions::getPlayers(Factions::getFaction($player->getName())) as $value) {
                            $online = Loader::getInstance()->getServer()->getPlayer($value);
                            if ($online instanceof Player && $online->distanceSquared($player) < 30) {
                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::SPEED), 12 * 12, 3));
                            }
                        }
                    }
                    $item->setCount($item->getCount() - 1);
                    $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                    $player->setSpecialItem(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
                }
            }
            if ($item instanceof CloseCall && $item->getNamedTagEntry(CloseCall::CUSTOM_ITEM) instanceof CompoundTag) {
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR) {
                    if ($player->isSpecialItem()) {
                        $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                        $event->setCancelled(true);
                        return;
                    }
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 13 * 13, 4));
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 13 * 13, 1));

                    # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                    if (Factions::inFaction($player->getName())) {
                        foreach (Factions::getPlayers(Factions::getFaction($player->getName())) as $value) {
                            $online = Loader::getInstance()->getServer()->getPlayer($value);
                            if ($online instanceof Player && $online->distanceSquared($player) < 30) {
                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::REGENERATION), 13 * 13, 4));
                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 13 * 13, 1));
                            }
                        }
                    }
                    $item->setCount($item->getCount() - 1);
                    $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                    $player->setSpecialItem(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
                }
            }
            if ($item instanceof RemoveEffects && $item->getNamedTagEntry(RemoveEffects::CUSTOM_ITEM) instanceof CompoundTag) {
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR) {
                    if ($player->isSpecialItem()) {
                        $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                        $event->setCancelled(true);
                        return;
                    }
                    $player->removeAllEffects();

                    # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                    if (Factions::inFaction($player->getName())) {
                        foreach (Factions::getPlayers(Factions::getFaction($player->getName())) as $value) {
                            $online = Loader::getInstance()->getServer()->getPlayer($value);
                            if ($online instanceof Player && $online->distanceSquared($player) < 30) {
                                $online->removeAllEffects();
                            }
                        }
                    }
                    $item->setCount($item->getCount() - 1);
                    $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                    $player->setSpecialItem(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
                }
            }
            if ($item instanceof Invisibility && $item->getNamedTagEntry(Invisibility::CUSTOM_ITEM) instanceof CompoundTag) {
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR) {
                    if ($player->isSpecialItem()) {
                        $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                        $event->setCancelled(true);
                        return;
                    }
                    $player->addEffect(new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 20 * 60, 1));

                    # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                    if (Factions::inFaction($player->getName())) {
                        foreach (Factions::getPlayers(Factions::getFaction($player->getName())) as $value) {
                            $online = Loader::getInstance()->getServer()->getPlayer($value);
                            if ($online instanceof Player && $online->distanceSquared($player) < 30) {
                                $online->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 20 * 60, 1));
                            }
                        }
                    }
                    $item->setCount($item->getCount() - 1);
                    $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                    $player->setSpecialItem(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
                }
            }
            if($item instanceof Camuflaje && $item->getNamedTagEntry(Camuflaje::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isCamuflaje()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getCamuflajeTime())], Loader::getConfiguration("messages")->get("camuflaje_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
                    $player->hidePlayer($event->getPlayer());
                    }
                $item->setCount($item->getCount() - 1);
                $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                $player->setCamuflaje(true);
                
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new CamuflajeTask($player), 20);
                }
            }
            if ($item instanceof Refill && $item->getNamedTagEntry(Refill::CUSTOM_ITEM) instanceof CompoundTag) {
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK) {
                    if ($player->isRefill()) {
                        $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getRefillTime())], Loader::getConfiguration("messages")->get("refill_cooldown")));
                        $event->setCancelled(true);
                        return;
                    }
                    $potions = Item::get(438, 22, 10);
                    $player->getInventory()->addItem($potions);
                    $item->setCount($item->getCount() - 1);
                    $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                    $player->setRefill(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new RefillTask($player), 20);
                }
            }
            if ($item instanceof Firework && $item->getNamedTagEntry(Firework::CUSTOM_ITEM) instanceof CompoundTag) {
                if ($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR) {
                    if ($player->isSpecialItem()) {
                        $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                        $event->setCancelled(true);
                        return;
                    }
                    $player->knockBack($player, 0, $player->getDirectionVector()->x, $player->getDirectionVector()->z, 2.1);

                    $item->setCount($item->getCount() - 1);
                    $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                    $player->setSpecialItem(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
                }
            }
        }
        if($item instanceof ResetItems && $item->getNamedTagEntry(ResetItems::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isResetItems()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getResetItemsTime())], Loader::getConfiguration("messages")->get("resetitems_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                if($player->isSpecialItem()){
                    $player->setSpecialItem(false);
                }else{
                    $player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("resetitems_cannot_use_if_not_have_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $item->setCount($item->getCount() - 1);
                    $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                    $player->setResetItems(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new ResetItemsTask($player), 20);
                }
            }
        if($item instanceof SecondChance && $item->getNamedTagEntry(SecondChance::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                if($player->isEnderPearl()){
                    $player->setEnderPearl(false);
                }else{
                    $player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("secondchance_cannot_use_if_not_have_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $item->setCount($item->getCount() - 1);
                    $player->getInventory()->setItemInHand($item->getCount() > 0 ? $item : Item::get(Item::AIR));
                    $player->setSpecialItem(true);
                    Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new SpecialItemTask($player), 20);
                }
            }
         if($item instanceof NinjaShear && $item->getNamedTagEntry(NinjaShear::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isNinjaShear()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getNinjaShearTime())], Loader::getConfiguration("messages")->get("ninjashear_cooldown")));
                    $event->setCancelled(true);
                    return;
            }
            if(empty($player->getNinjaShearPosition())){
                $player->sendTip(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("ninjashear_have_no_hit_registered")));
                return;
            }
            $player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("ninjashear_was_used_correctly")));
            
            # This task runs after a few seconds, to teleport the player to the saved position
            Loader::getInstance()->getScheduler()->scheduleDelayedTask(new NinjaShearDelayed($player, $player->getNinjaShearPosition()), 20);
            
            $item->reduceUses($player);
            $player->setNinjaShear(true);
            Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new NinjaShearTask($player), 20);
        }
        }
        if($item instanceof PartnerPackages && $item->getNamedTagEntry(PartnerPackages::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_BLOCK){
                $package = PackageManager::getPackage();
                if(empty($package)) return;

                foreach(PackageManager::getRewards() as $item){
                    if(!$player->getInventory()->canAddItem(ItemFactory::get($item->getId(), $item->getDamage()))){
                        return;                
                    }
                    $player->getInventory()->addItem($item);
                    $player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCount($player->getInventory()->getItemInHand()->getCount() - 1));
                    $player->sendMessage(str_replace(["&", "{itemName}"], ["§", $item->getName()], Loader::getConfiguration("messages")->get("package_give_reward_correctly")));
                    
                    $source = (new Vector3($player->x, $player->y, $player->z))->floor();
                    $player->getLevel()->addSound(new BlazeShootSound($source));
                    $player->getLevel()->addSound(new BlazeShootSound($source));
                }
            }
        }
    }
}
