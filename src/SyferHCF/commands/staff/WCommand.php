<?php

namespace SyferHCF\commands\staff;

use SyferHCF\Loader;

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;

class WCommand extends PluginCommand {
	
	/** @var Loader */
	protected $plugin;
	
	/**
	 * WCommand Constructor.
	 * @param Loader $plugin
	 */
	public function __construct(){
        parent::__construct("w", Loader::getInstance());
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $commandLabel
	 * @param Array $args
	 * @return bool|mixed
	 */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
		if(!isset($args[0])||!isset($args[1])){
			$sender->sendMessage(TE::RED."Usage: /w <playerName> <message>");
			return;
		}
		$player = Loader::getInstance()->getServer()->getPlayer($args[0]);
		if($player === null){
			$sender->sendMessage(TE::RED."The player you are looking for does not exist");
			return;
		}
		unset($args[0]);
		$message = implode(" ", $args);
		if($player->getName() === $sender->getName()){
			$sender->sendMessage(TE::RED."You can't send me a message yourself");
			return;
		}
		$sender->sendMessage(TE::GRAY."(§5To ".$player->getName().TE::GRAY.")".TE::RESET." ".TE::WHITE.$message);
		$player->sendMessage(TE::GRAY."(§5From ".$sender->getName().TE::GRAY.")".TE::RESET." ".TE::WHITE.$message);
		foreach(Loader::getInstance()->getServer()->getOnlinePlayers() as $players){
			if($players->hasPermission("viewprivate.command.use")){
				$players->sendMessage(TE::GRAY."From ".$sender->getName()." To ".$player->getName().TE::RESET." ".TE::WHITE.$message);
			}
		}
	}
}

?>
