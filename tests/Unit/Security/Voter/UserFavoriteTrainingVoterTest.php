<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security\Voter;

use App\Entity\User;
use App\Entity\UserFavoriteTraining;
use App\Security\Voter\UserFavoriteTrainingVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

final class UserFavoriteTrainingVoterTest extends TestCase
{
    private UserFavoriteTrainingVoter $voter;

    protected function setUp(): void
    {
        $this->voter = new UserFavoriteTrainingVoter();
    }

    public function testOwnerCanManageFavorite(): void
    {
        $user = new User();
        $user->setNick('owner');
        $user->setEmail('owner@test.com');

        $favorite = new UserFavoriteTraining();
        $favorite->setUser($user);

        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());

        self::assertSame(Voter::ACCESS_GRANTED, $this->voter->vote($token, $favorite, [UserFavoriteTrainingVoter::MANAGE]));
    }

    public function testNonOwnerCannotManageFavorite(): void
    {
        $owner = new User();
        $owner->setNick('owner');
        $owner->setEmail('owner@test.com');

        $other = new User();
        $other->setNick('other');
        $other->setEmail('other@test.com');

        $favorite = new UserFavoriteTraining();
        $favorite->setUser($owner);

        $token = new UsernamePasswordToken($other, 'main', $other->getRoles());

        self::assertSame(Voter::ACCESS_DENIED, $this->voter->vote($token, $favorite, [UserFavoriteTrainingVoter::MANAGE]));
    }

    public function testAbstainForUnsupportedAttribute(): void
    {
        $user = new User();
        $user->setNick('owner');
        $user->setEmail('owner@test.com');

        $favorite = new UserFavoriteTraining();
        $favorite->setUser($user);

        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());

        self::assertSame(Voter::ACCESS_ABSTAIN, $this->voter->vote($token, $favorite, ['SOME_OTHER_ATTRIBUTE']));
    }

    public function testAccessDeniedWhenNotAuthenticated(): void
    {
        $user = new User();
        $user->setNick('owner');
        $user->setEmail('owner@test.com');

        $favorite = new UserFavoriteTraining();
        $favorite->setUser($user);

        $token = $this->createStub(TokenInterface::class);
        $token->method('getUser')->willReturn(null);

        self::assertSame(Voter::ACCESS_DENIED, $this->voter->vote($token, $favorite, [UserFavoriteTrainingVoter::MANAGE]));
    }

    public function testNullIdFallbackSameUserObjectGrantsAccess(): void
    {
        $user = new User();
        $user->setNick('owner');
        $user->setEmail('owner@test.com');

        $favorite = new UserFavoriteTraining();
        $favorite->setUser($user);

        $token = new UsernamePasswordToken($user, 'main', $user->getRoles());

        self::assertSame(Voter::ACCESS_GRANTED, $this->voter->vote($token, $favorite, [UserFavoriteTrainingVoter::MANAGE]));
    }

    public function testNullIdFallbackDifferentUserObjectsDeniesAccess(): void
    {
        $owner = new User();
        $owner->setNick('owner');
        $owner->setEmail('owner@test.com');

        $other = new User();
        $other->setNick('other');
        $other->setEmail('other@test.com');

        $favorite = new UserFavoriteTraining();
        $favorite->setUser($owner);

        $token = new UsernamePasswordToken($other, 'main', $other->getRoles());

        self::assertSame(Voter::ACCESS_DENIED, $this->voter->vote($token, $favorite, [UserFavoriteTrainingVoter::MANAGE]));
    }
}
