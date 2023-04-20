<?php

namespace Unit\Entity;

use App\Entity\Group;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{
    public function testGroupCreation(): void
    {
        $group = new Group();
        $group->setName('testGroup');

        $this->assertNull($group->getId());
        $this->assertEquals('testGroup', $group->getName());
        $this->assertEquals('ROLE_TESTGROUP', $group->getRole());
    }

    public function testAddUser(): void
    {
        $user = new User();
        $user->setName('John Doe');

        $group = new Group();
        $group->setName('testGroup');

        $group->addUser($user);

        $this->assertCount(1, $group->getUsers());
        $this->assertTrue($group->getUsers()->contains($user));
    }

    public function testRemoveUser(): void
    {
        $user = new User();
        $user->setName('John Doe');

        $group = new Group();
        $group->setName('testGroup');
        $group->addUser($user);

        $group->removeUser($user);

        $this->assertCount(0, $group->getUsers());
        $this->assertFalse($group->getUsers()->contains($user));
    }

    public function testProcessGroupName(): void
    {
        $group = new Group();
        $group->setName('TestGroup');
        $reflection = new \ReflectionClass($group);

        $method = $reflection->getMethod('processGroupName');
        $method->setAccessible(true);
        $method->invoke($group);

        $this->assertEquals('testgroup', $group->getName());
    }
}
