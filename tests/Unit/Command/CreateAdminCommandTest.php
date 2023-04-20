<?php

namespace Unit\Command;

use App\Command\CreateAdminCommand;
use App\Entity\AccessToken;
use App\Entity\Group;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Repository\GroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateAdminCommandTest extends KernelTestCase
{
    public function testExecute(): void
    {
        $kernel = static::createKernel();
        $application = new Application($kernel);

        $entityManager = $this->getMockBuilder(EntityManagerInterface::class)
            ->disableOriginalConstructor()
            ->getMock();

        $accessTokenRepository = $this->getMockBuilder(AccessTokenRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $groupRepository = $this->getMockBuilder(GroupRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $command = new CreateAdminCommand($entityManager, $accessTokenRepository, $groupRepository);
        $application->add($command);

        $commandTester = new CommandTester($command);
        $user = new User();
        $user
            ->setName('admin')
            ->setId(1)
        ;

        $group = new Group();
        $group->setName('admin');

        $token = new AccessToken();
        $token->setUser($user);
        $token->setToken('sample-token');

        $entityManager->expects($this->exactly(2))->method('persist');
        $entityManager->expects($this->exactly(2))->method('flush');

        $groupRepository->method('getByName')->willReturn($group);

        $accessTokenRepository->method('findByUser')->willReturn($token);

        $commandTester->execute([]);

        $output = $commandTester->getDisplay();
        $this->assertStringContainsString('User 1 admin', $output);
        $this->assertStringContainsString('Token sample-token', $output);
    }
}
