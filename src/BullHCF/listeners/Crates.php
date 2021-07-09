<?php

namespace BullHCF\listeners;

use BullHCF\{API\InvMenu\InvMenu, Loader, Factions};
use BullHCF\player\Player;

use BullHCF\crate\CrateManager;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\event\Listener;
use pocketmine\item\ItemFactory;

use pocketmine\block\Chest;

use pocketmine\event\player\{PlayerJoinEvent, PlayerInteractEvent};
use pocketmine\event\block\{BlockBreakEvent, BlockPlaceEvent};

class Crates implements Listener {
	
	/**
	 * Crates Constructor.
	 */
	public function __construct(){
		
	}
	
	/**
	 * @param BlockPlaceEvent $event
	 * @return void
	 */
	public function onBlockPlaceEvent(BlockPlaceEvent $event) : void {
		$player = $event->getPlayer();
		$block = $event->getBlock();
		foreach(CrateManager::getCrates() as $crate){
			$position = $block->getLevel()->getBlock($block->subtract(0, 1));
			if($block instanceof Chest && $player->isOp() && $player->isGodMode() && ($type = $crate->isBlock($position->getId(), $position->getDamage()))){
				$crate->setPosition([$block->getX(), $block->getY(), $block->getZ()]);
				$crate->updateTag();
				$player->sendMessage(str_replace(["&", "{crateName}"], ["§", $crate->getNameFormat()], Loader::getConfiguration("messages")->get("place_crate_correctly")));
			}
		}
	}
	
	/**
	 * @param BlockBreakEvent $event
	 * @return void
	 */
	public function onBlockBreakEvent(BlockBreakEvent $event) : void {
		$player = $event->getPlayer();
		$block = $event->getBlock();
		foreach(CrateManager::getCrates() as $crate){
			$position = $block->getLevel()->getBlock($block->subtract(0, 1));
			if($block instanceof Chest && $player->isOp() && $player->isGodMode() && ($type = $crate->isBlock($position->getId(), $position->getDamage()))){
				CrateManager::removeCrate($crate->getName());
				$player->sendMessage(str_replace(["&", "{crateName}"], ["§", $crate->getNameFormat()], Loader::getConfiguration("messages")->get("remove_crate_correctly")));
			}
		}
	}
	
	/**
	 * @param PlayerJoinEvent $event
	 * @return void
	 */
	public function onPlayerJoinEvent(PlayerJoinEvent $event) : void {
		$player = $event->getPlayer();
		foreach(CrateManager::getCrates() as $crate){
			$crate->addParticles($player);
		}
	}
	
	/**
	 * @param PlayerInteractEvent $event
	 * @return void
	 */
	public function onPlayerInteractEvent(PlayerInteractEvent $event) : void {
		$player = $event->getPlayer();
		$block = $event->getBlock();
		foreach(CrateManager::getCrates() as $crate){
			$position = $block->getLevel()->getBlock($block->subtract(0, 1));
			if($block instanceof Chest && Factions::isSpawnRegion($block) && ($type = $crate->isBlock($position->getId(), $position->getDamage()))){
				if($player->getInventory()->getItemInHand()->getCustomName() === $crate->getKeyName() && ($key = $crate->isKey($player->getInventory()->getItemInHand()->getId(), $player->getInventory()->getItemInHand()->getDamage()))){
					$this->giveRewards($player, $crate->getName());
					$event->setCancelled(true);
				}else{
					$this->seeRewards($player, $crate->getName());
					$event->setCancelled(true);
				}
			}
		}
	}

    /**
     * @param Player $player
     * @param String $type
     * @return void
     */
    public function seeRewards(Player $player, String $type) : void {
        $crate = CrateManager::getCrate($type);
        $menu = InvMenu::create(InvMenu::TYPE_CHEST);
        $menu->setName($crate->getNameFormat());
        $menu->getInventory()->setContents($crate->getItems());
        $menu->send($player);
    }

    /**
	 * @param String $type
	 * @return Array[]
	 */
	public function getRewards(String $type) : Array {
		$crate = CrateManager::getCrate($type);
		$items = $crate->getItems();
		
		$item1 = $items[array_rand($items)];
		$item2 = $items[array_rand($items)];
		if(mt_rand(0, 10) === 3){
			return [$item1, $item2];
		}else{
			return [$item1];
		}
	}
	
	/**
	 * @param Player $player
	 * @param String $type
	 */
	public function giveRewards(Player $player, String $type){
		if(empty($this->getRewards($type))){
            $player->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("crate_rewards_empty")));
			return;
		}
		foreach($this->getRewards($type) as $item){
			if(!$player->getInventory()->canAddItem(ItemFactory::get($item->getId(), $item->getDamage()))){
				return;
			}
			$player->getInventory()->setItemInHand($player->getInventory()->getItemInHand()->setCount($player->getInventory()->getItemInHand()->getCount() - 1));
			$player->getInventory()->addItem($item);
            $player->sendMessage(str_replace(["&", "{itemName}"], ["§", $item->getName()], Loader::getConfiguration("messages")->get("crate_give_reward_correctly")));
		}
	}
}

?>