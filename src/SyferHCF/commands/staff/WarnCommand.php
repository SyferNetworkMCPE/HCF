<?php

namespace SyferHCF\commands\staff;

use SyferHCF\Loader;
use SyferHCF\utils\Data;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\{Config, TextFormat as TE};
use pocketmine\Player;

class WarnCommand extends PluginCommand {

    /** @var Loader */
    protected $plugin;
    
    /**
     * WarnCommand Constructor.
     * @param Loader $plugin
     */
    public function __construct(){
        parent::__construct("warn", Loader::getInstance());
        $this->setDescription("warns for player");
		$this->setPermission("warn.command.use");
    }

    /**
     * @param CommandSender $sender
     * @param String $commandLabel
     * @param Array $args
     * @return bool|mixed
     */
	public function execute(CommandSender $sender, String $commandLabel, Array $args){
        if(!$sender->hasPermission("warn.command.use")){
            $sender->sendMessage(TE::RED."You have not permissions to use this command");
            return;
        }
        if(!isset($args[0])){
            $sender->sendMessage(TE::RED."Usage: /warn [string: target] [string: reason]");
            return;
        }
        if($args[0] === "remove"||$args[0] === "delete"){
            if(Loader::getInstance()->getServer()->getPlayer($args[1]) instanceof Player){
                Data::deleteWarn(Loader::getInstance()->getServer()->getPlayer($args[1])->getName(), $sender, true);
                $sender->sendMessage(TE::GRAY."The last warning of ".TE::BOLD.TE::LIGHT_PURPLE.Loader::getInstance()->getServer()->getPlayer($args[1])->getName().TE::RESET.TE::GRAY." was removed from the server!");
            }else{
                Data::deleteWarn($args[1], $sender, true);
                $sender->sendMessage(TE::GRAY."The last warning of ".TE::BOLD.TE::LIGHT_PURPLE.$args[1].TE::RESET.TE::GRAY." was removed from the server!");
            }
        }else{
            if(Loader::getInstance()->getServer()->getPlayer($args[0]) instanceof Player){
                $argument = implode(" ", $args);
                $exploded = explode(" ", $argument);
                //TODO:
                unset($exploded[0]);
                $reason = implode(" ", $exploded);
                Data::registerWarn(Loader::getInstance()->getServer()->getPlayer($args[0])->getName(), $sender->getName(), $reason);
                Loader::getInstance()->getServer()->broadcastMessage(Loader::KIDS.TE::BOLD.TE::LIGHT_PURPLE.Loader::getInstance()->getServer()->getPlayer($args[0])->getName().TE::RESET.TE::GRAY." was warned by ".TE::BOLD.TE::DARK_PURPLE.$sender->getName().TE::RESET.TE::GRAY." reason ".TE::BOLD.TE::LIGHT_PURPLE.$reason);
                $sender->sendMessage(Loader::KIDS.TE::GRAY."You correctly warned ".TE::BOLD.TE::LIGHT_PURPLE.Loader::getInstance()->getServer()->getPlayer($args[0])->getName().TE::RESET.TE::GRAY." for the reason of ".TE::BOLD.TE::LIGHT_PURPLE.$reason);
            }else{
                $argument = implode(" ", $args);
                $exploded = explode(" ", $argument);
                //TODO:
                unset($exploded[0]);
                $reason = implode(" ", $exploded);
                Data::registerWarn($args[0], $sender->getName(), $reason);
                Loader::getInstance()->getServer()->broadcastMessage(Loader::KIDS.TE::BOLD.TE::LIGHT_PURPLE.Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName().TE::RESET.TE::GRAY." was warned by ".TE::BOLD.TE::DARK_PURPLE.$sender->getName().TE::RESET.TE::GRAY." reason ".TE::BOLD.TE::LIGHT_PURPLE.$reason);
                $sender->sendMessage(Loader::KIDS.TE::GRAY."You correctly warned ".TE::BOLD.TE::LIGHT_PURPLE.Loader::getInstance()->getServer()->getOfflinePlayer($args[0])->getName().TE::RESET.TE::GRAY." for the reason of ".TE::BOLD.TE::LIGHT_PURPLE.$reason);
            }
        }
    }
}

?>
