<?php

namespace SyferHCF\listeners;

use SyferHCF\{Loader, Factions};
use SyferHCF\player\Player;
use SyferHCF\API\InvMenu\ChestInventory;

use pocketmine\item\ItemIds;
use pocketmine\event\Listener;
use pocketmine\math\Vector3;
use pocketmine\utils\TextFormat as TE;

use pocketmine\event\player\{PlayerInteractEvent, PlayerMoveEvent};
use pocketmine\event\player\PlayerQuitEvent;

use pocketmine\event\block\{BlockBreakEvent, BlockPlaceEvent};

use pocketmine\entity\{Effect, EffectInstance};

use pocketmine\block\{ItemFrame, Door, Fence, FenceGate, Trapdoor, Chest, TrappedChest};
use pocketmine\block\EnchantingTable;
use pocketmine\block\Beacon;

use pocketmine\event\inventory\InventoryCloseEvent;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\inventory\transaction\action\SlotChangeAction;


class Spawn implements Listener {
	
	/**
	 * Spawn Constructor.
     * @param Loader $plugin
     */

	public $enter;

	public function __construct(){
		
	}
	
	/**
	 * @param PlayerInteractEvent $event
	 * @return void
	 */
	public function onPlayerInteractEvent(PlayerInteractEvent $event){
		$player = $event->getPlayer();
		$block = $event->getBlock();
		$item = $event->getItem();
		if(in_array($item->getId(), Loader::getDefaultConfig("items_id"))){

        	$event->setCancelled(true);

        }
		if($player->getLevel() === Loader::getInstance()->getServer()->getDefaultLevel()){
			if($player instanceof Player){
				if($block instanceof ItemFrame||$block instanceof Fence||$block instanceof FenceGate||$block instanceof Door||$block instanceof Trapdoor||$block instanceof Chest||$block instanceof TrappedChest||$item instanceof Bucket||$item instanceof Hoe||$item instanceof Shovel||$block instanceof Transparent||$block instanceof EnchantingTable){
					/** @var Vector3|x:|y:|z: */
					$spawn = new Vector3(0, 4, 0);
					if((int)$spawn->distance($event->getBlock()) < 400){
						if($player->isGodMode()){
							$event->setCancelled(false);
						}else{
							$event->setCancelled(true);
						}
					}
				}
			}
		}
		if($player->getLevel()->getName() === Loader::getDefaultConfig("LevelManager")["levelEndName"]){
			if($block instanceof ItemFrame||$block instanceof Fence||$block instanceof FenceGate||$block instanceof Door||$block instanceof Trapdoor||$block instanceof Chest||$block instanceof TrappedChest||$item instanceof Bucket||$item instanceof Hoe||$item instanceof Shovel||$block instanceof EnchantingTable||$block instanceof Beacon){
				if($player->isGodMode()){
					$event->setCancelled(false);
				}else{
					$event->setCancelled(true);
				}
			}
		}
	}
	
	/**
	 * @param BlockBreakEvent $event
	 * @return void
	 */
	public function onBlockBreak(BlockBreakEvent $event){
		$player = $event->getPlayer();
		if($player->getLevel() === Loader::getInstance()->getServer()->getDefaultLevel()){
			if($player instanceof Player){
				/** @var Vector3|x:|y:|z: */
				$spawn = new Vector3(0, 4, 0);
				if((int)$spawn->distance($event->getBlock()) < 400){
					if($player->isGodMode()){
						$event->setCancelled(false);
					}else{
						$event->setCancelled(true);
					}
				}
			}
		}
		if($player->getLevel()->getName() === Loader::getDefaultConfig("LevelManager")["levelEndName"]){
			if($player->isGodMode()){
				$event->setCancelled(false);
			}else{
				$event->setCancelled(true);
			}
		}
	}
	
