<?php
use Symfony\Component\Console\Application;
use App\Command\TestCommand;
use  \App\Command\SwooleServer\StartApplicationCommand;

if (!$application instanceof Application) {
    throw new RuntimeException("application not instanceof " . Application::class);
}

$application->add(new TestCommand());

$application->add(new StartApplicationCommand());