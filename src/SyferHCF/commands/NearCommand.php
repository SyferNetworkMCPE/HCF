<?php

namespace SyferHCF\commands;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\level\Level;
use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};

class NearCommand extends PluginCommand {

    /**
     * NearCommand Constructor.
     */
    public function __construct(){
        parent::__construct("near", Loader::getInstance());
        
        parent::setPermission("near.command.use");
        parent::setDescription("Can see the players that are close to you");
    }
    
    /**
     * @param CommandSender $sender
     * @param String $label
     * @param Array $args
     * @return void
     */
    public function execute(CommandSender $sender, String $label, Array $args) : void {
        if(!$sender->hasPermission("near.command.use")){
            $sender->sendMessage(TE::RED."You have not permissions to use this command");
            return;
        }
        $sender->sendMessage(TE::GOLD."Nearby Players: "."\n");
        foreach($sender->getLevel()->getNearbyEntities($sender->getBoundingBox()->expandedCopy(200, 200, 200)) as $player){
        	if($player instanceof Player)
            $sender->sendMessage(TE::RESET.$player->getName().TE::GRAY."(".TE::DARK_RED.(int)$player->distance($sender)."m".TE::GRAY.")"."\n");
        }
    }
}

?>
