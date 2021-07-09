<?php

namespace BullHCF\listeners;

use BullHCF\{item\PackageItem, Loader, Factions};
use BullHCF\player\Player;

use BullHCF\utils\Time;

use BullHCF\Task\EnderPearlTask;
use BullHCF\Task\specials\{StormBreakerTask, AntiTrapperTask, SpecialItemTask, NinjaShearTask, PotionCounterTask};

use BullHCF\Task\delayedtask\{StormBreakerDelayed};

use BullHCF\item\specials\{EggPorts,
    StormBreaker,
    AntiTrapper,
    Strength,
    Resistance,
    Invisibility,
    PotionCounter,
    Firework,
    PrePearl};

use pocketmine\utils\TextFormat as TE;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;

use pocketmine\item\{Item, ItemIds};
use pocketmine\entity\{Effect, EffectInstance};
use pocketmine\block\{Fence, FenceGate, Fire};

use pocketmine\event\block\{BlockBreakEvent, BlockPlaceEvent};
use pocketmine\event\entity\{EntityDamageEvent, EntityDamageByEntityEvent, ProjectileHitEvent, ProjectileHitEntityEvent};
use pocketmine\event\player\PlayerInteractEvent;

class SpecialItems implements Listener {

    /**
     * SpecialItems Constructor.
     */
    public function __construct(){
        
    }

