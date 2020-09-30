<?php
namespace App\Command\AbstractInterface;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface of command.
 * Interface CommandInterface
 * @package HttpServeApplication\Command\Contract
 */
interface CommandInterface
{
    public function configure(): void;

    public function execute(InputInterface $input, OutputInterface $output): int;
}