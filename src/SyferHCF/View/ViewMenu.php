<?php

namespace SyferHCF\View;

use libs\muqsit\invmenu\InvMenu;
use pocketmine\inventory\transaction\action\SlotChangeAction;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\item\ItemIds;
use pocketmine\utils\TextFormat as TE;
use SyferHCF\Loader;
use SyferHCF\player\Player;

class ViewMenu {

    protected $menu;

    protected $slot = [];

    public function sendMenu(Player $player){
        try {
            $menu = InvMenu::create(InvMenu::TYPE_DOUBLE_CHEST);
            $antifall = Item::get(ItemIds::STRING, 0, 1);
            $antifall->setCustomName(TE::GREEN . TE::BOLD . "AntiFall");
            $antifall->setLore([
                TE::GRAY . "by using this special item I'll rise\n§7to heaven for 15 seconds quickly.\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $antifall->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));

            $antitrapper = Item::get(ItemIds::BED, 0, 1);
            $antitrapper->setCustomName(TE::GREEN . TE::BOLD . "AntiTrapper");
            $antitrapper->setLore([
                TE::GRAY . "when you hit the player, they cannot\n§7place blocks for 20 seconds.\n§l§6Uses Left§r§f: §d5\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);

            $cocaine = Item::get(ItemIds::SUGAR, 0, 1);
            $cocaine->setCustomName(TE::GREEN . TE::BOLD . "Cocaine");
            $cocaine->setLore([
                TE::GRAY . "can get speed 4 for yourself.\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $cocaine->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));

            $closecall = Item::get(ItemIds::COOKIE, 0, 1);
            $closecall->setCustomName(TE::GREEN . TE::BOLD . "Close Call");
            $closecall->setLore([
                TE::GRAY . "when using this item you will receive strength 2 and regeneration 5\n§7for 15 seconds to eliminate the enemy.\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $closecall->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));

            $effectsbard = Item::get(ItemIds::FIRE, 0, 1);
            $effectsbard->setCustomName(TE::GREEN . TE::BOLD . "EffectsBard");
            $effectsbard->setLore([
                TE::GRAY . "by using this item you will receive force 2, resistance 3, speed2, jump 3 for 10 seconds.\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $effectsbard->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));

            $eggports = Item::get(ItemIds::EGG, 0, 1);
            $eggports->setCustomName(TE::GREEN . TE::BOLD . "EggPorts");
            $eggports->setLore([
                TE::GRAY . "launch it to the enemy between a radio of 7 blocks\n§7and the enemy would change from position with you\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $eggports->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));

            $fireworks = Item::get(ItemIds::FIREWORKS, 0, 1);
            $fireworks->setCustomName(TE::GREEN . TE::BOLD . "Firework");
            $fireworks->setLore([
                TE::GRAY . "use the item and rise to the sky with power\n§7you will have the fall damage ever\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $fireworks->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $invisibility = Item::get(ItemIds::DYE, 0, 1);
            $invisibility->setCustomName(TE::GREEN . TE::BOLD . "Invisibility");
            $invisibility->setLore([
                TE::GRAY . "If you use this item you will receive invisibility\n§7for 3 minutes, it does not hide armor\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $invisibility->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $knockback = Item::get(ItemIds::BLAZE_ROD, 0, 1);
            $knockback->setCustomName(TE::GREEN . TE::BOLD . "Knockback");
            $knockback->setLore([
                TE::GRAY . "enchantment knockback 2 to ward off enemies or throw\n§7them from high places for whatever you wan.\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $knockback->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $loggerbait = Item::get(ItemIds::SPAWN_EGG, 0, 1);
            $loggerbait->setCustomName(TE::GREEN . TE::BOLD . "Logger Bait");
            $loggerbait->setLore([
                TE::GRAY . "put it in the place where you want to\n§7simulate your disconnection from the server\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $loggerbait->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $ninjashear = Item::get(ItemIds::SHEARS, 0, 1);
            $ninjashear->setCustomName(TE::GREEN . TE::BOLD . "NinjaShear");
            $ninjashear->setLore([
                TE::GRAY . "teleport you up to the last player who has stuck you.\n§l§6Uses Left§r§f: §d5\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $ninjashear->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $nopotions = Item::get(ItemIds::REDSTONE, 0, 1);
            $nopotions->setCustomName(TE::GREEN . TE::BOLD . "NoPotions");
            $nopotions->setLore([
                TE::GRAY . "gives you high level regeneration effect almost does not\n§7lower your life you can save potions.\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $nopotions->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $potioncounter = Item::get(ItemIds::GLASS_BOTTLE, 0, 1);
            $potioncounter->setCustomName(TE::GREEN . TE::BOLD . "PotionCounter");
            $potioncounter->setLore([
                TE::GRAY . "makes an account of the enemy's\n§7positions in your inventory and your enderchest.\n§l§6Uses Left§r§f: §d5\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $potioncounter->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $rageball = Item::get(ItemIds::FIRE_CHARGE, 0, 1);
            $rageball->setCustomName(TE::GREEN . TE::BOLD . "RageBall");
            $rageball->setLore([
                TE::GRAY . "when using this item you will receive the effects\n§7of strength 2 for 15 seconds and slowness 3 for 10 seconds\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $rageball->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $refill = Item::get(ItemIds::POPPY, 0, 1);
            $refill->setCustomName(TE::GREEN . TE::BOLD . "Refill");
            $refill->setLore([
                TE::GRAY . "by clicking you will receive several regeneration potions.\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $refill->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $re = Item::get(ItemIds::FLINT, 0, 1);
            $re->setCustomName(TE::GREEN . TE::BOLD . "RemoveEffects");
            $re->setLore([
                TE::GRAY . "remove your effects, not remove customenchants.\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $re->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $resetitems = Item::get(ItemIds::DIAMOND, 0, 1);
            $resetitems->setCustomName(TE::GREEN . TE::BOLD . "ResetItems");
            $resetitems->setLore([
                TE::GRAY . "using this item will remove the cooldown from\n§7specialitems, not all cooldowns\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $resetitems->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $resistance = Item::get(ItemIds::IRON_INGOT, 0, 1);
            $resistance->setCustomName(TE::GREEN . TE::BOLD . "Resistance");
            $resistance->setLore([
                TE::GRAY . "when you use it you receive resistance 3 for 15 seconds\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $resistance->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $secondchance = Item::get(ItemIds::GHAST_TEAR, 0, 1);
            $secondchance->setCustomName(TE::GREEN . TE::BOLD . "Remove Pearl");
            $secondchance->setLore([
                TE::GRAY . "can remove the cooldown from your enderpearl\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $secondchance->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $stormbreaker = Item::get(ItemIds::GOLD_AXE, 0, 1);
            $stormbreaker->setCustomName(TE::GREEN . TE::BOLD . "StormBreaker");
            $stormbreaker->setLore([
                TE::GRAY . "remove the enemy's helmet after 3 seconds.\n§l§6Uses Left§r§f: §d5\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $stormbreaker->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $strength = Item::get(ItemIds::BLAZE_POWDER, 0, 1);
            $strength->setCustomName(TE::GREEN . TE::BOLD . "Strength");
            $strength->setLore([
                TE::GRAY . "when you use it you receive strength 2 for 15 seconds\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $strength->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $debuff = Item::get(462, 0, 1);
            $debuff->setCustomName(TE::GREEN . TE::BOLD . "Debuff");
            $debuff->setLore([
                TE::GRAY . "if you genus the enemy 3 hits you will receive\n§7poison 2 for 15 seconds and blindness 3 for 10 seconds\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $debuff->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $guardian = Item::get(397, 0, 1);
            $guardian->setCustomName(TE::GREEN . TE::BOLD . "Guardian Angel");
            $guardian->setLore([
                TE::GRAY . "use the item and it will be activated when you have 1 heart when you activate \n§7it will give you all the life back and force 1 for 10 seconds\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $guardian->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            $exotic = Item::get(106, 0, 1);
            $exotic->setCustomName(TE::GREEN . TE::BOLD . "Exotic Plant");
            $exotic->setLore([
                TE::GRAY . "hold 3 hits to the enemy with\n§7this and will receive slowness 4 for 10 seconds\n\n§aAvailable for purchase at §csyferhcf.tebex.io§a!"
            ]);
            $exotic->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING), 3));
            
            

            $menu->getInventory()->setItem(1, $antifall);
            $menu->getInventory()->setItem(2, $antitrapper);
            $menu->getInventory()->setItem(3, $cocaine);
            $menu->getInventory()->setItem(4, $closecall);
            $menu->getInventory()->setItem(5, $effectsbard);
            $menu->getInventory()->setItem(6, $eggports);
            $menu->getInventory()->setItem(7, $fireworks);
            $menu->getInventory()->setItem(8, $invisibility);
            $menu->getInventory()->setItem(9, $knockback);
            $menu->getInventory()->setItem(10, $loggerbait);
            $menu->getInventory()->setItem(11, $ninjashear);
            $menu->getInventory()->setItem(12, $nopotions);
            $menu->getInventory()->setItem(13, $potioncounter);
            $menu->getInventory()->setItem(14, $rageball);
            $menu->getInventory()->setItem(15, $refill);
            $menu->getInventory()->setItem(16, $re);
            $menu->getInventory()->setItem(17, $resetitems);
            $menu->getInventory()->setItem(18, $resistance);
            $menu->getInventory()->setItem(19, $secondchance);
            $menu->getInventory()->setItem(20, $stormbreaker);
            $menu->getInventory()->setItem(0, $strength);
            $menu->setName(TE::BOLD . TE::LIGHT_PURPLE . "PartnerItems List");
            $menu->setListener(function (Player $player, Item $item, Item $itemClickedWith, SlotChangeAction $action) {
                if ($item->getCustomName() === TE::DARK_RED . TE::BOLD . "Builder") {
                    $this->getView("AntiFall", $player);
                }
                if ($item->getCustomName() === TE::GRAY . TE::BOLD . "Starter") {
                    $this->getView("Starter", $player);
                }
                if ($item->getCustomName() === TE::LIGHT_PURPLE . TE::BOLD . "Archer") {
                    $this->getView("Archer", $player);
                }
                if ($item->getCustomName() === TE::GOLD . TE::BOLD . "Bard") {
                    $this->getView("Bard", $player);
                }
                if ($item->getCustomName() === TE::AQUA . TE::BOLD . "Diamond") {
                    $this->getView("Diamond", $player);
                }
                if ($item->getCustomName() === TE::GREEN . TE::BOLD . "Rogue") {
                    $this->getView("Rogue", $player);
                }
                if ($item->getCustomName() === TE::BLUE . TE::BOLD . "Miner") {
                    $this->getView("Miner", $player);
                }
                if ($item->getCustomName() === TE::GREEN . TE::BOLD . "Mercury") {
                    $this->getView("Mercury", $player);
                }
                if ($item->getCustomName() === TE::OBFUSCATED."||".TE::RESET.TE::GOLD . TE::BOLD . " Knight ".TE::RESET.TE::OBFUSCATED."||") {
                    $this->getView("knight", $player);
                }
                if ($item->getCustomName() === TE::OBFUSCATED."||".TE::RESET.TE::DARK_PURPLE . TE::BOLD . " Master ".TE::RESET.TE::OBFUSCATED."||") {
                    $this->getView("Master", $player);
                }
                if ($item->getCustomName() === TE::GOLD . TE::BOLD . "Brewer") {
                    $this->getView("Brewer", $player);
                }
                if($item->getCustomName() === TE::DARK_GREEN. TE::BOLD."Mage"){
                    $this->getView("Mage", $player);
                }
                if($item->getCustomName() === TE::DARK_PURPLE . TE::BOLD."Refill"){
                    $this->getView("Refill", $player);
                }
            });
            $menu->send($player);
        } catch (\Exception $exception) {

        }
    }
 }