    /**
     * @param EntityDamageEvent $event
     * @return void
     */
    public function onEntityDamageEvent(EntityDamageEvent $event) : void {
        $player = $event->getEntity();
        if($event instanceof EntityDamageByEntityEvent){
            $damager = $event->getDamager();
            if($player instanceof Player && $damager instanceof Player){
                if($event->getCause() === EntityDamageEvent::CAUSE_ENTITY_ATTACK){
	                $item = $damager->getInventory()->getItemInHand();
	                if(!Factions::isSpawnRegion($damager) && $item instanceof StormBreaker && $item->getNamedTagEntry(StormBreaker::CUSTOM_ITEM) instanceof CompoundTag){
	                    
						if(Factions::inFaction($player->getName()) && Factions::inFaction($damager->getName()) && Factions::getFaction($player->getName()) === Factions::getFaction($damager->getName())) return;
						
	                    if($damager->isStormBreaker()){
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
	                if(!Factions::isSpawnRegion($damager) && $item instanceof AntiTrapper && $item->getNamedTagEntry(AntiTrapper::CUSTOM_ITEM) instanceof CompoundTag){
		
	                    if(Factions::inFaction($player->getName()) && Factions::inFaction($damager->getName()) && Factions::getFaction($player->getName()) === Factions::getFaction($damager->getName())) return;
	
	                    if($damager->isAntiTrapper()){
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
                    if(!Factions::isSpawnRegion($damager) && $item instanceof PotionCounter && $item->getNamedTagEntry(PotionCounter::CUSTOM_ITEM) instanceof CompoundTag){

                        if(Factions::inFaction($player->getName()) && Factions::inFaction($damager->getName()) && Factions::getFaction($player->getName()) === Factions::getFaction($damager->getName())) return;

                        if($damager->isPotionCounter()){
                            $damager->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($damager->getPotionCounterTime())], Loader::getConfiguration("messages")->get("potioncounter_cooldown")));
                            $event->setCancelled(true);
                            return;
                        }
                        $item->reduceUses($damager);

                        $inventory = [];
                        $enderchest = [];
                        foreach($player->getInventory()->getContents() as $slot => $item){
                            if($item->getId() === 438 && $item->getDamage() === 22){
                                $inventory[] = $item;
                            }
                        }
                        foreach($player->getEnderChestInventory()->getContents() as $slot => $item){
                            if($item->getId() === 438 && $item->getDamage() === 22){
                                $enderchest[] = $item;
                            }
                        }
                        $damager->sendMessage(str_replace(["&", "{playerName}", "{potionsTotal}"], ["§",$player->getName(), count($inventory)], Loader::getConfiguration("messages")->get("potioncounter_count_target_inventory_potion")));
                        $damager->sendMessage(str_replace(["&", "{playerName}", "{potionsTotal}"], ["§",$player->getName(), count($enderchest)], Loader::getConfiguration("messages")->get("potioncounter_count_target_enderchest_potion")));

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
    public function onBlockBreak(BlockBreakEvent $event) : void {
        $player = $event->getPlayer();
        if($player->isAntiTrapperTarget()){
            $player->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getAntiTrapperTime())], Loader::getConfiguration("messages")->get("antitrapper_target_cooldown")));
            $event->setCancelled(true);
        }
    }

    /**
     * @param BlockPlaceEvent $event
     * @return void
     */
    public function onBlockPlace(BlockPlaceEvent $event) : void {
        $player = $event->getPlayer();
        if($player->isAntiTrapperTarget()){
            $player->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getAntiTrapperTime())], Loader::getConfiguration("messages")->get("antitrapper_target_cooldown")));
            $event->setCancelled(true);
        }
    }

    /**
     * @param PlayerInteractEvent $event
     * @return void
     */
    public function onPlayerInteractEvent(PlayerInteractEvent $event) : void {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        $item = $event->getItem();
        if($player->isAntiTrapperTarget()){
            if($block instanceof Fence||$block instanceof FenceGate){
                $player->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getAntiTrapperTime())], Loader::getConfiguration("messages")->get("antitrapper_target_cooldown")));
                $event->setCancelled(true);
            }
        }
        if($item instanceof Strength && $item->getNamedTagEntry(Strength::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::STRENGTH), 15 * 10, 1));

                # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                if(Factions::inFaction($player->getName())){
                    foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                        $online = Loader::getInstance()->getServer()->getPlayer($value);
                        if($online instanceof Player && $online->distanceSquared($player) < 30){
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
        if($item instanceof Resistance && $item->getNamedTagEntry(Resistance::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::DAMAGE_RESISTANCE), 15 * 10, 2));

                # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                if(Factions::inFaction($player->getName())){
                    foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                        $online = Loader::getInstance()->getServer()->getPlayer($value);
                        if($online instanceof Player && $online->distanceSquared($player) < 30){
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
        if($item instanceof Invisibility && $item->getNamedTagEntry(Invisibility::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
                    $player->sendTip(str_replace(["&", "{time}"], ["§", Time::getTimeToString($player->getSpecialItemTime())], Loader::getConfiguration("messages")->get("specialitem_cooldown")));
                    $event->setCancelled(true);
                    return;
                }
                $player->addEffect(new EffectInstance(Effect::getEffect(Effect::INVISIBILITY), 20 * 60, 1));

                # This code checks if the player using the item has a faction to give it the effects in the specified radius.
                if(Factions::inFaction($player->getName())){
                    foreach(Factions::getPlayers(Factions::getFaction($player->getName())) as $value){
                        $online = Loader::getInstance()->getServer()->getPlayer($value);
                        if($online instanceof Player && $online->distanceSquared($player) < 30){
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
        if($item instanceof Firework && $item->getNamedTagEntry(Firework::CUSTOM_ITEM) instanceof CompoundTag){
            if($event->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR){
                if($player->isSpecialItem()){
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
        if ($item instanceof PackageItem){
            $item->setCount($item->getCount() -1);
            $eggports = new EggPorts();
            $antitrapper = new AntiTrapper();
            $strength = new Strength();
            $resistance = new Resistance();
            $invisibility = new Invisibility();
            $potionCounter = new PotionCounter();
            $firework = new Firework();
            $stormbreaker = new StormBreaker();
            $player->getInventory()->addItem($strength);
            $player->getInventory()->addItem($antitrapper);
            $player->getInventory()->addItem($resistance);
            $player->getInventory()->addItem($invisibility);
            $player->getInventory()->addItem($potionCounter);
            $player->getInventory()->addItem($firework);
            $player->getInventory()->addItem($stormbreaker);
            $player->getInventory()->addItem($eggports);
        }
    }
}

?>