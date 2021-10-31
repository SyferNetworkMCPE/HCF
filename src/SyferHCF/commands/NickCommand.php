<?php

namespace SyferHCF\commands;

use SyferHCF\Loader;
use pocketmine\Player;
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class NickCommand extends PluginCommand {
  
  public function __construct(){
    parent::__construct("nick", Loader::getInstance());
    $this->setDescription("Manage the name of users");
  }
  public function execute(CommandSender $player, String $label, Array $args){
      if(!$player instanceof Player) return;

    if(!$player->hasPermission("nick.permission")) {
      $player->sendMessage(TE::RED."You don't have permissions");
      $player->setDisplayName($player->getName());
      return;
    }
    $name = implode(" ", $args);
    if($name == "reset" or $name == "off"){
      $player->setDisplayName($player->getName());
      $player->sendMessage("§aSuccessfully Reset Your Nickname.");
      return;
		}
		$player->$player->getName($name);
		$player->sendMessage("§aYour Nickname Has Been Set To " . TE::LIGHT_PURPLE . $name);
		return;
  }
}
