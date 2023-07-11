<?php

namespace FlyTicket;

use FlyTicket\Command\FlyTicketCommand;
use FlyTicket\Listener\FlyTicketListener;
use FlyTicket\Manager\DatabaseManager;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\utils\Filesystem;

class Main extends PluginBase
{
    public static Main $main;
    private $config;
    private $databaseManager;

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
        $command_name = $this->config->get("command");
        $this->getLogger()->info("FlyTicket Enabled!");
        $this->getServer()->getCommandMap()->register($command_name, new FlyTicketCommand());
        $this->getServer()->getPluginManager()->registerEvents(new FlyTicketListener(), $this);
    }

    public function onDisable(): void
    {
        $this->databaseManager->getDatabase(false);
        $this->deleteOldDataFile();
    }
    
    public function deleteOldDataFile(): void {
        $dataFilePath = $this->getDataFolder() . 'flyticketData.db' ?? null;
        if (file_exists($dataFilePath)) {
            unlink($dataFilePath);
            $this->getLogger()->info("§aflyticketData deleted");
        } else {}
    }
}