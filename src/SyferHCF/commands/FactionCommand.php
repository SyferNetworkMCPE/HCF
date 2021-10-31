<?php

namespace SyferHCF\commands;

use SyferHCF\Listeners\Faction;
use SyferHCF\{Loader, Factions};
use SyferHCF\player\{Player, PlayerBase};

use SyferHCF\utils\Time;

use SyferHCF\Task\{InvitationTask, TeleportHomeTask, TeleportStuckTask};
use SyferHCF\Task\updater\UpdaterTask;

use SyferHCF\API\System;
use SyferHCF\utils\Tower;

use pocketmine\utils\TextFormat as TE;
use pocketmine\level\Level;
use pocketmine\math\Vector3;
use pocketmine\block\Block;

use pocketmine\command\{PluginCommand, CommandSender};

class FactionCommand extends PluginCommand
{

    /**
     * FactionCommand Constructor.
     */
    public function __construct()
    {
        parent::__construct("f", Loader::getInstance());

        parent::setDescription("You can find all the commands for the factions system");
    }

    /**
     * @param CommandSender $sender
     * @param String $label
     * @param Array $args
     * @return void
     */
    public function execute(CommandSender $sender, string $label, array $args): void
    {
        $senderName = $sender->getName();
        if (empty($args)) {
            $sender->sendMessage(
                TE::YELLOW . "/{$label} create [string: factionName] " . TE::GRAY . "(With this command you can create your new faction)" . "\n" .
                TE::YELLOW . "/{$label} disband " . TE::GRAY . "(With this command you can erase your faction)" . "\n" .
                TE::YELLOW . "/{$label} leave " . TE::GRAY . "(With this command you can exit a faction)" . "\n" .
                TE::YELLOW . "/{$label} invite [string: playerName] " . TE::GRAY . "(With this command you can invite members to your faction)" . "\n" .
                TE::YELLOW . "/{$label} kick [string: playerName] " . TE::GRAY . "(Use this command to remove players from your faction)" . "\n" .
                TE::YELLOW . "/{$label} claim " . TE::GRAY . "(With this command you can claim your land)" . "\n" .
                TE::YELLOW . "/{$label} chat [string: public:faction] " . TE::GRAY . "(Select in which chat to speak)" . "\n" .
                TE::YELLOW . "/{$label} unclaim " . TE::GRAY . "(With this command you can delete the area of your claim)" . "\n" .
                TE::YELLOW . "/{$label} sethome " . TE::GRAY . "(Place the home of your faction)" . "\n" .
                TE::YELLOW . "/{$label} deposit [int: amount:all] " . TE::GRAY . "(Deposit the money in your faction)" . "\n" .
                TE::YELLOW . "/{$label} withdraw [int: amount:all] " . TE::GRAY . "(Can take money out of your faction)" . "\n" .
                TE::YELLOW . "/{$label} who [string: factionName:playerName] " . TE::GRAY . "(View enemy faction information)" . "\n" .
                TE::YELLOW . "/{$label} focus [string: factionName] " . TE::GRAY . "(To be able to focus on the indicated faction)" . "\n" .
                TE::YELLOW . "/{$label} unfocus " . TE::GRAY . "(To remove the focus from the indicated faction)"
            );
            return;
        }
        switch ($args[0]) {
            case "opclaim":
                if (!$sender->hasPermission("faction.command.use")) {
                    $sender->sendMessage(TE::RED . "You have not permissions to use this command");
                    return;
                }
                if (empty($args[1])) {
                    $sender->addTool();
                    $sender->setInteract(true);
                } else {
                    if (!System::isPosition($sender, 1) && !System::isPosition($sender, 2)) {
                        $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_not_zone_location")));
                        return;
                    }
                    if (Factions::isRegionExists($args[1])) {
                        $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_zone_exists")));
                        return;
                    }
                    if ($args[1] === "Spawn") {
                        Factions::claimRegion($args[1], $sender->getLevel()->getName(), System::getPosition($sender, 1), System::getPosition($sender, 2), Factions::SPAWN);
                        Tower::delete($sender, 1);
                        Tower::delete($sender, 2);
                        System::deletePosition($sender, 1, true);
                        System::deletePosition($sender, 2, true);
                    } else {
                        Factions::claimRegion($args[1], $sender->getLevel()->getName(), System::getPosition($sender, 1), System::getPosition($sender, 2), Factions::PROTECTION);
                        Tower::delete($sender, 1);
                        Tower::delete($sender, 2);
                        System::deletePosition($sender, 1, true);
                        System::deletePosition($sender, 2, true);
                    }
                }
                break;
            case "dtrall":
                if (!$sender->hasPermission("faction.command.use")) {
                    $sender->sendMessage(TE::RED . "You have not permissions to use this command");
                    return;
                }
                foreach (Factions::getFactions() as $factionName) {
                    Factions::setStrength($factionName, Factions::getMaxStrength($factionName));
                }
                break;
            case "help":
            case "?":
            $sender->sendMessage(TE::GOLD."/f create [string: factionName] " . TE::GRAY . "(With this command you can create your new faction)");
		    $sender->sendMessage(TE::GOLD."/f disband " . TE::GRAY . "(With this command you can erase your faction)");
		    $sender->sendMessage(TE::GOLD."/f leave " . TE::GRAY . "(With this command you can exit a faction)");
		    $sender->sendMessage(TE::GOLD."/f invite [string: playerName] " . TE::GRAY . "(With this command you can invite members to your faction)");
		    $sender->sendMessage(TE::GOLD."/f kick [string: playerName] " . TE::GRAY . "(Use this command to remove players from your faction)");
		    $sender->sendMessage(TE::GOLD."/f claim " . TE::GRAY . "(With this command you can claim your land)");
		    $sender->sendMessage(TE::GOLD."/f chat [string: public:faction] " . TE::GRAY . "(Select in which chat to speak)");
		    $sender->sendMessage(TE::GOLD."/f unclaim " . TE::GRAY . "(With this command you can delete the area of your claim)");
		    $sender->sendMessage(TE::GOLD."/f sethome " . TE::GRAY . "(Place the home of your faction)");
		    $sender->sendMessage(TE::GOLD."/f deposit [int: amount:all] " . TE::GRAY . "(Deposit the money in your faction)");
		    $sender->sendMessage(TE::GOLD."/f withdraw [int: amount:all] " . TE::GRAY . "(Can take money out of your faction)");
		    $sender->sendMessage(TE::GOLD."/f who [string: factionName:playerName] " . TE::GRAY . "(View enemy faction information)");
		    $sender->sendMessage(TE::GOLD."/f focus [string: factionName] " . TE::GRAY . "(To be able to focus on the indicated faction)");
		    $sender->sendMessage(TE::GOLD."/f unfocus " . TE::GRAY . "(To remove the focus from the indicated faction)");
		    break;
            case "freezeall":
                if (!$sender->hasPermission("faction.command.use")) {
                    $sender->sendMessage(TE::RED . "You have not permissions to use this command");
                    return;
                }
                foreach (Factions::getFactions() as $factionName) {
                    Factions::removeFreezeTime($factionName);
                }
                break;
            case "disbandall":
                if (!$sender->hasPermission("faction.command.use")) {
                    $sender->sendMessage(TE::RED . "You have not permissions to use this command");
                    return;
                }
                foreach (Factions::getFactions() as $factionName) {
                    Factions::remove($factionName);
                }
                break;
            case "forcedisband":
                if (!$sender->hasPermission("faction.command.use")) {
                    $sender->sendMessage(TE::RED . "You have not permissions to use this command");
                    return;
                }
                if (empty($args[1])) {
                    $sender->sendMessage(TE::RED . "Use: /{$label} {$args[0]} [string: factionName]");
                    return;
                }
                if (!Factions::isFactionExists($args[1])) {
                    $sender->sendMessage(str_replace(["&", "{factionName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("faction_not_exists")));
                    return;
                }
                Factions::remove($args[1]);
                break;
            case "setdtr":
                if (!$sender->hasPermission("faction.command.use")) {
                    $sender->sendMessage(TE::RED . "You have not permissions to use this command");
                    return;
                }
                if (empty($args[1]) || empty($args[2])) {
                    $sender->sendMessage(TE::RED . "Use: /{$label} {$args[0]} [string: factionName] [int: dtr]");
                    return;
                }
                if (!is_string($args[1])) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_is_string")));
                    return;
                }
                if (!is_numeric($args[2])) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_is_numeric")));
                    return;
                }
                Factions::setStrength($args[1], $args[2]);
                break;
            case "setbalance":
                if (!$sender->hasPermission("faction.command.use")) {
                    $sender->sendMessage(TE::RED . "You have not permissions to use this command");
                    return;
                }
                if (empty($args[1]) || empty($args[2])) {
                    $sender->sendMessage(TE::RED . "Use: /{$label} {$args[0]} [string: factionName] [int: balance]");
                    return;
                }
                if (!is_string($args[1])) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_is_string")));
                    return;
                }
                if (!is_numeric($args[2])) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_is_numeric")));
                    return;
                }
                Factions::setBalance($args[1], $args[2]);
                break;
            case "forceleave":
                if (!$sender->hasPermission("faction.command.use")) {
                    $sender->sendMessage(TE::RED . "You have not permissions to use this command");
                    return;
                }
                if (empty($args[1])) {
                    $sender->sendMessage(TE::RED . "Use: /{$label} {$args[0]} [string: playerName]");
                    return;
                }
                if (!is_string($args[1])) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_is_string")));
                    return;
                }
                if (Factions::getLeader($args[2]) === $args[1]) {
                    $sender->sendMessage(str_replace(["&", "{playerName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("faction_player_is_leader")));
                    return;
                }
                Factions::removeToFaction($args[1]);
                break;
            case "create":
                if (Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&", "{factionName}"], ["§", Factions::getFaction($senderName)], Loader::getConfiguration("messages")->get("sender_is_in_faction")));
                    return;
                }
                if (empty($args[1])) {
                    $sender->sendMessage(TE::RED . "Use: /{$label} {$args[0]} <factionName>");
                    return;
                }
                if (!is_string($args[1])) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_is_string")));
                    return;
                }
                if (strlen($args[1]) >= 15) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_big_name")));
                    return;
                }
                if (strlen($args[1]) < 5) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_small_name")));
                    return;
                }
                Factions::create($args[1], $sender);
                break;
            case "disband":
                if (!Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
                    return;
                }
                if (Factions::getStrength(Factions::getFaction($senderName)) < 1) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_is_raid")));
                    return;
                }
                if (Factions::getLeader(Factions::getFaction($senderName)) !== $senderName) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_is_leader")));
                    return;
                }
                Factions::remove(Factions::getFaction($senderName));
                break;
            case "leave":
                if (!Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
                    return;
                }
                if (Factions::getLeader(Factions::getFaction($senderName)) === $senderName) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_leave_of_faction_is_leader")));
                    return;
                }
                if (Factions::getStrength(Factions::getFaction($senderName)) < 1) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_is_raid")));
                    return;
                }
                $sender->sendMessage(str_replace(["&", "{factionName}"], ["§", Factions::getFaction($senderName)], Loader::getConfiguration("messages")->get("sender_leave_of_faction_correctly")));
                Factions::removeToFaction($senderName);
                break;
            case "claim":
                if (!Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
                    return;
                }
                if ((!($senderName === Factions::getLeader(Factions::getFaction($senderName)) || $senderName === Factions::getCoLeader(Factions::getFaction($senderName))))) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_is_leader")));
                    return;
                }
                if (Factions::isRegionExists(Factions::getFaction($senderName))) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_zone_exists")));
                    return;
                }
                if ($sender->isInteract()) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_is_alredy_claiming")));
                    return;
                }
                $sender->setInteract(true);
                $sender->addTool();
                $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_claim_zone_help")));
                break;
            case "unclaim":
                if ($sender->isOp() && !empty($args[1])) {
                    Factions::removeRegion($args[1]);
                    return;
                }
                if (!Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
                    return;
                }
                if ((!($senderName === Factions::getLeader(Factions::getFaction($senderName)) || $senderName === Factions::getCoLeader(Factions::getFaction($senderName))))) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_is_leader")));
                    return;
                }
                if (Factions::getStrength(Factions::getFaction($senderName)) < 1) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_is_raid")));
                    return;
                }
                if (!Factions::isRegionExists(Factions::getFaction($senderName))) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_zone_not_exists")));
                    return;
                }
                Factions::removeRegion(Factions::getFaction($senderName));
                $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_zone_delete_correctly")));
                break;
            case "map":
                if (!Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
                    return;
                }
                if (!Factions::isRegionExists(Factions::getFaction($senderName))) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_zone_not_exists")));
                    return;
                }
                if (!$sender->isViewingMap()) {
                    Factions::seeRegions($sender, Block::get(Block::COAL_BLOCK));
                    Factions::seeRegions($sender, Block::get(Block::IRON_BLOCK));
                    $sender->setViewingMap(true);
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_see_zone")));
                } else {
                    Factions::seeRegions($sender, Block::get(Block::AIR));
                    $sender->setViewingMap(false);
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_hide_zone")));
                }
                break;
            case "sethome":
            case "sethq":
                if (!Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
                    return;
                }
                if ((!($senderName === Factions::getLeader(Factions::getFaction($senderName)) || $senderName === Factions::getCoLeader(Factions::getFaction($senderName))))) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_is_leader")));
                    return;
                }
                if (Factions::getRegionName($sender) !== Factions::getFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_is_not_in_your_zone")));
                    return;
                }
                Factions::setFactionHome(Factions::getFaction($senderName), $sender->getLevel()->getFolderName(), $sender);
                foreach (Factions::getPlayers(Factions::getFaction($senderName)) as $player) {
                    $online = Loader::getInstance()->getServer()->getPlayer($player);
                    if ($online instanceof Player) {
                        $online->sendMessage(str_replace(["&", "{playerName}"], ["§", $senderName], Loader::getConfiguration("messages")->get("faction_place_home")));
                    }
                }
                break;
            case "home":
            case "hq":
                if (!Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
                    return;
                }
                if (!Factions::isHome(Factions::getFaction($senderName))) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("faction_home_not_exists")));
                    return;
                }
                if ($sender->isTeleportingHome()) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_is_in_teleport_time")));
                    return;
                }
                if ($sender->isCombatTag()) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_is_combatTag")));
                    return;
                }
                if ($sender->isInvincibility()) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_not_execute_command_with_invincibility")));
                    return;
                }
                if (Factions::isSpawnRegion($sender)) {
                    $sender->teleport(Factions::getFactionHomeLocation(Factions::getFaction($sender->getName())));
                    return;
                }
                $sender->setTeleportingHome(true);
                $sender->sendMessage(str_replace(["&", "{time}"], ["§", Loader::getDefaultConfig("Cooldowns")["Home"]], Loader::getConfiguration("messages")->get("sender_got_to_home")));
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new TeleportHomeTask($sender), 20);
                break;
            case "stuck":
                if (Factions::isSpawnRegion($sender)) {
                    return;
                }
                if ($sender->isTeleportingStuck()) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_is_in_teleport_time")));
                    return;
                }
                $sender->setTeleportingStuck(true);
                $sender->sendMessage(str_replace(["&", "{time}"], ["§", Loader::getDefaultConfig("Cooldowns")["Stuck"]], Loader::getConfiguration("messages")->get("sender_got_to_stuck")));
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new TeleportStuckTask($sender), 20);
                break;
            case "promote":
                if (!Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
                    return;
                }
                if (empty($args[1])) {
                    $sender->sendMessage(TE::RED . "/{$label} {$args[0]} <playerName>");
                    return;
                }
                if (Factions::getLeader(Factions::getFaction($senderName)) !== $senderName) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_is_leader")));
                    return;
                }
                $playerName = $args[1];
                if (Factions::getLeader(Factions::getFaction($senderName)) === $playerName) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_is_leader")));
                    return;
                }
                if (Factions::getFaction($playerName) !== Factions::getFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_kick_other_player")));
                    return;
                }
                Factions::joinToFaction($playerName, Factions::getFaction($senderName), Player::CO_LEADER);
                break;
            case "demote":
                if (!Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
                    return;
                }
                if (empty($args[1])) {
                    $sender->sendMessage(TE::RED . "/{$label} {$args[0]} <playerName>");
                    return;
                }
                if (Factions::getLeader(Factions::getFaction($senderName)) !== $senderName) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_is_leader")));
                    return;
                }
                $playerName = $args[1];
                if (Factions::getLeader(Factions::getFaction($senderName)) === $playerName) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_is_leader")));
                    return;
                }
                if (Factions::getFaction($playerName) !== Factions::getFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_kick_other_player")));
                    return;
                }
                Factions::joinToFaction($playerName, Factions::getFaction($senderName), Player::MEMBER);
                break;
            case "invite":
                if (!Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
                    return;
                }
                if ((!($senderName === Factions::getLeader(Factions::getFaction($senderName)) || $senderName === Factions::getCoLeader(Factions::getFaction($senderName))))) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_is_leader")));
                    return;
                }
                if (empty($args[1])) {
                    $sender->sendMessage(TE::RED . "Use: /{$label} {$args[0]} <playerName>");
                    return;
                }
                $player = Loader::getInstance()->getServer()->getPlayer($args[1]);
                if ($player === null) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_not_is_online")));
                    return;
                }
                if (Factions::inFaction($player->getName())) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_is_already_in_faction")));
                    return;
                }
                if ($player->getName() === $senderName) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_do_not_invite_yourself")));
                    return;
                }
                if ($player->isInvited()) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_is_already_invited")));
                    return;
                }
                $player->setInvite(true);
                $player->setCurrentInvite(Factions::getFaction($senderName));
                $player->sendMessage(str_replace(["&", "{factionName}"], ["§", Factions::getFaction($senderName)], Loader::getConfiguration("messages")->get("player_invite_correctly")));
                Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new InvitationTask($player), 20);
                break;
            case "accept":
                if (Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&", "{factionName}"], ["§", Factions::getFaction($senderName)], Loader::getConfiguration("messages")->get("sender_is_in_faction")));
                    return;
                }
                if (!$sender->isInvited()) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_has_not_pending_invitations")));
                    return;
                }
                if (Factions::getMaxPlayers($sender->getCurrentInvite()) === Loader::getDefaultConfig("FactionsConfig")["maxPlayers"]) {
                    $sender->sendMessage(str_replace(["&", "{factionName}"], ["§", $sender->getCurrentInvite()], Loader::getConfiguration("messages")->get("faction_is_full")));
                    return;
                }
                $sender->setInvite(false);
                $sender->sendMessage(str_replace(["&", "{factionName}"], ["§", $sender->getCurrentInvite()], Loader::getConfiguration("messages")->get("sender_join_faction_correctly")));
                Factions::joinToFaction($senderName, $sender->getCurrentInvite(), Player::MEMBER);
                break;
            case "deny":
                if (Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&", "{factionName}"], ["§", Factions::getFaction($senderName)], Loader::getConfiguration("messages")->get("sender_is_in_faction")));
                    return;
                }
                if (!$sender->isInvited()) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_has_not_pending_invitations")));
                    return;
                }
                $sender->setInvite(false);
                $sender->sendMessage(str_replace(["&", "{factionName}"], ["§", $sender->getCurrentInvite()], Loader::getConfiguration("messages")->get("player_decline_invitation_correctly")));
                break;
            case "kick":
                if (!Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
                    return;
                }
                if (empty($args[1])) {
                    $sender->sendMessage(TE::RED . "Use: /{$label} {$args[0]} <playerName>");
                    return;
                }
                if ((!($senderName === Factions::getLeader(Factions::getFaction($senderName)) || $senderName === Factions::getCoLeader(Factions::getFaction($senderName))))) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_is_leader")));
                    return;
                }
                $playerName = $args[1];
                if (Factions::getLeader(Factions::getFaction($senderName)) !== $senderName) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_is_leader")));
                    return;
                }
                if (!Factions::inFaction($playerName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_is_not_in_faction")));
                    return;
                }
                if (Factions::getFaction($playerName) !== Factions::getFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_kick_other_player")));
                    return;
                }
                Factions::removeToFaction($playerName);
                break;
            case "deposit":
            case "d":
                if (!Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
                    return;
                }
                if (empty($args[1])) {
                    $sender->sendMessage(TE::RED . "Use /{$label} {$args[0]} <amount:all>");
                    return;
                }
                if ($args[1] === "all") {
                    if ($sender->getBalance() === 0) {
                        $sender->sendMessage(str_replace(["&", "{money}"], ["§", $sender->getBalance()], Loader::getConfiguration("messages")->get("sender_has_not_money")));
                        return;
                    }
                    Factions::addBalance(Factions::getFaction($senderName), $sender->getBalance());
                    foreach (Factions::getPlayers(Factions::getFaction($senderName)) as $player) {
                        $online = Loader::getInstance()->getServer()->getPlayer($player);
                        if ($online instanceof Player) {
                            $online->sendMessage(str_replace(["&", "{playerName}", "{money}"], ["§", $senderName, $sender->getBalance()], Loader::getConfiguration("messages")->get("faction_player_deposit_money")));
                        }
                    }
                    $sender->reduceBalance($sender->getBalance());
                } else {
                    if (!is_numeric($args[1])) {
                        $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_is_numeric")));
                        return;
                    }
                    if (is_float($args[1])) {
                        $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_float_number")));
                        return;
                    }
                    if ($sender->getBalance() < $args[1] || $args[1] < 0) {
                        $sender->sendMessage(str_replace(["&", "{money}"], ["§", $sender->getBalance()], Loader::getConfiguration("messages")->get("sender_has_not_money")));
                        return;
                    }
                    Factions::addBalance(Factions::getFaction($senderName), $args[1]);
                    foreach (Factions::getPlayers(Factions::getFaction($senderName)) as $player) {
                        $online = Loader::getInstance()->getServer()->getPlayer($player);
                        if ($online instanceof Player) {
                            $online->sendMessage(str_replace(["&", "{playerName}", "{money}"], ["§", $senderName, $args[1]], Loader::getConfiguration("messages")->get("faction_player_deposit_money")));
                        }
                    }
                    $sender->reduceBalance($args[1]);
                }
                break;
            case "withdraw":
            case "w":
                if (!Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
                    return;
                }
                if (empty($args[1])) {
                    $sender->sendMessage(TE::RED . "Use /{$label} {$args[0]} <amount:all>");
                    return;
                }
                if ((!($senderName === Factions::getLeader(Factions::getFaction($senderName)) || $senderName === Factions::getCoLeader(Factions::getFaction($senderName))))) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_is_leader")));
                    return;
                }
                if ($args[1] === "all") {
                    if (Factions::getBalance(Factions::getFaction($senderName)) === 0) {
                        $sender->sendMessage(str_replace(["&", "{money}"], ["§", Factions::getBalance(Factions::getFaction($senderName))], Loader::getConfiguration("messages")->get("sender_has_not_money")));
                        return;
                    }
                    $sender->addBalance(Factions::getBalance(Factions::getFaction($senderName)));
                    foreach (Factions::getPlayers(Factions::getFaction($senderName)) as $player) {
                        $online = Loader::getInstance()->getServer()->getPlayer($player);
                        if ($online instanceof Player) {
                            $online->sendMessage(str_replace(["&", "{playerName}", "{money}"], ["§", $senderName, Factions::getBalance(Factions::getFaction($senderName))], Loader::getConfiguration("messages")->get("faction_player_withdraw_money")));
                        }
                    }
                    Factions::reduceBalance(Factions::getFaction($senderName), Factions::getBalance(Factions::getFaction($senderName)));
                } else {
                    if (!is_numeric($args[1])) {
                        $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_is_numeric")));
                        return;
                    }
                    if (is_float($args[1])) {
                        $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("not_float_number")));
                        return;
                    }
                    if (Factions::getBalance(Factions::getFaction($senderName)) < $args[1] || $args[1] < 0) {
                        $sender->sendMessage(str_replace(["&", "{money}"], ["§", Factions::getBalance(Factions::getFaction($senderName))], Loader::getConfiguration("messages")->get("sender_has_not_money")));
                        return;
                    }
                    $sender->addBalance($args[1]);
                    foreach (Factions::getPlayers(Factions::getFaction($senderName)) as $player) {
                        $online = Loader::getInstance()->getServer()->getPlayer($player);
                        if ($online instanceof Player) {
                            $online->sendMessage(str_replace(["&", "{playerName}", "{money}"], ["§", $senderName, $args[1]], Loader::getConfiguration("messages")->get("faction_player_withdraw_money")));
                        }
                    }
                    Factions::reduceBalance(Factions::getFaction($senderName), $args[1]);
                }
                break;
            case "chat":
            case "c":
                if (!Factions::inFaction($senderName)) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
                    return;
                }
                if (empty($args[1])) {
                    $sender->sendMessage(TE::RED . "Use /{$label} {$args[0]} <public:faction>");
                    return;
                }
                switch ($args[1]) {
                    case "faction":
                    case "f":
                        $sender->setChat(Player::FACTION_CHAT);
                        $sender->sendMessage(str_replace(["&", "{currentChat}"], ["§", $sender->getChat()], Loader::getConfiguration("messages")->get("sender_change_chat")));
                        break;
                    case "public":
                    case "p":
                        $sender->setChat(Player::PUBLIC_CHAT);
                        $sender->sendMessage(str_replace(["&", "{currentChat}"], ["§", $sender->getChat()], Loader::getConfiguration("messages")->get("sender_change_chat")));
                        break;
                    case "ally":
                    case "a":
                        $sender->setChat(Player::ALLY_CHAT);
                        $sender->sendMessage(str_replace(["&", "{currentChat}"], ["§", $sender->getChat()], Loader::getConfiguration("messages")->get("player_change_chat")));
                        break;
                }
                break;
            case "focus":
                if (empty($args[1])) {
                    $sender->sendMessage(TE::RED . "Use /{$label} {$args[0]} <factionName>");
                    return;
                }
                if (!Factions::isFactionExists($args[1])) {
                    $sender->sendMessage(str_replace(["&", "{factionName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("faction_not_exists")));
                    return;
                }
                $sender->setFocus(true);
                $sender->setFocusFaction($args[1]);
                break;
            case "unfocus":
                if (!$sender->isFocus()) {
                    $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_did_not_focus_faction")));
                    return;
                }
                $sender->setFocus(false);
                break;
            case "list":
                $page = 0;
                if (!empty($args[1])) {
                    $page = $args[1];
                }
                $factions = Factions::getFactions();
                if (empty($factions)) return;

                $pages = ceil(count($factions) / 10);

                $list = array_chunk($factions, 5);

                if ($page > $pages) {
                    return;
                }

                $sender->sendMessage(TE::LIGHT_PURPLE . TE::BOLD . "FACTIONS LIST§r§f: " . TE::RESET . TE::WHITE . $page . TE::GRAY . "/" . TE::WHITE . $pages);
                if (isset($list[$page])) {
                    foreach ($list[$page] as $id => $name) {
                        $sender->sendMessage(str_replace(["&", "{factionName}", "{onlinePlayers}", "{maxPlayers}", "{currentDtr}", "{maxDtr}"], ["§", $name, Factions::getOnlinePlayers($name), Factions::getMaxPlayers($name), Factions::getStrength($name), Factions::getMaxStrength($name)], Loader::getConfiguration("messages")->get("faction_list_information")));
                    }
                }
                break;
            case "who":
            case "info":
                if (empty($args[1])) {
                    if (!Factions::inFaction($senderName)) {
                        $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("sender_not_in_faction")));
                        return;
                    }
                    $homeLocation = Factions::getFactionHomeString(Factions::getFaction($senderName));
                    $dtr = Factions::getStrength(Factions::getFaction($senderName));
                    $maxPlayers = Loader::getDefaultConfig("FactionsConfig")["maxPlayers"];

                    $balance = Factions::getBalance(Factions::getFaction($senderName)) === null ? "$0" : "$" . Factions::getBalance(Factions::getFaction($senderName));
                    $players = Factions::getMaxPlayers(Factions::getFaction($senderName)) === null ? "Error 404 report in discord" : Factions::getMaxPlayers(Factions::getFaction($senderName));
                    $onlinePlayers = Factions::getListPlayers(Factions::getFaction($senderName)) === null ? "No members connected" : Factions::getListPlayers(Factions::getFaction($senderName));
                    $leader = Factions::getLeader(Factions::getFaction($senderName)) === null ? "Error 404 report in discord" : Factions::getLeader(Factions::getFaction($senderName)) . TE::YELLOW . "[" . TE::GREEN . PlayerBase::getKills(Factions::getLeader(Factions::getFaction($senderName))) . TE::YELLOW . "]";
                    $co_leader = Factions::getCoLeader(Factions::getFaction($senderName)) === null ? "Not Set Co-Leader" : Factions::getCoLeader(Factions::getFaction($senderName)) . TE::YELLOW . "[" . TE::GREEN . PlayerBase::getKills(Factions::getLeader(Factions::getFaction($senderName))) . TE::YELLOW . "]";
                    $timeRegen = Factions::getFreezeTime(Factions::getFaction($senderName)) === null ? "" : Time::getTimeToString(Factions::getFreezeTime(Factions::getFaction($senderName)));
                    $points = Factions::getPoints(Factions::getFaction($senderName));

                    $sender->sendMessage(str_replace(["&", "{home}", "{factionName}", "{onlinePlayers}", "{players}", "{maxPlayers}", "{currentDtr}", "{leader}", "{co_leader}", "{balance}", "{timeRegen}", "%n%", "{points}"], ["§", $homeLocation, Factions::getFaction($senderName), $onlinePlayers, $players, $maxPlayers, $dtr, $leader, $co_leader, $balance, $timeRegen, "\n", $points], Loader::getConfiguration("messages")->get("faction_information")));
                } else {
                    $player = Loader::getInstance()->getServer()->getPlayer($args[1]);
                    if ($player instanceof Player) {
                        if (!Factions::inFaction($player->getName())) {
                            $sender->sendMessage(str_replace(["&"], ["§"], Loader::getConfiguration("messages")->get("player_is_not_in_faction")));
                            return;
                        }
                        $points = Factions::getPoints(Factions::getFaction($player->getName()));
                        $homeLocation = Factions::getFactionHomeString(Factions::getFaction($player->getName()));
                        $dtr = Factions::getStrength(Factions::getFaction($player->getName()));
                        $maxPlayers = Loader::getDefaultConfig("FactionsConfig")["maxPlayers"];

                        $balance = Factions::getBalance(Factions::getFaction($player->getName())) === null ? "$0" : "$" . Factions::getBalance(Factions::getFaction($player->getName()));
                        $players = Factions::getMaxPlayers(Factions::getFaction($player->getName())) === null ? "Error 404 report in discord" : Factions::getMaxPlayers(Factions::getFaction($player->getName()));
                        $onlinePlayers = Factions::getListPlayers(Factions::getFaction($player->getName())) === null ? "No members connected" : Factions::getListPlayers(Factions::getFaction($player->getName()));
                        $leader = Factions::getLeader(Factions::getFaction($player->getName())) === null ? "Error 404 report in discord" : Factions::getLeader(Factions::getFaction($player->getName())) . TE::YELLOW . "[" . TE::GREEN . PlayerBase::getKills(Factions::getLeader(Factions::getFaction($player->getName()))) . TE::YELLOW . "]";
                        $co_leader = Factions::getCoLeader(Factions::getFaction($player->getName())) === null ? "There is no co-leader" : Factions::getCoLeader(Factions::getFaction($player->getName())) . TE::YELLOW . "[" . TE::GREEN . PlayerBase::getKills(Factions::getLeader(Factions::getFaction($player->getName()))) . TE::YELLOW . "]";
                        $timeRegen = Factions::getFreezeTime(Factions::getFaction($player->getName())) === null ? "" : Time::getTimeToString(Factions::getFreezeTime(Factions::getFaction($player->getName())));

                        $sender->sendMessage(str_replace(["&", "{home}", "{factionName}", "{onlinePlayers}", "{players}", "{maxPlayers}", "{currentDtr}", "{leader}", "{co_leader}", "{balance}", "{timeRegen}", "%n%", "{points}"], ["§", $homeLocation, Factions::getFaction($player->getName()), $onlinePlayers, $players, $maxPlayers, $dtr, $leader, $co_leader, $balance, $timeRegen, "\n", $points], Loader::getConfiguration("messages")->get("faction_information")));
                    } else {
                        if (!Factions::isFactionExists($args[1])) {
                            $sender->sendMessage(str_replace(["&", "{factionName}"], ["§", $args[1]], Loader::getConfiguration("messages")->get("faction_not_exists")));
                            return;
                        }
                        $points = Factions::getPoints($args[1]);
                        $homeLocation = Factions::getFactionHomeString($args[1]);
                        $dtr = Factions::getStrength($args[1]);
                        $maxPlayers = Loader::getDefaultConfig("FactionsConfig")["maxPlayers"];

                        $balance = Factions::getBalance($args[1]) === null ? "$0" : "$" . Factions::getBalance($args[1]);
                        $players = Factions::getMaxPlayers($args[1]) === null ? "Error 404 report in discord" : Factions::getMaxPlayers($args[1]);
                        $onlinePlayers = Factions::getListPlayers($args[1]) === null ? "No members connected" : Factions::getListPlayers($args[1]);
                        $leader = Factions::getLeader($args[1]) === null ? "Error 404 report in discord" : Factions::getLeader($args[1]) . TE::YELLOW . "[" . TE::GREEN . PlayerBase::getKills(Factions::getLeader($args[1])) . TE::YELLOW . "]";
                        $co_leader = Factions::getCoLeader($args[1]) === null ? "There is no co-leader" : Factions::getCoLeader($args[1]) . TE::YELLOW . "[" . TE::GREEN . PlayerBase::getKills(Factions::getLeader($args[1])) . TE::YELLOW . "]";
                        $timeRegen = Factions::getFreezeTime($args[1]) === null ? "" : Time::getTimeToString(Factions::getFreezeTime($args[1]));

                        $sender->sendMessage(str_replace(["&", "{home}", "{factionName}", "{onlinePlayers}", "{players}", "{maxPlayers}", "{currentDtr}", "{leader}", "{co_leader}", "{balance}", "{timeRegen}", "%n%", "{points}"], ["§", $homeLocation, $args[1], $onlinePlayers, $players, $maxPlayers, $dtr, $leader, $co_leader, $balance, $timeRegen, "\n", $points], Loader::getConfiguration("messages")->get("faction_information")));
                    }
                }
                break;
            case "ally":
                if (!Factions::inFaction($senderName)) {
                    $sender->sendMessage(TE::RED . "You are not in any faction");
                    return;
                }
                if (Factions::getLeader(Factions::getFaction($senderName)) !== $senderName) {
                    $sender->sendMessage(TE::RED . "You are not the leader of your faction!");
                    return;
                }
                if (empty($args[1])) {
                    $sender->sendMessage(TE::YELLOW."/f ally add [string: factionName] ".TE::GRAY."(Add an ally for your faction)");
                    $sender->sendMessage(TE::YELLOW."/f ally remove [string: factionName] ".TE::GRAY."(Remove an ally from your faction)");
                    $sender->sendMessage(TE::YELLOW."/f ally accept [string: factionName] ".TE::GRAY."(Accept a request from ally)");
                    $sender->sendMessage(" ");
                    $sender->sendMessage(TE::GRAY."You must be a faction leader to execute these commands");
                    return;
                }
                $faction = Factions::getFaction($senderName);
                switch ($args[1]) {
                    case "add":
                        if (Factions::getAlly($faction) !== null) {
                            $sender->sendMessage(TE::RED . "You already have an ally");
                            return;
                        }

                        if (empty($args[2])) {
                            $sender->sendMessage(TE::RED . "Usage: /f ally add <factionName>");
                            return;
                        }

                        if (!Factions::isFactionExists($args[2])) {
                            $sender->sendMessage(TE::RED . "There is no such faction");
                            return;
                        }

                        $sender->sendMessage(TE::GREEN . "The invitation was send successfully");

                        $leader = Loader::getInstance()->getServer()->getPlayer(Factions::getLeader($args[2]));
                        if ($leader instanceof Player) {
                            Factions::inviteAlly(Factions::getFaction($senderName), $args[2]);
                            $leader->sendMessage(TE::YELLOW . "You have received an invitation to do ally from " . TE::WHITE . Factions::getFaction($senderName) . TE::YELLOW . " if you want to accept the invitation use " . TE::WHITE . "/f ally accept " . Factions::getFaction($senderName));
                        } else $sender->sendMessage(TE::RED . "The leader of the faction is not connected");
                        break;
                    case "remove":
                        if (Factions::getAlly(Factions::getFaction($senderName)) === null) {
                            $sender->sendMessage(TE::RED . "Your faction has no ally");
                            return;
                        }
                        if (empty($args[2])) {
                            $sender->sendMessage(TE::RED . "Mention your ally to remove it");
                            return;
                        }
                        if (Factions::getAlly(Factions::getFaction($senderName)) === $args[2]) {
                            $sender->sendMessage(TE::RED . "The mentioned faction is not an ally with you");
                            return;
                        }
                        $sender->sendMessage(TE::GREEN . "The ally was removed successfully");
                        Factions::removeAlly(Factions::getFaction($senderName));
                        break;
                    case "accept":
                        if(!Factions::isInviteAlly($faction)){
                            $sender->sendMessage(TE::RED."You have no invitations to allys");
                            return;
                        }
                        if(empty($args[2])){
                            $sender->sendMessage(TE::RED."Usage: /f ally accept <allyName>");
                            return;
                        }
                        foreach (Factions::getAllyInvitation($faction) as $ally) {
                            if($args[2] === $ally){
                                $sender->sendMessage(TE::GREEN."The ally has been accepted correctly!");
                                Factions::addAlly($faction, $ally);
                            }else{
                                $sender->sendMessage(TE::RED."You don't have an invitation from ".TE::YELLOW.$args[2]);
                            }
                        }
                        break;
                }
                break;
            case "top":
                $top = Factions::getTopFactions();
                $space = "\n";
                $message = TE::BOLD . TE::DARK_PURPLE . "Top Factions " . TE::RESET."§7|".TE::WHITE." Map II". "\n";
                foreach ($top as $n => $list) {
                    $message .= TE::GRAY . $n . ". " . TE::LIGHT_PURPLE . $list[0] . "§7 - " .TE::WHITE. $list[1] . $space;
                }
                $message = substr($message, 0, -1);
                $sender->sendMessage($message);
                break;
        }
    }
}
?>
