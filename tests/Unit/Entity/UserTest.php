<?php

namespace Unit\Entity;

use App\Entity\Group;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUser(): void
    {
        $user = new User();
        $user->setName('John Doe');

        $this->assertNotNull($user);
        $this->assertEquals('John Doe', $user->getName());

        $group = new Group();
        $group->setName('Admin');

        $user->addGroup($group);

        $this->assertCount(1, $user->getGroups());
        $this->assertTrue($user->getGroups()->contains($group));

        $user->removeGroup($group);

        $this->assertCount(0, $user->getGroups());
        $this->assertFalse($user->getGroups()->contains($group));

        $roles = $user->getRoles();
        $this->assertContains('ROLE_USER', $roles);
    }
}
