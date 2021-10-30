<?php

namespace SyferHCF\Task\asynctask;

use SyferHCF\Loader;
use SyferHCF\player\Player;

use pocketmine\scheduler\AsyncTask;
use pocketmine\Server;

use mysqli_connect;
use mysqli_fetch_assoc;

class SavePlayerData extends AsyncTask {

    /**
     * SavePlayerData Constructor.
     */
    public function __construct(){
        
    }

    /**
     * @return void
     */
    public function onRun() : void {
        
    }

    /**
     * @param Server $server
     * @return void
     */
    public function onCompletion(Server $server) : void {
        
    }
}

?>
