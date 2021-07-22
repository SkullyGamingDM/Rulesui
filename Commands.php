namespace SkullyGamingDM\RulesUI;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;

class Commands extends PluginCommand {

    private $main;

    public function __construct(Main $main, string $desc, array $aliases) {
        $this->main = $main;
        parent::__construct("rules", $main);
        $this->setPermission("rulesui.rules");
        $this->setAliases($aliases);
        $this->setUsage("/rules");
        $this->setDescription((empty($desc)) ? "Server rules in UI form" : $desc);
    }

    public function getMain() : Main {
        return $this->main;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage("[RulesUI] This command only works in game!");
        } else {
            if (!$sender->hasPermission("rulesui.rules")) {
                $sender->sendMessage("[RulesUI] You do not have permission to use this command!");
            } else {
                $this->getMain()->getConfig()->reload();
                $this->getMain()->RulesUI($sender);
            }
        }
        return true;
    }

}
