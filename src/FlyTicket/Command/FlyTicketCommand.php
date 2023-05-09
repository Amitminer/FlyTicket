<?php

namespace FlyTicket\Command;

use FlyTicket\Form\FormManager;
use FlyTicket\Main;
use FlyTicket\Manager\FlyTicketManager;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;

class FlyTicketCommand extends Command
{

    public function __construct() {
        $command_name = Main::$config->get("command");
        $command_description = Main::$config->get("command-description");
        parent::__construct($command_name, $command_description, "/".$command_name);
    }
    public function execute(CommandSender $player, string $commandLabel, array $args) {
        if ($player instanceof Player) {
            $formM = new FormManager();
            $formM->FlyTicketForm($player);
        } else {
            $player->sendMessage(FlyTicketManager::CONSOLE_USED);
        }
    }
}