<?php

namespace FlyTicket;

use FlyTicket\Command\FlyTicketCommand;
use FlyTicket\Listener\FlyTicketListener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase
{
    public static Main $main;
    public static Config $config;
    protected function onLoad(): void
    {
        self::$main = $this;
        $this->saveResource("config.yml");
        self::$config = new Config($this->getDataFolder()."config.yml", 2);
    }
    public function onEnable(): void
    {
        $command_name = self::$config->get("command");
        $this->getLogger()->info("FlyTicket Enabled!");
        $this->getServer()->getCommandMap()->register($command_name, new FlyTicketCommand());
        $this->getServer()->getPluginManager()->registerEvents(new FlyTicketListener(), $this);
    }
}