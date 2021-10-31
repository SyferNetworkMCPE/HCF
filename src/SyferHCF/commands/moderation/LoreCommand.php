<?php


namespace SyferHCF\commands\moderation;


use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use SyferHCF\player\Player;
use SyferHCF\Loader;
use pocketmine\utils\TextFormat as TE;

class LoreCommand extends PluginCommand {

    public function __construct(){
        parent::__construct("lore", Loader::getInstance());
        parent::setDescription("rename lore item hand");
        $this->setPermission("lore.command.use");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {

        if(!$sender instanceof Player) return;
        if(!$this->testPermission($sender)){
            return;
        }

        if(empty($args[0])){
            return;
        }
        $item = $sender->getInventory()->getItemInHand();

        if($args[0] === "clear") {
            $item->setLore([]);
            return;
        }

        $item->setLore([str_replace(["&", "{line}"], ["§", TE::EOL], implode(" ", $args))]);
    }
}
