<?php

namespace SVega9848\GlobalToast\Commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\network\mcpe\protocol\ToastRequestPacket;
use pocketmine\player\Player;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;
use SVega9848\GlobalToast\Core\Main;

class GlobalToastCommand extends Command implements PluginOwned {

    private Main $main;

    public function __construct(Main $main)
    {
        $this->main = $main;
        parent::__construct("globaltoast", "Broadcast toast announcements to everyone in the server!");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        $plugin = $this->getOwningPlugin();
        if($sender instanceof Player) {
            if($sender->hasPermission("globaltoast.cmd")) {
                if(isset($args[0])) {
                    foreach($plugin->getServer()->getOnlinePlayers() as $player) {
                        $packet = ToastRequestPacket::create(TextFormat::colorize($this->main->config->get("toast-title")), TextFormat::colorize(implode(" ", $args)));
                        $player->getNetworkSession()->sendDataPacket($packet);
                    }
                } else {
                    $sender->sendMessage(TextFormat::colorize("&7[&c!&7] &cYou must type someting! Example: /globaltoast (message)"));
                }
            }
        }
    }

    public function getOwningPlugin(): Plugin
    {
        return $this->main;
    }
}