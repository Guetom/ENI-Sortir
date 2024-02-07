<?php

namespace App\Command;

use App\Entity\Status;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:setup-status',
    description: 'Command to setup status in database',
    hidden: false,
)]
class SetupStatusCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private StatusRepository       $statusRepository
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $status = [
            'Créée',
            'Ouverte',
            'Clôturée',
            'Activité en cours',
            'Passée',
            'Annulée'
        ];

        $req = $this->statusRepository->findAll();
        if (empty($req)) {
            foreach ($status as $value) {
                $status = new Status();
                $status->setLabel($value);
                $this->entityManager->persist($status);
            }
            $this->entityManager->flush();
        } else {
            $io->error('Status already exist in database');
            return Command::FAILURE;
        }

        $io->success('Status has been successfully added to the database');
        return Command::SUCCESS;
    }
}
