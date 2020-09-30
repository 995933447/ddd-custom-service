<?php
namespace App\Command\SwooleServer;

use App\Command\AbstractInterface\CommandInterface;
use Framework\ServeApplication\Swoole\Application;
use Framework\ServeApplication\Swoole\ApplicationConfig;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StartApplicationCommand extends Command implements CommandInterface
{
    protected $defaultConfigFile = __DIR__ . '/../../../infrastructure/resource/swoole_server.php';

    public function configure(): void
    {
        $this->setName('swoole-server:start');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        (
            new Application(
                new ApplicationConfig(
                    require $input->hasOption($config_option_name = 'config')?
                        $input->getOption($config_option_name): $this->defaultConfigFile
                )
            )
        )->run();
    }
}