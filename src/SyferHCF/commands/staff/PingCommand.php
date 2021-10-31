<?php

namespace SyferHCF\commands\staff;

use SyferHCF\Loader;

use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class PingCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * PingCommand Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(){
        parent::__construct("ping", Loader::getInstance());
	}
	
	public function execute(CommandSender $sender, string $label, array $args){
		if(!$sender instanceof Player){
			$sender->sendMessage(TE::RED."Use this command in the game!");
			return;
		}
		if(isset($args[0])){
			$jug = $sender->getServer()->getPlayer($args[0]);
			if($jug != null){
				unset($args[0]);
				$sender->sendMessage(TE::GRAY."Ping of the player ".TE::DARK_PURPLE.$jug->getName().TE::GRAY." is of ".TE::LIGHT_PURPLE.$jug->getPing().TE::GRAY." ms");
			}else{
				$sender->sendMessage(TE::RED."The player you are entering is not connected!");
			}
		}else{
			$sender->sendMessage(TE::GRAY."You ping player ".TE::DARK_PURPLE.$sender->getName().TE::GRAY." is of ".TE::LIGHT_PURPLE.$sender->getPing().TE::GRAY." ms");
		}
	}
}
