<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-user',
    description: 'Create a new user',
    hidden: false
)]
class CreateUserCommand extends Command
{
    private UserPasswordHasherInterface $passwordHashed;
    public function __construct(
        private readonly UserPasswordHasherInterface $Harsher,
        private EntityManagerInterface $entityManager
    ) {
        $this->passwordHashed = $this->Harsher;
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $output->writeln([
            'User Creator',
            '============',
            '',
        ]);

        $pseudo = $io->ask('Please enter the pseudo');
        $email = $io->ask('Please enter the email');
        $firstName = $io->ask('Please enter the first name');
        $lastName = $io->ask('Please enter the last name');
        $isAdmin = $io->confirm('Is this user an admin?');
        $password = $io->askHidden('Please enter the password');
        $confirmPassword = $io->askHidden('Please confirm the password');

        if ($password !== $confirmPassword) {
            $io->error('The passwords do not match');
            return Command::FAILURE;
        }

        $user = new User();
        $user
            ->setPseudo($pseudo)
            ->setEmail($email)
            ->setFirstName($firstName)
            ->setLastName($lastName)
            ->setRoles($isAdmin ? ['ROLE_ADMIN'] : ['ROLE_USER'])
            ->setDisable(false)
            ->setPassword($this->passwordHashed->hashPassword($user, $password));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $io->writeln(['User created successfully',
            '============',
            'Login : ' . $pseudo,]
        );

        return Command::SUCCESS;
    }
}
