<?php

namespace SyferHCF\commands\moderation;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use SyferHCF\provider\MysqlProvider;

use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\utils\TextFormat as TE;

class RanksCommand extends PluginCommand {
	
	protected $listRanks = ["Member", "Member_COL", "Member_ARG", "Member_ESP", "Member_MEX", "Owner", "Owner_666", "Owner_COL", "Owner_MEX", "Owner_ARG", "Owner_ESP", "Co-Owner", "Co-Owner_COL", "Co-Owner_MEX", "Co-Owner_ESP", "Co-Owner_ARG", "PlataformAdmin", "PlataformAdmin_COL", "PlataformAdmin_MEX", "PlataformAdmin_ESP", "PlataformAdmin_ARG", "Developer", "Developer_COL", "Developer_MEX", "Developer_ESP", "Developer_ARG", "SrAdmin", "SrAdmin_COL", "SrAdmin_MEX", "SrAdmin_ESP", "SrAdmin_ARG", "Admin", "Admin_COL", "Admin_MEX", "Admin_ESP", "Admin_ARG", "JrAdmin", "JrAdmin_COL", "JrAdmin_MEX", "JrAdmin_ESP", "JrAdmin_ARG", "SrMod", "SrMod_COL", "SrMod_MEX", "SrMod_ESP", "SrMod_ARG", "Mod+", "Mod+_COL", "Mod+_MEX", "Mod+_ESP", "Mod+_ARG", "Mod", "Mod_COL", "Mod_MEX", "Mod_ESP", "Mod_ARG", "Trial-Mod", "Trial-Mod_COL", "Trial-Mod_MEX", "Trial-Mod_ESP", "Trial-Mod_ARG", "Partner", "Partner_COL", "Partner_MEX", "Partner_ESP", "Partner_ARG", "Refys+", "Refys+_COL", "Refys+_MEX", "Refys+_ESP", "Refys+_ARG", "Refys", "Refys_COL", "Refys_MEX", "Refys_ESP", "Refys_ARG", "Poison", "Poison_COL", "Poison_MEX", "Poison_ESP", "Poison_ARG", "Forest", "Forest_COL", "Forest_MEX", "Forest_ESP", "Forest_ARG", "MiniYT", "MiniYT_COL", "MiniYT_MEX", "MiniYT_ESP", "MiniYT_ARG", "YouTuber", "YouTuber_COL", "YouTuber_MEX", "YouTuber_ESP", "YouTuber_ARG", "Famous", "Famous_COL", "Famous_MEX", "Famous_ESP", "Famous_ARG", "Booster", "Booster_COL", "Booster_MEX", "Booster_ESP", "Booster_ARG", "BoosterCloned", "BoosterCloned_COL", "BoosterCloned_MEX", "BoosterCloned_ESP", "BoosterCloned_ARG"];
	
	/**
	 * RanksCommand Constructor.
	 */
	public function __construct(){
		parent::__construct("ranks", Loader::getInstance());
		parent::setDescription("handle give , remove , create , list ranks");
	}
	
	/**
	 * @param CommandSender $sender
	 * @param String $label
	 * @param Array $args
	 * @return void
	 */
	public function execute(CommandSender $sender, String $label, Array $args) : void {
		if(!$sender->hasPermission("ranks.command.use")){
            $sender->sendMessage(TE::RED."You have not permissions to use this command");
            return;
        }
        if(empty($args)){
			$sender->sendMessage(TE::RED."Use: /{$label} help (see list of commands)");
			return;
		}
		$connection = MysqlProvider::getDataBase();
		switch($args[0]){
			case "setrank":
				if(!$sender->hasPermission("ranks.command.use")){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty($args[1])||empty($args[2])){
					$sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: playerName] [string: rankName]");
					return;
				}
				if(!is_string($args[1])||!is_string($args[2])){
					$sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_not_is_string")));
					return;
				}
				if(!in_array($args[2], $this->listRanks)){
					$sender->sendMessage(TE::RED."Rank {$args[2]} not exists!");
					return;
				}
				$queryTo = $connection->query("SELECT * FROM players_data_ranks WHERE player_name = '$args[1]';");
				$result = $queryTo->fetch_array(MYSQLI_ASSOC);
				if(empty($result)){
					$connection->query("INSERT INTO players_data_ranks(player_name, rank_id) VALUES ('$args[1]', '$args[2]');");
				}else{
                    $connection->query("UPDATE players_data_ranks SET rank_id = '$args[2]' WHERE player_name = '$args[1]';");
                }
			break;
            case "remove":
                if(!$sender->hasPermission("ranks.command.use")){
                    $sender->sendMessage(TE::RED."You have not permissions to use this command");
                    return;
                }
                if(empty($args[1])){
                    $sender->sendMessage(TE::RED."Use: /{$label} {$args[0]} [string: playerName]");
                    return;
                }
                $connection->query("DELETE FROM players_data_ranks WHERE player_name = '$args[1]';");
            break;
			case "list":
				if(!$sender->hasPermission("ranks.command.use")){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				if(empty($args[1])){
					$sender->sendMessage(TE::RED."Use: /ranks {$args[0]} [string: rankName]");
					return;
				}
				if(!in_array($args[1], $this->listRanks)){
					$sender->sendMessage(TE::RED."Rank {$args[1]} not exists!");
					return;
				}
                $queryTo = $connection->query("SELECT * FROM players_data_ranks WHERE rank_id = '$args[1]';");
				while($result = $queryTo->fetch_array(MYSQLI_ASSOC)){
					$sender->sendMessage(TE::GREEN."List of users with rank {$args[1]} ".TE::YELLOW.$result["player_name"]);
				}
			break;
			case "help":
            case "?":
				if(!$sender->hasPermission("ranks.command.use")){
					$sender->sendMessage(TE::RED."You have not permissions to use this command");
					return;
				}
				$sender->sendMessage(
				TE::YELLOW."/{$label} setrank [string: playerName] [string: rankName] ".TE::GRAY."(To place a new rank on a player in db)"."\n".
                TE::YELLOW."/{$label} remove [string: playerName] ".TE::GRAY."(To remove a player from the db)"."\n".
                TE::YELLOW."/{$label} list [string: rankName] ".TE::GRAY."(Find all players with the specified rank in the db)"
                );
			break;
		}
	}
}

?>
