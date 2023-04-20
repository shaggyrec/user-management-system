<?php
namespace App\Security;

use App\Repository\AccessTokenRepository;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;
use Symfony\Component\Security\Http\AccessToken\AccessTokenHandlerInterface;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;

/**
 * Class AccessTokenHandler
 *
 * @package App\Security
 */
class AccessTokenHandler implements AccessTokenHandlerInterface
{
    /**
     * @param AccessTokenRepository $repository
     */
    public function __construct(
        private readonly AccessTokenRepository $repository
    ) {
    }

    /**
     * @param string $accessToken
     * @return UserBadge
     */
    public function getUserBadgeFrom(string $accessToken): UserBadge
    {
        $accessToken = $this->repository->findByToken($accessToken);
        if (null === $accessToken || !$accessToken->isValid()) {
            throw new BadCredentialsException('Invalid credentials.');
        }

        // and return a UserBadge object containing the user identifier from the found token
        return new UserBadge(
            $accessToken->getUser()->getId(),
            fn() => $accessToken->getUser(),
        );
    }
}
