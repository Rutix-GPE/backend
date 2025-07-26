<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Process\Process;
use Symfony\Component\HttpKernel\KernelInterface;

#[AsCommand(
    name: 'app:db:rebuild',
    description: 'Supprime et recrée la BDD, régénère les migrations, les exécute, et charge une fixture spécifique.',
)]
class RebuildDatabaseCommand extends Command
{
    private const MIGRATIONS_PATH = 'migrations';

    public function __construct(
        private KernelInterface $kernel
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $env = $this->kernel->getEnvironment();

        $filesystem = new Filesystem();
        $baseCommand = ['php', 'bin/console'];

        $commands = [
            array_merge($baseCommand, ['doctrine:database:drop', '--force', '--if-exists', '--env=' . $env]),
            array_merge($baseCommand, ['doctrine:database:create', '--env=' . $env]),
        ];

        if ($filesystem->exists(self::MIGRATIONS_PATH)) {
            $io->section('Suppression des anciens fichiers de migration...');
            $filesystem->remove(glob(self::MIGRATIONS_PATH . '/Version*.php'));
        }

        $commands[] = array_merge($baseCommand, ['doctrine:migrations:diff', '--env=' . $env]);
        $commands[] = array_merge($baseCommand, ['doctrine:migrations:migrate', '--no-interaction', '--env=' . $env]);

        $commands[] = array_merge($baseCommand, [
            'doctrine:fixtures:load',
            '--group=pres',
            '--no-interaction',
            '--env=' . $env
        ]);

        foreach ($commands as $cmd) {
            $process = new Process($cmd);
            $io->section('Exécution : ' . implode(' ', $cmd));
            $process->run(function ($type, $buffer) use ($output) {
                $output->write($buffer);
            });

            if (!$process->isSuccessful()) {
                $io->error("Échec de la commande : " . implode(' ', $cmd));
                return Command::FAILURE;
            }
        }

        $io->success("Base de données reconstruite avec succès et fixture spéciale chargée.");
        return Command::SUCCESS;
    }
}
