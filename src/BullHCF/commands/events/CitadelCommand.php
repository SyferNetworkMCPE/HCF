<?php

namespace BullHCF\commands\events;

use BullHCF\Loader;
use BullHCF\player\Player;

use BullHCF\utils\{Time, Translator};

use BullHCF\Citadel\CitadelManager;

use BullHCF\Task\event\CitadelTask;

use BullHCF\API\System;
use BullHCF\utils\Tower;

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};

class CitadelCommand extends PluginCommand {

    /** @var Int */
    protected $taskId;

    /**
     * CitadelCommand Constructor.
     */
    public function __construct(){
        parent::__construct("citadel", Loader::getInstance());

        parent::setPermission("Citadel.command.use");
    }

    /**
     * @param CommandSender $sender
     * @param String $label
     * @param Array $args
     * @return void
     */
    public function execute(CommandSender $sender, String $label, Array $args) : void {
        if(empty($args)){
            $sender->sendMessage(TE::RED."Argument #1 is not valid for command syntax");
            return;
        }
        switch($args[0]){
            case "create":
                if(!$sender->isOp()){
                    $sender->sendMessage(TE::RED."You have not permissions to use this command");
                    return;
                }
                if(empty($args[1])){
                    $sender->addTool();
                    $sender->setInteract(true);
                }else{
                    if(CitadelManager::isCitadel($args[1])){
                        $sender->sendMessage(str_replace(["&", "{citadelName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("citadel_alredy_exists")));
                        return;
                    }
                    if(!System::isPosition($sender, 1) && !System::isPosition($sender, 2)){
                        $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_not_zone_location")));
                        return;
                    }
                    $citadelData = [
                        "name" => $args[1],
                        "levelName" => $sender->getLevel()->getFolderName(),
                        "position1" => System::getPosition($sender, 1),
                        "position2" => System::getPosition($sender, 2),
                    ];
                    CitadelManager::createCitadel($citadelData);

                    Tower::delete($sender, 1);
                    Tower::delete($sender, 2);
                    System::deletePosition($sender, 1, true);
                    System::deletePosition($sender, 2, true);
                    $sender->sendMessage(str_replace(["&", "{citadelName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("citadel_create_correctly")));
                }
                break;
            case "delete":
                if(!$sender->isOp()){
                    $sender->sendMessage(TE::RED."You have not permissions to use this command");
                    return;
                }
                if(empty($args[1])){
                    $sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: citadelName]");
                    return;
                }
                if(!CitadelManager::isCitadel($args[1])){
                    $sender->sendMessage(str_replace(["&", "{citadelName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("citadel_not_exists")));
                    return;
                }
                CitadelManager::removeCitadel($args[1]);
                $sender->sendMessage(str_replace(["&", "{citadelName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("citadel_delete_correctly")));
                break;
            case "start":
                if(!$sender->hasPermission("start.command.use")){
                    $sender->sendMessage(TE::RED."You have not permissions to use this command");
                    return;
                }
                if(empty($args[1])){
                    $sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: citadelName]");
                    return;
                }
                if(!CitadelManager::isCitadel($args[1])){
                    $sender->sendMessage(str_replace(["&", "{citadelName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("citadel_not_exists")));
                    return;
                }
                if(($citadelName = CitadelManager::CitadelIsEnabled())){
                    $citadel = CitadelManager::getCitadel($citadelName);
                    $sender->sendMessage(str_replace(["&", "{citadelName}"], ["§", $citadel->getName()], Loader::getConfiguration("messages")->get("citadel_alredy_enable")));
                    return;
                }
                if(!empty($args[2])){
                    if(!in_array(Translator::intToString($args[2]), Translator::VALID_FORMATS)){
                        $sender->sendMessage(TE::RED."The time format you enter is invalid!");
                        return;
                    }
                }
                if($sender->isOp()){
                    $this->taskId = Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new CitadelTask($args[1], !empty($args[2]) ? Translator::getStringFormatToInt(Translator::stringToInt($args[2]), $args[2]) : null), 20)->gettaskId();
                }else{
                    if($sender->getCitadelHostTimeRemaining() > time()){
                        $sender->sendMessage(str_replace(["&", "{time}"], ["§", Time::getTime($sender->getCitadelHostTimeRemaining())], Loader::getConfiguration("messages")->get("function_cooldown")));
                        return;
                    }
                    $sender->resetCitadelHostTime();
                    $this->taskId = Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new CitadelTask($args[1], null), 20)->gettaskId();
                }
                break;
            case "stop":
                if(!$sender->isOp()){
                    $sender->sendMessage(TE::RED."You have not permissions to use this command");
                    return;
                }
                if(empty($args[1])){
                    $sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: citadelName]");
                    return;
                }
                if(!CitadelManager::isCitadel($args[1])){
                    $sender->sendMessage(str_replace(["&", "{citadelName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("citadel_not_exists")));
                    return;
                }
                $citadel = CitadelManager::getCitadel($args[1]);
                if(!$citadel->isEnable()){
                    $sender->sendMessage(str_replace(["&", "{citadelName}"], ["§", $citadel->getName()], Loader::getConfiguration("messages")->get("citadel_is_not_activated")));
                    return;
                }
                $citadel->setEnable(false);
                Loader::getInstance()->getScheduler()->cancelTask($this->taskId);
                break;
            case "list":
                if(empty(CitadelManager::getCitadels())){
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("citadel_not_have_arenas")));
                    return;
                }
                $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("citadel_list_of_arenas")));
                foreach(array_values(CitadelManager::getCitadels()) as $citadel){
                    $sender->sendMessage(str_replace(["&", "{citadelName}", "{position}", "{worldName}", "{status}"], ["§", $citadel->getName(), Translator::vector3ToString($citadel->getPosition1()), $citadel->getLevel(), $citadel->isEnable() ? TE::GREEN."RUNNING" : TE::RED."IDLE"], Loader::getConfiguration("messages")->get("citadel_list")));
                }
                break;
            case "help":
            case "?":
                if(!$sender->isOp()){
                    $sender->sendMessage(TE::RED."You have not permissions to use this command");
                    return;
                }
                $sender->sendMessage(
                    TE::YELLOW."/{$label} create [string: citadelName] ".TE::GRAY."(To create a new citadel and register its positions)"."\n".
                    TE::YELLOW."/{$label} delete [string: citadelName] ".TE::GRAY."(To remove a citadel from the list)"."\n".
                    TE::YELLOW."/{$label} start [string: citadelName] ".TE::GRAY."(To start a CITADEL event)"."\n".
                    TE::YELLOW."/{$label} list  ".TE::GRAY."(To see the list of registered citadels)"
                );
                break;
            default:
                $sender->sendMessage(TE::RED."Unknown command. Try /help for a list of commands");
                break;
        }
    }
}

?>
