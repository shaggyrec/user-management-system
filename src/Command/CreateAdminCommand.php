<?php

namespace App\Command;

use App\Entity\Group;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class CreateAdminCommand
 *
 * @package App\Command
 */
#[AsCommand(
    name: 'app:create-admin',
    description: 'Creates an admin user',
    hidden: false,
)]
class CreateAdminCommand extends Command
{
    private const OPTION_NAME = 'name';

    public function __construct(
        private readonly EntityManagerInterface $entityManager,
        private readonly AccessTokenRepository $accessTokenRepository,
        private readonly GroupRepository $groupRepository
    ) {
        parent::__construct();
    }

    /**
     * @return void
     */
    protected function configure(): void
    {
        $this->addOption(
            self::OPTION_NAME,
            null,
            InputOption::VALUE_OPTIONAL,
            'User\'s name',
            'admin',
        );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $user = (new User())->setName($input->getOption(self::OPTION_NAME));
        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $groupAdmin = $this->groupRepository->getByName(Group::GROUP_NAME_ADMIN);
        if (!$groupAdmin) {
            $groupAdmin = (new Group())->setName('admin');
            $this->entityManager->persist($groupAdmin);
            $this->entityManager->flush();
        }

        $groupAdmin->addUser($user);
        $this->entityManager->persist($groupAdmin);
        $this->entityManager->flush();

        $token = $this->accessTokenRepository->findByUser($user);
        $output->writeln(sprintf('User %d %s', $token->getUser()->getId(), $token->getUser()->getName()));
        $output->writeln(sprintf('Token %s', $token->getToken()));

        return Command::SUCCESS;
    }
}
