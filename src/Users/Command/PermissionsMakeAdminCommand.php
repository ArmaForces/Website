<?php

declare(strict_types=1);

namespace App\Users\Command;

use App\Users\Entity\User\User;
use App\Users\Repository\User\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class PermissionsMakeAdminCommand extends Command
{
    public const NAME = 'app:permissions:make-admin';
    public const ARGUMENT_DISCORD_USER_ID = 'discord-user-id';
    public const OPTION_FULL_PERMISSIONS = 'full-permissions';

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserRepository $userRepository
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setName(self::NAME)
            ->setDescription('Grants permissions to manage permissions to given user identified by Discord user id.')
            ->addArgument(self::ARGUMENT_DISCORD_USER_ID, InputArgument::OPTIONAL, 'Discord user id (18-digits integer)')
            ->addOption(self::OPTION_FULL_PERMISSIONS, 'f', InputOption::VALUE_OPTIONAL, 'Full permissions', false)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $fullPermissions = $input->getOption(self::OPTION_FULL_PERMISSIONS);
        $discordUserId = $input->getArgument(self::ARGUMENT_DISCORD_USER_ID);
        if (!$discordUserId) {
            /** @var User[] $allUsers */
            $allUsers = $this->userRepository->findAll();
            if (!$allUsers) {
                $io->error('No users found!');

                return 1;
            }

            $allUsersNames = array_map(static fn (User $user) => sprintf('%d (%s)', $user->getExternalId(), $user->getUsername()), $allUsers);

            /** @var QuestionHelper $helper */
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

        $user = $this->userRepository->findOneByExternalId($discordUserId);
        if (!$user) {
            $io->error(sprintf('User not found by given id: "%s"!', $discordUserId));

            return 1;
        }

        $permissions = $user->getPermissions();
        if (false !== $fullPermissions) {
            $permissions->grantAll();
        } else {
            $permissions->userList = true;
            $permissions->userUpdate = true;
        }

        $this->entityManager->flush();

        $io->success(sprintf('Successfully granted admin permissions for user with id: "%s" (%s)!', $discordUserId, $user->getUsername()));

        return 0;
    }
}
