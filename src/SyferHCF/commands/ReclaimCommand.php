<?php

namespace SyferHCF\commands;

use SyferHCF\Loader;
use SyferHCF\player\{Player, PlayerBase};

use SyferHCF\crate\CrateManager;
use SyferHCF\packages\PackageManager;
use SyferHCF\utils\Time;

use pocketmine\item\{Item, ItemIds};
use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};

use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;

class ReclaimCommand extends PluginCommand {
	
	/**
	 * ReclaimCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("reclaim", Loader::getInstance());
		
		parent::setDescription("Claim your daily rewards");
	}
	
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
	 * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		if($sender->isOP()){
        	if($sender->getTimeReclaimRemaining() < time()){
        		try {
	        		$sender->resetReclaimTime();
	        		
					PackageManager::givePackage($sender, 64);
                    
					Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
				} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());
				}
            }else{
            	$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
			}
		}
		if($sender->hasPermission("partner.reclaim")){
			if($sender->getTimeReclaimRemaining() < time()){
				try {
					$sender->resetReclaimTime();
                
					CrateManager::giveKey($sender, "Boa", 30);
					CrateManager::giveKey($sender, "Python", 25);
					CrateManager::giveKey($sender, "ABILITY", 20);
					CrateManager::giveKey($sender, "OP", 20); 
					CrateManager::giveKey($sender, "Syfer", 15);
					PackageManager::givePackage($sender, 20);
    
					Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
				} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());
				}
            }else{
				$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
		}
	    if($sender->hasPermission("refys+.reclaim")){
			if($sender->getTimeReclaimRemaining() < time()){
				try {
					$sender->resetReclaimTime();
                
					CrateManager::giveKey($sender, "Boa", 25);
					CrateManager::giveKey($sender, "OP", 16);
					CrateManager::giveKey($sender, "ABILITY", 16);
					CrateManager::giveKey($sender, "Python", 20); 
					CrateManager::giveKey($sender, "Syfer", 12);
					PackageManager::givePackage($sender, 16);
					Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
				} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());
				}
            }else{
				$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
		if($sender->hasPermission("booster.reclaim")){
			if($sender->getTimeReclaimRemaining() < time()){
				try {
					$sender->resetReclaimTime();
                
					CrateManager::giveKey($sender, "Boa", 8);
					CrateManager::giveKey($sender, "OP", 2);
					CrateManager::giveKey($sender, "ABILITY", 2);
					CrateManager::giveKey($sender, "Python", 5); 
					CrateManager::giveKey($sender, "Syfer", 2);
					PackageManager::givePackage($sender, 3);
					Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
				} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());
				}
            }else{
				$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
        if($sender->hasPermission("refys.reclaim")){
        	if($sender->getTimeReclaimRemaining() < time()){
				try {
					$sender->resetReclaimTime();
                
					CrateManager::giveKey($sender, "Boa", 20);
					CrateManager::giveKey($sender, "OP", 13);
					CrateManager::giveKey($sender, "ABILITY", 12);
					CrateManager::giveKey($sender, "Python", 17); 
					CrateManager::giveKey($sender, "Syfer", 10);
					PackageManager::givePackage($sender, 12);
    
					Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
				} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());
				}
            }else{
				$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
		}
		if($sender->hasPermission("poison.reclaim")){
			if($sender->getTimeReclaimRemaining() < time()){
				try {
					$sender->resetReclaimTime();
                
					CrateManager::giveKey($sender, "Boa", 17);
					CrateManager::giveKey($sender, "OP", 10);
					CrateManager::giveKey($sender, "ABILITY", 9);
					CrateManager::giveKey($sender, "Python", 15); 
					CrateManager::giveKey($sender, "Syfer", 8);
					PackageManager::givePackage($sender, 8);
    
					Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
				} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());
				}
            }else{
				$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
		}
        if($sender->hasPermission("youtube.reclaim")){
        	if($sender->getTimeReclaimRemaining() < time()){
				try {
					$sender->resetReclaimTime();
            
					CrateManager::giveKey($sender, "Boa", 10);
					CrateManager::giveKey($sender, "OP", 4);
					CrateManager::giveKey($sender, "ABILITY", 4);
					CrateManager::giveKey($sender, "Python", 7); 
					CrateManager::giveKey($sender, "Syfer", 5);
					PackageManager::givePackage($sender, 4);
    
					Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
				} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());
				}
            }else{
				$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
        if($sender->hasPermission("famous.reclaim")){
        	if($sender->getTimeReclaimRemaining() < time()){
				try {
					$sender->resetReclaimTime();
                
					CrateManager::giveKey($sender, "Boa", 16);
					CrateManager::giveKey($sender, "OP", 10);
					CrateManager::giveKey($sender, "ABILITY", 10);
					CrateManager::giveKey($sender, "Python", 12); 
					CrateManager::giveKey($sender, "Syfer", 12);
					PackageManager::givePackage($sender, 8);
    
					Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
				} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());
				}
            }else{
				$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
        if($sender->hasPermission("member.reclaim")){
        	if($sender->getTimeReclaimRemaining() < time()){
				try {
					$sender->resetReclaimTime();
                
					CrateManager::giveKey($sender, "Boa", 5);
					CrateManager::giveKey($sender, "Python", 2); 
					PackageManager::givePackage($sender, 1);
    
					Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
				} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());
				}
            }else{
				$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
        if($sender->hasPermission("forest.reclaim")){
        	if($sender->getTimeReclaimRemaining() < time()){
				try {
					$sender->resetReclaimTime();
                
					CrateManager::giveKey($sender, "Boa", 15);
					CrateManager::giveKey($sender, "OP", 8);
					CrateManager::giveKey($sender, "ABILITY", 7);
					CrateManager::giveKey($sender, "Python", 12); 
					CrateManager::giveKey($sender, "Syfer", 6);
					PackageManager::givePackage($sender, 5);
					
    
					Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
				} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());
				}
            }else{
				$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
        }
        public function package(Player $player, Int $int){
	  $package = Item::get(ItemIds::ENDER_CHEST, 0, $int);
	  $package->setCustomName(TE::BOLD.TE::LIGHT_PURPLE."OP Packages");
	  //$package->setLore([TE::GRAY.""]);
	    $package->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::PROTECTION), 1));
	    $player->getInventory()->addItem($package);            
    }
}

?>
