<?php
declare(strict_types=1);

namespace LotGD\Core\Console;

use LotGD\Core\Bootstrap;

use LotGD\Core\Console\Command\CharacterListCommand;
use LotGD\Core\Console\Command\CharacterResetViewpointCommand;
use LotGD\Core\Console\Command\ConsoleCommand;
use LotGD\Core\Console\Command\DatabaseInitCommand;
use LotGD\Core\Console\Command\DatabaseSchemaUpdateCommand;
use LotGD\Core\Console\Command\ModuleRegisterCommand;
use LotGD\Core\Console\Command\ModuleValidateCommand;
use Symfony\Component\Console\Application;

/**
 * Main execution class for the daenerys tool.
 */
class Main
{
    private $application;
    private $bootstrap;
    private $game;

    /**
     * Construct a new daenerys tool instance.
     */
    public function __construct()
    {
        $this->application = new Application();

        $this->application->setName("daenerys 🐲 ");
        $this->application->setVersion("0.0.1 (lotgd/core version " . \LotGD\Core\Game::getVersion() . ")");
    }

    /**
     * Add supported commands, including those configured from lotgd.yml files.
     */
    protected function addCommands()
    {
        $this->application->add(new ModuleValidateCommand($this->game));
        $this->application->add(new ModuleRegisterCommand($this->game));
        $this->application->add(new DatabaseInitCommand($this->game));
        $this->application->add(new DatabaseSchemaUpdateCommand($this->game));
        $this->application->add(new ConsoleCommand($this->game));
        $this->application->add(new CharacterListCommand($this->game));
        $this->application->add(new CharacterResetViewpointCommand($this->game));

        // Add additional ones
        $this->bootstrap->addDaenerysCommands($this->application);
    }

    /**
     * Run the danerys tool.
     */
    public function run()
    {
        // Bootstrap application
        $this->bootstrap = new Bootstrap();
        $this->game = $this->bootstrap->getGame(\getcwd());

        // Add commands
        $this->addCommands();

        // Run
        $this->application->run();
    }
}