	/**
	 * @param BlockPlaceEvent $event
	 * @return void
	 */
	public function onBlockPlace(BlockPlaceEvent $event){
		$player = $event->getPlayer();
		if($player->getLevel() === Loader::getInstance()->getServer()->getDefaultLevel()){
			if($player instanceof Player){
				/** @var Vector3|x:|y:|z: */
				$spawn = new Vector3(0, 4, 0);
				if((int)$spawn->distance($event->getBlock()) < 400){
					if($player->isGodMode()){
						$event->setCancelled(false);
					}else{
						$event->setCancelled(true);
					}
				}
			}
		}
		if($player->getLevel()->getName() === Loader::getDefaultConfig("LevelManager")["levelEndName"]){
			if($player->isGodMode()){
				$event->setCancelled(false);
			}else{
				$event->setCancelled(true);
			}
		}
	}
	
	/**
	 * @param PlayerMoveEvent $event
	 * @return void
	 */
	public function onPlayerMoveEvent(PlayerMoveEvent $event) : void {
		$player = $event->getPlayer();
		if($player instanceof Player){
			if(!$this->isBorderLimit($player)){
				$player->teleport($this->correctPosition($player));
			}
			if($player->getRegion() === "Spawn"){
				if($player->isCombatTag()){
					$player->sendMessage(TE::RED."You can't walk into spawn in Spawn Tag!");
					return;
				}
			}
			if(Factions::inFaction($player->getName())){
			  $player->setNameTag(
			      TE::GRAY."[".TE::LIGHT_PURPLE.Factions::getFaction($player->getName()).TE::GRAY." | ".TE::RED.Factions::getStrength(Factions::getFaction($player->getName())).TE::YELLOW."■".TE::GRAY."]\n"
                  .TE::LIGHT_PURPLE.$player->getDisplayName());
			  
			}else{
			  $player->setNameTag(TE::GOLD.$player->getName());
        }
		}
	}
	
	/**
	 * @param Vector3 $position
	 * @return bool
	 */
	protected function isBorderLimit(Vector3 $position) : bool {
		$border = Loader::getDefaultConfig("FactionsConfig")["BorderLimit"];
		return $position->getFloorX() >= -$border && $position->getFloorX() <= $border && $position->getFloorZ() >= -$border && $position->getFloorZ() <= $border;
	}
	
	/**
	 * @param Vector3 $position
	 * @return Vector3
	 */
	protected function correctPosition(Vector3 $position) : Vector3 {
		$border = Loader::getDefaultConfig("FactionsConfig")["BorderLimit"];
		$radius = 2000;
		
		$x = $position->getFloorX();
		$y = $position->getFloorY();
		$z = $position->getFloorZ();
		
		$xMin = -$border;
		$xMax = $border;
		
		$zMin = -$border;
		$zMax = $border;
		
		if($x <= $xMin){
			$x = $xMin + 4;
		}elseif($x >= $xMax){
			$x = $xMax - 4;
		}
		if($z <= $zMin){
			$z = $zMin + 4;
		}elseif($z >= $zMax){
			$z = $zMax - 4;
		}
		$y = 72;
		return new Vector3($x, $y, $z, $position->getLevel());
	}
	public function onPlayerQuitEvent(PlayerQuitEvent $event) : void {

		$player = $event->getPlayer();

		if($player->isInventoryHandler()){
			$player->unsetInventoryHandler();
		}
	}
	
	/**
	 * @param InventoryCloseEvent $event
	 * @return void
	 */
	public function onInventoryCloseEvent(InventoryCloseEvent $event) : void {
		$player = $event->getPlayer();
		$inventory = $event->getInventory();
		if($inventory instanceof ChestInventory){
			//TODO:
            $inventory->removeFakeBlock($player);
			$player->unsetInventoryHandler();
		}
	}
	

	
	/**
	 * @param InventoryTransactionEvent $event
	 * @return void
	 */
	public function onInventoryTransactionEvent(InventoryTransactionEvent $event) : void {
		$transaction = $event->getTransaction();
		foreach($transaction->getActions() as $action){
			if($action instanceof SlotChangeAction){
				$inventory = $action->getInventory();
				if($inventory instanceof ChestInventory){
					$event->setCancelled(true);
				}
			}
		}
	}
}

?>
