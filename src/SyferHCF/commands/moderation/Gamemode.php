<?php

namespace SyferHCF\commands\moderation;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\utils\TextFormat as TE;
use SyferHCF\Loader;
use SyferHCF\player\Player;

class Gamemode extends PluginCommand {

    /**
	 * Gamemode Constructor.
	 */
	
	public function __construct(){
		parent::__construct("gamemode", Loader::getInstance());
		parent::setAliases(["gm"]);
		parent::setDescription("change gamemode");
		parent::setPermission("gamemode.use");
	}

    public function execute(CommandSender $sender, string $commandLabel, array $args){
        if(!$sender instanceof Player){
            return;
        }
        if(!$this->testPermission($sender)){
            return;
        }
        if(empty($args[0])){
            $sender->sendMessage(TE::RED."Correct usage: /gamemode <survival|creative|aventure>");
            return;
        }
        switch($args[0]){
            case "s":
            case "0":
            case "survival":
                $sender->setGamemode(Player::SURVIVAL);
                $sender->sendMessage(TE::GRAY."Your gamemode has been updated to ".TE::LIGHT_PURPLE."Survival");
                break;
                break;
                break;
            case "c":
            case "1":
            case "creative":
                $sender->setGamemode(Player::CREATIVE);
                $sender->sendMessage(TE::GRAY."Your gamemode has been updated to ".TE::LIGHT_PURPLE."Creative");
                break;
                break;
                break;
            case "a":
            case "2":
            case "aventure":
                $sender->setGamemode(Player::ADVENTURE);
                $sender->sendMessage(TE::GRAY."Your gamemode has been updated to ".TE::LIGHT_PURPLE."Adventure");
                break;
                break;
                break;
            case "e":
            case "3":
            case "spectator":
                $sender->setGamemode(Player::SPECTATOR);
                $sender->sendMessage(TE::GRAY."Your gamemode has been updated to ".TE::LIGHT_PURPLE."Spectator");
                break;
                break;
                break;

        }
    }
}
