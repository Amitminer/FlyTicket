<?php

namespace FlyTicket;

use FlyTicket\Command\FlyTicketCommand;
use FlyTicket\Listener\FlyTicketListener;
use FlyTicket\Manager\DatabaseManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{
    public static Main $main;
    private $config;
    private $databaseManager;

    /**
     * Get the instance of the main class.
     *
     * @return Main The instance of the main class.
     */
    public static function getInstance(): Main
    { 
        return self::$main;
    }

    protected function onLoad(): void
    {
        self::$main = $this;
        $this->saveResource("config.yml");
        $this->config = new Config($this->getDataFolder() . 'config.yml', Config::YAML);
    }

    public function onEnable(): void
    {
        $this->deleteOldDataFile();
        $this->saveDefaultConfig();
        $this->databaseManager = new DatabaseManager();
        $this->databaseManager->getDatabase(true);
        $commandName = $this->config->get("command");
        $this->getLogger()->info("FlyTicket Enabled!");
        $this->getServer()->getCommandMap()->register($commandName, new FlyTicketCommand());
        $this->getServer()->getPluginManager()->registerEvents(new FlyTicketListener(), $this);
    }

    public function onDisable(): void
    {
        $this->databaseManager->getDatabase(false);
        $this->deleteOldDataFile();
    }
    
    /**
     * Delete the old data file (flyticketData.db) from the plugin_data directory.
     */
    public function deleteOldDataFile(): void
    {
        $dataFilePath = $this->getDataFolder() . 'flyticketData.db';
        
        if (file_exists($dataFilePath)) {
            unlink($dataFilePath);
            $this->getLogger()->info("flyticketData.db deleted");
        }
    }
}
