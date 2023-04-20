<?php

namespace Unit\Security;

use App\Entity\AccessToken;
use App\Entity\User;
use App\Repository\AccessTokenRepository;
use App\Security\AccessTokenHandler;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

class AccessTokenHandlerTest extends TestCase
{
    public function testGetUserBadgeFrom(): void
    {
        $user = new User();
        $user
            ->setId(333)
            ->setName('John Doe');

        $accessToken = new AccessToken();
        $accessToken->setUser($user);
        $accessToken->setToken('valid_token');

        $repository = $this->createMock(AccessTokenRepository::class);
        $repository->expects($this->once())
            ->method('findByToken')
            ->with('valid_token')
            ->willReturn($accessToken);

        $handler = new AccessTokenHandler($repository);

        $userBadge = $handler->getUserBadgeFrom('valid_token');

        $this->assertEquals($user->getId(), $userBadge->getUserIdentifier());
    }

    public function testGetUserBadgeFromInvalidToken(): void
    {
        $repository = $this->createMock(AccessTokenRepository::class);
        $repository->expects($this->once())
            ->method('findByToken')
            ->with('invalid_token')
            ->willReturn(null);

        $handler = new AccessTokenHandler($repository);

        $this->expectException(BadCredentialsException::class);
        $this->expectExceptionMessage('Invalid credentials.');

        $handler->getUserBadgeFrom('invalid_token');
    }
}
