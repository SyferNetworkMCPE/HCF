<?php

namespace SyferHCF\commands\moderation;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\entity\Creature;
use pocketmine\entity\Human;
use pocketmine\entity\object\ExperienceOrb;
use pocketmine\entity\object\ItemEntity;

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};

class ClearEntitysCommand extends PluginCommand {
	
	/**
	 * ClearEntitysCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("clearentitys", Loader::getInstance());
		parent::setDescription("eraser entities the world");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
     * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		if(!$sender->isOp()){
			$sender->sendMessage(TE::RED."You have not permissions to use this command");
			return;
		}
		foreach(Loader::getInstance()->getServer()->getLevels() as $level){
            foreach($level->getEntities() as $entity){
                if($entity instanceof ItemEntity){
                    $entity->close();
                }elseif($entity instanceof Creature && !$entity instanceof Human){
                    $entity->close();
                }elseif($entity instanceof ExperienceOrb){
                    $entity->close();
                }
            }
        }
        Loader::getInstance()->getServer()->broadcastMessage("§d[Server] Se han borrado todas las entidades :D");
	}
}

?>
