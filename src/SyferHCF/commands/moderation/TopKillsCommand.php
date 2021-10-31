<?php

namespace SyferHCF\commands\moderation;

use SyferHCF\Loader;
use SyferHCF\entities\spawnable\{EntitysTop, Top1, Top2, Top3};
use pocketmine\command\{Command, CommandSender, PluginCommand};
use pocketmine\Player;
use pocketmine\Server;

class TopKillsCommand extends PluginCommand {

  public function __construct(){
        parent::__construct("topkill", Loader::getInstance());
        parent::setDescription("handling of topkills");
        $this->setPermission("topkillop.command.use");
    }

  /**
  * @param CommandSender $pl
  * @param string $label
  * @param array $args
  * @return mixed|void
  */
  
  public function execute(CommandSender $pl, string $label, array $args) {
    $entity = new EntitysTop();
    if ($pl instanceof Player) {
      if ($pl->isOp()) {
        if (empty($args[0])) {
          $pl->sendMessage("§cSelect a Top using /tops (1/2/3) or /tops kill");
          return false;
        }
        switch ($args[0]) {
          case '1':
            $entity->setTop1($pl);
            $pl->sendMessage("§aYou placed top number 1");
            break;
          case '2':
            $entity->setTop2($pl);
            $pl->sendMessage("§aYou placed top number 2");
            break;
          case '3':
            $entity->setTop3($pl);
            $pl->sendMessage("§aYou placed top number 3");
            break;
          case 'kill':
            foreach(Loader::getInstance()->getServer()->getDefaultLevel()->getEntities() as $entity){
              if($entity instanceof Top1){
                  $entity->kill();
              } else if ($entity instanceof Top2) {
                $entity->kill();
              } else if ($entity instanceof Top3) {
                $entity->kill();
              }
            }
            $pl->sendMessage("§aAll Loader removed correctly.");
            break;
          default:
            $pl->sendMessage("§cTop {$args[0]} not exist try again.");
            break;
        }
      } else {
        $pl->sendMessage("§cYou no have permissions to use this command.");
      }
    }
  }
}
