<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\User\UserEntity;
use App\Repository\UserEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

class PermissionsMakeAdminCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'app:permissions:make-admin';

    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var UserRepository */
    protected $userRepository;

    /**
     * @{@inheritdoc}
     */
    public function __construct(EntityManagerInterface $entityManager, UserRepository $userRepository)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
    }

    /**
     * @{@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Grants permissions to manage permissions to given user identified by Discord user id.')
            ->addArgument('discord_user_id', InputArgument::OPTIONAL, 'Discord user id (18-digits integer)')
        ;
    }

    /**
     * @{@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $discordUserId = $input->getArgument('discord_user_id');
        if (null === $discordUserId) {
            /** @var UserEntity[] $allUsers */
            $allUsers = $this->userEntityRepository->findAll();
            if (empty($allUsers)) {
                $io->error('No users found!');

                return 1;
            }

            $allUsersNames = array_map(static function (UserEntity $x) {
                return sprintf('%d (%s)', $x->getExternalId(), $x->getUsername());
            }, $allUsers);

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

        $user = $this->userRepository->findByExternalId($discordUserId);
        if (!$user) {
            $io->error(sprintf('User not found by given id: "%s"!', $discordUserId));

            return 1;
        }

        $permissions = $user->getPermissions();
        $usersPermissions = $permissions->getUsersPermissions();
        $usersPermissions->setList(true);
        $usersPermissions->setManagePermissions(true);
        $this->entityManager->flush();

        $io->success(sprintf('Successfully granted admin permissions for user with id: "%s" (%s)!', $discordUserId, $user->getUsername()));

        return 0;
    }
}
