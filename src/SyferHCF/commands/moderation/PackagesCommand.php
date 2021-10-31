<?php

namespace SyferHCF\commands\moderation;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use SyferHCF\packages\PackageManager;

use pocketmine\utils\TextFormat as TE;
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\Server;

use libs\muqsit\invmenu\InvMenu;

class PackagesCommand extends PluginCommand {

    /**
     * PackagesCommand Constructor.
     */
    public function __construct(){
        parent::__construct("package", Loader::getInstance());
        parent::setDescription("Manager of Partner Packages");
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
        if(empty($args)){
			$sender->sendMessage(TE::RED."Use: /{$label} help (see list of commands)");
			return;
        }
        switch($args[0]){
            case "create":
                if(!$sender->isOp()){
                    $sender->sendMessage(TE::RED."You have not permissions to use this command");
                    return;
                }
                if(PackageManager::isPackage()){
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("package_alredy_exists")));
                    return;
                }
                $packageData = [
                    "contents" => $sender->getInventory()->getContents(),
                ];
                PackageManager::createPackage($packageData);
                $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("package_create_correctly")));
            break;
            case "delete":
                if(!$sender->isOp()){
                    $sender->sendMessage(TE::RED."You have not permissions to use this command");
                    return;
                }
                if(!PackageManager::isPackage()){
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("package_not_exists")));
                    return;
                }
                PackageManager::removePackage();
                $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("package_delete_correctly")));
            break;
            case "edit":
                if(!$sender->isOp()){
                    $sender->sendMessage(TE::RED."You have not permissions to use this command");
                    return;
                }
                if(empty($args[1])){
					$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: args]");
					return;
				}
                switch($args[1]){
                    case "items":
                        $package = PackageManager::getPackage();
						$package->setItems($sender->getInventory()->getContents());
						$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("package_edit_items_correctly")));
                    break;
                }
            break;
            case "give":
                if(!$sender->isOp()){
                    $sender->sendMessage(TE::RED."You have not permissions to use this command");
                    return;
                }
                $amount = 1;
                if(!empty($args[1])){
                    $amount = $args[1];
                }
                if(!is_numeric($args[1])){
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_is_numeric")));
                    return;
                }
                PackageManager::givePackage($sender, $amount);
                $sender->sendMessage(str_replace(["&", "{amount}"], ["§", $amount], Loader::getConfiguration("messages")->get("package_give_correctly")));
            break;
            case "giveall":
            if(!$sender->isOp()){
                    $sender->sendMessage(TE::RED."You have not permissions to use this command");
                    return;
                }
                $amount = 1;
                if(!empty($args[1])){
                    $amount = $args[1];
                }
                if(!is_numeric($args[1])){
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_is_numeric")));
                    return;
                }
               foreach (Loader::getInstance()->getServer()->getOnlinePlayers() as $player) {
                    PackageManager::givePackage($player, $amount);
                    $player->sendMessage(str_replace(["&", "{amount}"], ["§", $amount], Loader::getConfiguration("messages")->get("package_give_correctly")));
                }
                break;
            case "rewards":
                if(!$sender->isOp()){
                    $sender->sendMessage(TE::RED."You have not permissions to use this command");
                    return;
                }
                $package = PackageManager::getPackage();
                if(empty($package)){
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("package_not_exists")));
                    return;
                }
                $menu = InvMenu::create(InvMenu::TYPE_CHEST);
                $menu->getInventory()->setContents($package->getItems());
                $menu->send($sender);
            break;
            case "help":
			case "?":
				if(!$sender->isOp()){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				$sender->sendMessage(
					TE::YELLOW."/{$label} create ".TE::GRAY."(To create a new package content)"."\n".
					TE::YELLOW."/{$label} delete ".TE::GRAY."(To remove a package content)"."\n".
					TE::YELLOW."/{$label} edit [string: items] ".TE::GRAY."(To edit the package content)"."\n".
					TE::YELLOW."/{$label} give [int: amount] ".TE::GRAY."(To give package amount)"."\n".
					TE::YELLOW."/{$label} rewards ".TE::GRAY."(To see package content)"
				);
			break;
            default:
                $sender->sendMessage(TE::RED."Unknown command. Try /help for a list of commands");
            break;
        }
    }
}

?>
