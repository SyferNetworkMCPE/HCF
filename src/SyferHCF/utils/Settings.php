<?php

namespace SyferHCF\utils;

use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\utils\Config;
use SyferHCF\Factions;
use SyferHCF\Loader;
use SyferHCF\player\Player;
use pocketmine\utils\TextFormat as TE;
class Settings {

    /**
     * @var
     */
     
    protected $plugin;

    public function getTopList()
    {
        $all = (new Config(Loader::getInstance()->getDataFolder() . "kills.yml", Config::YAML))->getAll();
        arsort($all);
        $ret = [];
        $n = 1;
        $max = ceil(count($all) / 3);
        $page = min($max, max(1, 1));
        foreach ($all as $p => $m) {
            $current = ceil($n / 3);
            if ($current == $page) {
                $ret[$n] = [$p, $m];
            } elseif ($current > $page) {
                break;
            }
            ++$n;
        }
        return $ret;
    }
    
    public function sendTopList()
    {
        $top = $this->getTopList();
        $space = "\n";
        $message = TE::BOLD . TE::LIGHT_PURPLE . "Top Kills" . TE::RESET . "\n \n";
        foreach ($top as $n => $list) {
            $message .= TE::AQUA . $n . " " . TE::GRAY . $list[0] . " - " . $list[1] . $space;
        }
        $message = substr($message, 0, -1);
        return $message;
    }

    public function setTextTop()
    {
        $config = new Config(Loader::getInstance()->getDataFolder()."config.yml", Config::YAML);
        if($config->exists("Position")){
            $args = explode(":", $config->get("Position"));
            if(Loader::getInstance()->getServer()->getLevelByName($args[3]) == null){
                return false;
            }
            $level = Loader::getInstance()->getServer()->getLevelByName($args[3]);
            if(isset(Loader::getInstance()->textParticle[$config->get("Position")])){
                return false;
            }
            Loader::getInstance()->textParticle[$config->get("Position")] = Loader::getInstance()->getScheduler()->scheduleRepeatingTask(new TopTask(Loader::getInstance(), $args[0], $args[1], $args[2], $level), 20);
        }
        return true;
    }

    public function setTop(Player $player, int $top){
        $nbt = Entity::createBaseNBT(new Vector3((float)$player->getX(), (float)$player->getY(), (float)$player->getZ()));
        $human = new TopKill($player->getLevel(), $nbt);
        $human->yaw = $player->getYaw();
        $human->pitch = $player->getPitch();
        $human->setSkin(clone $player->getSkin());
        $human->setScale(1);
        $human->setTop($top);
        $human->setNametagVisible(true);
        $human->setNameTagAlwaysVisible(true);
        $human->setImmobile(true);
        $human->spawnToAll();
    }
}
