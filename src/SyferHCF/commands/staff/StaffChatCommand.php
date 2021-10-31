<?php

namespace SyferHCF\commands\staff;

use SyferHCF\Loader;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\command\{CommandSender, PluginCommand};
use SyferHCF\player\Player;

class StaffChatCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;

	protected $sc;
	
	/**
	 * Mute Constructor.
	 * @param 
	 */
	public function __construct(){
		parent::__construct("sc", Loader::getInstance());

		$this->setDescription("/sc [string: message]");
		$this->setPermission("sc.command.use");
	}
	
	/**
     * @param CommandSender $sender
     * @param String $commandLabel
     * @param Array $args
     * @return bool|mixed
     */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
		if(!$sender instanceof Player){
            $sender->sendMessage(TE::RED."Use this command in the game!");
            return;
        }
        if(!$sender->hasPermission("sc.command.use")){
            $sender->sendMessage(TE::RED."You have not permissions to use this command");
            return;
        }
        if(!isset($args[0])){
            if(isset($this->sc[$sender->getName()])){
                $sender->setChat(Player::PUBLIC_CHAT);
                unset($this->sc[$sender->getName()]);
                return;
            }
            $this->sc[$sender->getName()] = $sender->getName();
            $sender->setChat(Player::STAFF_CHAT);
        	return;
        }
        foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $player){
        	if($player->hasPermission("sc.command.use")){
        		$player->sendMessage(TE::GRAY."[§3StaffChat§7]".TE::GRAY."[§b".$player->getLevel()->getName()."§7]".TE::RESET." ".TE::LIGHT_PURPLE.$sender->getName().TE::WHITE.": ".TE::YELLOW.implode(" ", $args));
        	}
        }
    }
}
