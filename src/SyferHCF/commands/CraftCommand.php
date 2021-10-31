<?php

namespace SyferHCF\commands;

use pocketmine\inventory\CraftingGrid;
use SyferHCF\Loader;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Player;
use pocketmine\network\mcpe\protocol\types\WindowTypes;
use pocketmine\block\Block;
use pocketmine\network\mcpe\protocol\ContainerOpenPacket;

class CraftCommand extends PluginCommand {
  
  public function __construct(){
    parent::__construct("craft", Loader::getInstance());
    $this->setDescription("Portable work table");
  }
  
  public function sendCraftingTable(Player $player)
    {
        $block1 = Block::get(Block::CRAFTING_TABLE);
        $block1->x = (int)floor($player->x);
        $block1->y = (int)floor($player->y) + 4;
        $block1->z = (int)floor($player->z);
        $block1->level = $player->getLevel();
        $block1->level->sendBlocks([$player], [$block1]);
    }
    
    public function execute(CommandSender $sender, String $label, Array $args) : void {

        if($sender->hasPermission("craft.command.use")) if($sender instanceof Player){
            $this->sendCraftingTable($sender);
            $sender->setCraftingGrid(new CraftingGrid($sender, CraftingGrid::SIZE_BIG));
            if(!array_key_exists($windowId = Player::HARDCODED_CRAFTING_GRID_WINDOW_ID, $sender->openHardcodedWindows)){
                $pk = new ContainerOpenPacket();
                $pk->windowId = $windowId;
                $pk->type = WindowTypes::WORKBENCH;
                $pk->x = $sender->getFloorX();
                $pk->y = $sender->getFloorY() + 4;
                $pk->z = $sender->getFloorZ();
                $sender->sendDataPacket($pk);
                $sender->openHardcodedWindows[$windowId] = true;

            }

        }
      
    }
  
}
