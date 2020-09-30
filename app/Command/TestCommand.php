<?php
namespace App\Command;

use App\Command\AbstractInterface\CommandInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Test to run console command.
 * Class TestCommand
 * @package HttpServeApplication\Command
 */
class TestCommand extends Command implements CommandInterface
{
    public function configure(): void
    {
        $this->setName('test-run')
            ->setDescription('Test command.');
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $output->writeln("Hello world!");

        return self::SUCCESS;
    }
}