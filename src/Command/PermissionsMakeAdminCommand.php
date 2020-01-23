<?php

declare(strict_types=1);

namespace App\Command;

use App\Repository\UserEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PermissionsMakeAdminCommand extends Command
{
    /** @var string */
    protected static $defaultName = 'app:permissions:make-admin';

    /** @var EntityManagerInterface */
    protected $entityManager;

    /** @var UserEntityRepository */
    protected $userEntityRepository;

    /**
     * @{@inheritdoc}
     */
    public function __construct(EntityManagerInterface $entityManager, UserEntityRepository $userEntityRepository)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
        $this->userEntityRepository = $userEntityRepository;
    }

    /**
     * @{@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setDescription('Grants permissions to manage permissions to given user identified by Discord user id.')
            ->addArgument('discord_user_id', InputArgument::REQUIRED, 'Discord user id (18-digits integer)')
        ;
    }

    /**
     * @{@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $discordUserId = $input->getArgument('discord_user_id');
        if (!preg_match('/[\d]{18}/', $discordUserId)) {
            $io->error(sprintf('Incorrect format of user id. Must be a 18-digits integer, "%s" given!', $discordUserId));

            return 1;
        }

        $discordUserId = (int) $discordUserId;

        $userEntity = $this->userEntityRepository->findByExternalId($discordUserId);
        if (!$userEntity) {
            $io->error(sprintf('User not found by given id: "%s"!', $discordUserId));

            return 1;
        }

        $permissionsEntity = $userEntity->getPermissions();
        $permissionsEntity->setListUsers(true);
        $permissionsEntity->setManageUsersPermissions(true);
        $this->entityManager->flush();

        $io->success(sprintf('Successfully granted admin permissions for user with id: "%s" (%s)!', $discordUserId, $userEntity->getUsername()));

        return 0;
    }
}
