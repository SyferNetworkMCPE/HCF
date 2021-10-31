<?php

namespace SyferHCF\Task;

use SyferHCF\Loader;
use SyferHCF\entities\spawnable\{Top1, Top2, Top3};
use Himbeer\LibSkin\SkinConverter;
use pocketmine\scheduler\Task;
use pocketmine\utils\Config;
use pocketmine\Server;
use pocketmine\entity\Skin;

/**
* Class TopKillTask
* @package Loader\Scheduler
*/
class TopKillTask extends Task {

  /** @var Loader $plugin */
  private $plugin;

  /**
  * @param Loader $plugin
  */
  public function __construct(Loader $plugin) {
    $this->plugin = $plugin;
  }

  /**
  * @param int $tick
  */
  public function onRun(int $tick) {
    foreach (Server::getInstance()->getLevels() as $levels) {
      foreach ($levels->getEntities() as $entities) {
        if ($entities instanceof Top1) {
          $entities->setNameTag(TopKillTask::setTop1());
          $entities->setNameTagAlwaysVisible(true);
          $entities->setScale(0.9);
        } else if ($entities instanceof Top2) {
          $entities->setNameTag(TopKillTask::setTop2());
          $entities->setNameTagAlwaysVisible(true);
          $entities->setScale(0.9);
        } else if ($entities instanceof Top3) {
          $entities->setNameTag(TopKillTask::setTop3());
          $entities->setNameTagAlwaysVisible(true);
          $entities->setScale(0.9);
        }
      }
    }
  }

  /**
  * @return string
  */
  public static function setTop1(): string {
    $kills = new Config(Loader::getInstance()->getDataFolder() . "Kills.yml", Config::YAML);
    $tops = [];
    $title = "§l§dTop #1" . "\n";
    foreach ($kills->getAll() as $key => $top) {
      array_push($tops, $top);
    }
    natsort($tops);
    $pl = array_reverse($tops);
    if (max($tops) != null) {
      $top1 = array_search(max($tops), $kills->getAll());
      $subtitle1 = "§f{$top1}" . "\n" . "§f" . max($tops) . " §cKills";
      foreach (Server::getInstance()->getLevels() as $levels) {
        foreach ($levels->getEntities() as $entities) {
          if ($entities instanceof Top1) {
            $skinData = SkinConverter::imageToSkinDataFromPngPath(Loader::getInstance()->getDataFolder() . "Skins/{$top1}.png");
            $entities->setSkin(new Skin("custom_skin", $skinData));
          }
        }
      }
    } else {
      $subtitle1 = '§cNone';
    }
    return $title . $subtitle1;
  }

  /**
  * @return string
  */
  public static function setTop2(): string {
    $kills = new Config(Loader::getInstance()->getDataFolder() . "Kills.yml", Config::YAML);
    $tops = [];
    $title = "§l§dTop #2" . "\n";
    foreach ($kills->getAll() as $key => $top) {
      array_push($tops, $top);
    }
    natsort($tops);
    $pl = array_reverse($tops);
    if ($pl[1] != null) {
      $top1 = array_search($pl[1], $kills->getAll());
      $subtitle1 = "§f{$top2}" . "\n" . "§f" . $pl[1] . " §cKills";
      foreach (Server::getInstance()->getLevels() as $levels) {
        foreach ($levels->getEntities() as $entities) {
          if ($entities instanceof Top2) {
            $skinData = SkinConverter::imageToSkinDataFromPngPath(Loader::getInstance()->getDataFolder() . "Skins/{$top2}.png");
            $entities->setSkin(new Skin("custom_skin", $skinData));
          }
        }
      }
    } else {
      $subtitle1 = '§cNone';
    }
    return $title . $subtitle1;
  }

  /**
  * @return string
  */
  public static function setTop3(): string {
    $kills = new Config(Loader::getInstance()->getDataFolder() . "Kills.yml", Config::YAML);
    $tops = [];
    $title = "§l§dTop #3" . "\n";
    foreach ($kills->getAll() as $key => $top) {
      array_push($tops, $top);
    }
    natsort($tops);
    $pl = array_reverse($tops);
    if ($pl[2] != null) {
      $top1 = array_search($pl[2], $kills->getAll());
      $subtitle1 = "§f{$top3}" . "\n" . "§f" . $pl[2] . " §cKills";
      foreach (Server::getInstance()->getLevels() as $levels) {
        foreach ($levels->getEntities() as $entities) {
          if ($entities instanceof Top3) {
            $skinData = SkinConverter::imageToSkinDataFromPngPath(Loader::getInstance()->getDataFolder() . "Skins/{$top3}.png");
            $entities->setSkin(new Skin("custom_skin", $skinData));
          }
        }
      }
    } else {
      $subtitle1 = '§cNone';
    }
    return $title . $subtitle1;
  }
}
