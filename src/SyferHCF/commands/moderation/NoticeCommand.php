<?php
declare(strict_types=1);

namespace SyferHCF\commands\moderation;

use SyferHCF\Loader;
use pocketmine\command\{CommandSender, PluginCommand};
use pocketmine\command\ConsoleCommandSender;
use pocketmine\command\utils\InvalidCommandSyntaxException;
use pocketmine\lang\TranslationContainer;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use function count;
use function implode;

class NoticeCommand extends PluginCommand{

	public function __construct(){
    parent::__construct("notice", Loader::getInstance());
    $this->setDescription("notices §d§lsyferhcf");
    $this->setPermission("notice.command");
	}

	public function execute(CommandSender $sender, string $commandLabel, array $args){
		if(!$this->testPermission($sender)){
			return true;
		}

		if(count($args) === 0){
			throw new InvalidCommandSyntaxException();
		}

		Loader::getInstance()->getServer()->broadcastMessage(new TranslationContainer(TextFormat::DARK_GRAY . "%chat.type.announcement", [$sender instanceof Player ? "§4Alert§8" : ($sender instanceof ConsoleCommandSender ? "§4Alert§8" : $sender->getName()), TextFormat::WHITE . implode(" ", $args)]));
		return true;
	}
}
