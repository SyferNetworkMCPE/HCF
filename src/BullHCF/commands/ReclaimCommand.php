<?php

namespace BullHCF\commands;

use BullHCF\Loader;
use BullHCF\player\{Player, PlayerBase};

use BullHCF\crate\CrateManager;
use BullHCF\utils\Time;

use pocketmine\item\{Item, ItemIds};
use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};

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
        if($sender->hasPermission("Staff.reclaim.use")){
        	if($sender->getTimeReclaimRemaining() < time()){
        		try {
	        		$sender->resetReclaimTime();
	
					if(!PlayerBase::getData($sender->getName())->get("lives_claimed")) $sender->setLives(60);
					PlayerBase::setData($sender->getName(), "lives_claimed", true);
					
					CrateManager::giveKey($sender, "Common", 30);
					CrateManager::giveKey($sender, "Cow", 25);
					CrateManager::giveKey($sender, "Legend", 20);
					CrateManager::giveKey($sender, "Partner", 20);
                    
					Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
				} catch(\Exception $exception){
					$sender->sendMessage($exception->getMessage());
				}
            }else{
            	$sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
			}
		}
		if($sender->hasPermission("Partner.reclaim.use")){
            if($sender->getTimeReclaimRemaining() < time()){
                try {
                    $sender->resetReclaimTime();

                    if(!PlayerBase::getData($sender->getName())->get("lives_claimed")) $sender->setLives(50);
                    PlayerBase::setData($sender->getName(), "lives_claimed", true);

                    CrateManager::giveKey($sender, "Common", 25);
                    CrateManager::giveKey($sender, "Cow", 25);
                    CrateManager::giveKey($sender, "Legend", 15);
                    CrateManager::giveKey($sender, "Partner", 15);

                    Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
                } catch(\Exception $exception){
                    $sender->sendMessage($exception->getMessage());
                }
            }else{
                $sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
        if($sender->hasPermission("Bull.reclaim.use")){
            if($sender->getTimeReclaimRemaining() < time()){
                try {
                    $sender->resetReclaimTime();

                    if(!PlayerBase::getData($sender->getName())->get("lives_claimed")) $sender->setLives(30);
                    PlayerBase::setData($sender->getName(), "lives_claimed", true);

                    CrateManager::giveKey($sender, "Common", 30);
                    CrateManager::giveKey($sender, "Cow", 25);
                    CrateManager::giveKey($sender, "Legend", 16);
                    CrateManager::giveKey($sender, "Partner", 15);

                    Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
                } catch(\Exception $exception){
                    $sender->sendMessage($exception->getMessage());
                }
            }else{
                $sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
        if($sender->hasPermission("Demon.reclaim.use")){
            if($sender->getTimeReclaimRemaining() < time()){
                try {
                    $sender->resetReclaimTime();

                    if(!PlayerBase::getData($sender->getName())->get("lives_claimed")) $sender->setLives(20);
                    PlayerBase::setData($sender->getName(), "lives_claimed", true);

                    CrateManager::giveKey($sender, "Common", 28);
                    CrateManager::giveKey($sender, "Cow", 22);
                    CrateManager::giveKey($sender, "Legend", 14);
                    CrateManager::giveKey($sender, "Partner", 10);

                    Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
                } catch(\Exception $exception){
                    $sender->sendMessage($exception->getMessage());
                }
            }else{
                $sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
        if($sender->hasPermission("CowHero.reclaim.use")){
            if($sender->getTimeReclaimRemaining() < time()){
                try {
                    $sender->resetReclaimTime();

                    if(!PlayerBase::getData($sender->getName())->get("lives_claimed")) $sender->setLives(8);
                    PlayerBase::setData($sender->getName(), "lives_claimed", true);

                    CrateManager::giveKey($sender, "Common", 14);
                    CrateManager::giveKey($sender, "Cow", 10);
                    CrateManager::giveKey($sender, "Legend", 6);
                    CrateManager::giveKey($sender, "Partner", 2);

                    Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
                } catch(\Exception $exception){
                    $sender->sendMessage($exception->getMessage());
                }
            }else{
                $sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
        if($sender->hasPermission("VIP.reclaim.use")||$sender->hasPermission("NitroBooster.reclaim.use")){
            if($sender->getTimeReclaimRemaining() < time()){
                try {
                    $sender->resetReclaimTime();

                    if(!PlayerBase::getData($sender->getName())->get("lives_claimed")) $sender->setLives(10);
                    PlayerBase::setData($sender->getName(), "lives_claimed", true);

                    CrateManager::giveKey($sender, "Common", 20);
                    CrateManager::giveKey($sender, "Cow", 15);
                    CrateManager::giveKey($sender, "Legend", 8);
                    CrateManager::giveKey($sender, "Partner", 6);

                    Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
                } catch(\Exception $exception){
                    $sender->sendMessage($exception->getMessage());
                }
            }else{
                $sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
        if($sender->hasPermission("YouTuber.reclaim.use")){
            if($sender->getTimeReclaimRemaining() < time()){
                try {
                    $sender->resetReclaimTime();

                    if(!PlayerBase::getData($sender->getName())->get("lives_claimed")) $sender->setLives(6);
                    PlayerBase::setData($sender->getName(), "lives_claimed", true);

                    CrateManager::giveKey($sender, "Common", 20);
                    CrateManager::giveKey($sender, "Cow", 15);
                    CrateManager::giveKey($sender, "Legend", 10);
                    CrateManager::giveKey($sender, "Partner", 8);

                    Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
                } catch(\Exception $exception){
                    $sender->sendMessage($exception->getMessage());
                }
            }else{
                $sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
        if($sender->hasPermission("Famous.reclaim.use")){
            if($sender->getTimeReclaimRemaining() < time()){
                try {
                    $sender->resetReclaimTime();

                    if(!PlayerBase::getData($sender->getName())->get("lives_claimed")) $sender->setLives(8);
                    PlayerBase::setData($sender->getName(), "lives_claimed", true);

                    CrateManager::giveKey($sender, "Common", 25);
                    CrateManager::giveKey($sender, "Cow", 20);
                    CrateManager::giveKey($sender, "Legend", 15);
                    CrateManager::giveKey($sender, "Partner", 8);

                    Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
                } catch(\Exception $exception){
                    $sender->sendMessage($exception->getMessage());
                }
            }else{
                $sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
        if($sender->hasPermission("Xtreme.reclaim.use")||$sender->hasPermission("MiniYT.reclaim.use")||$sender->hasPermission("Streamer.reclaim.use")){
            if($sender->getTimeReclaimRemaining() < time()){
                try {
                    $sender->resetReclaimTime();

                    if(!PlayerBase::getData($sender->getName())->get("lives_claimed")) $sender->setLives(5);
                    PlayerBase::setData($sender->getName(), "lives_claimed", true);

                    CrateManager::giveKey($sender, "Common", 10);
                    CrateManager::giveKey($sender, "Cow", 8);
                    CrateManager::giveKey($sender, "Legend", 4);

                    Loader::getInstance()->getServer()->broadcastMessage(str_replace(["&", "{playerName}", "{rank}"], ["§", $sender->getName(), $sender->getRank()], Loader::getConfiguration("messages")->get("player_reclaim_correctly")));
                } catch(\Exception $exception){
                    $sender->sendMessage($exception->getMessage());
                }
            }else{
                $sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getTimeReclaimRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
            }
        }
    }
}
?>