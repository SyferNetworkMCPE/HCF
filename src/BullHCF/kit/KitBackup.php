<?php

namespace BullHCF\kit;

use BullHCF\Loader;
use BullHCF\player\Player;

use BullHCF\item\Items;

use pocketmine\utils\{Config, TextFormat as TE};

class KitBackup {

    /**
     * @return void
     */
    public static function initAll() : void {
        $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."kits.yml", Config::YAML);
        foreach($file->getAll() as $name => $values){
            self::init($name);
        }
    }

    /**
     * @return void
     */
    public static function saveAll() : void {
        foreach(KitManager::getKits() as $name => $values){
            self::save($name);
        }
    }

    /**
     * @param String $name
     * @return void
     */
    public static function init(String $name) : void {
        try {
            $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."kits.yml", Config::YAML);
            $kitData = $file->getAll()[$name];

            if(isset($kitData["contents"])){
                foreach($kitData["contents"] as $slot => $item){
                    $kitData["contents"][$slot] = Items::itemDeserialize($item);
                }
            }
            if(isset($kitData["armorContents"])){
                foreach($kitData["armorContents"] as $slot => $item){
                    $kitData["armorContents"][$slot] = Items::itemDeserialize($item);
                }
            }
            KitManager::createKit($kitData);
        } catch (\Exception $exception) {
            Loader::getInstance()->getLogger()->error("Can't load kit: ".$name);  
            Loader::getInstance()->getLogger()->error($exception->getMessage()); 
        }
    }

    /**
     * @param String $name
     * @return void
     */
    public static function save(String $name) : void {
        try {
            $crateData = [];

            $kit = KitManager::getKit($name);
            $file = new Config(Loader::getInstance()->getDataFolder()."backup".DIRECTORY_SEPARATOR."kits.yml", Config::YAML);

            $kitData["name"] = $kit->getName();
            $kitData["permission"] = $kit->getPermission();
            $kitData["nameFormat"] = $kit->getNameFormat();

            foreach($kit->getItems() as $slot => $item){
                $kitData["contents"][$slot] = Items::itemSerialize($item);
            }
            foreach($kit->getArmorItems() as $slot => $item){
                $kitData["armorContents"][$slot] = Items::itemSerialize($item);
            }
            $file->set($kit->getName(), $kitData);
            $file->save();
        } catch (\Exception $exception){
            Loader::getInstance()->getLogger()->error("Can't save kit: ".$name);
            Loader::getInstance()->getLogger()->error($exception->getMessage());
        }
    }
}

?>