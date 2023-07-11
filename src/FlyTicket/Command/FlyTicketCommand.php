<?php

namespace FlyTicket\Command;

use FlyTicket\Manager\FormManager;
use FlyTicket\Main;
use FlyTicket\Manager\FlyTicketManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use FlyTicket\Manager\MessageManager;

class FlyTicketCommand extends Command
{

    public function __construct() {
        $config = Main:: getInstance()->getConfig();
        $command_name = $config->get("command");
        $command_description = $config->get("command-description");
        parent::__construct($command_name, $command_description, "/".$command_name);
        $this->setPermission("flyticket.command.use");
    }
    public function execute(CommandSender $player, string $commandLabel, array $args) {
        if ($player instanceof Player) {
            $formM = new FormManager();
            $formM->FlyTicketForm($player);
        } else {
            $player->sendMessage(MessageManager::NoConsole());
        }
    }
}