<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User\User;
use App\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class PermissionsMakeAdminCommand extends Command
{
    protected EntityManagerInterface $entityManager;
    protected UserRepository $userRepository;

    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setName('app:permissions:make-admin')
            ->setDescription('Grants permissions to manage permissions to given user identified by Discord user id.')
            ->addArgument('discord_user_id', InputArgument::OPTIONAL, 'Discord user id (18-digits integer)')
        ;
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $discordUserId = $input->getArgument('discord_user_id');
        if (!$discordUserId) {
            /** @var User[] $allUsers */
            $allUsers = $this->userRepository->findAll();
            if (!$allUsers) {
                $io->error('No users found!');

                return 1;
            }

            $allUsersNames = array_map(static fn (User $user) => sprintf('%d (%s)', $user->getExternalId(), $user->getUsername()), $allUsers);

            $helper = $this->getHelper('question');
            $question = new ChoiceQuestion('Please select user from the list', $allUsersNames);

            $response = $helper->ask($input, $output, $question);
            if (!$response) {
                return 1;
            }

            $discordUserId = substr($response, 0, 18);
        }

        if (!preg_match('/[\d]{18}/', $discordUserId)) {
            $io->error(sprintf('Incorrect format of user id. Must be a 18-digits integer, "%s" given!', $discordUserId));

            return 1;
        }

        $discordUserId = (int) $discordUserId;

        $user = $this->userRepository->findOneByExternalId($discordUserId);
        if (!$user) {
            $io->error(sprintf('User not found by given id: "%s"!', $discordUserId));

            return 1;
        }

        $permissions = $user->getPermissions();
        $userPermissions = $permissions->getUserManagementPermissions();
        $userPermissions->setList(true);
        $userPermissions->setManagePermissions(true);
        $this->entityManager->flush();

        $io->success(sprintf('Successfully granted admin permissions for user with id: "%s" (%s)!', $discordUserId, $user->getUsername()));

        return 0;
    }
}
