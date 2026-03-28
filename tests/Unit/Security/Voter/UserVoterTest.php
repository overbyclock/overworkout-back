<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security\Voter;

use App\Entity\User;
use App\Security\Voter\UserVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class UserVoterTest extends TestCase
{
    private UserVoter $voter;
    private $token;

    protected function setUp(): void
    {
        $this->voter = new UserVoter();
        $this->token = $this->createMock(TokenInterface::class);
    }

    public function testViewOwnProfile(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, $user, [UserVoter::VIEW]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testViewOtherUserAsAdmin(): void
    {
        $admin = $this->createUser(1, ['ROLE_ADMIN']);
        $otherUser = $this->createUser(2, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($admin);

        $result = $this->voter->vote($this->token, $otherUser, [UserVoter::VIEW]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testViewOtherUserAsNonAdmin(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);
        $otherUser = $this->createUser(2, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, $otherUser, [UserVoter::VIEW]);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testEditAsAdmin(): void
    {
        $admin = $this->createUser(1, ['ROLE_ADMIN']);
        $targetUser = $this->createUser(2, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($admin);

        $result = $this->voter->vote($this->token, $targetUser, [UserVoter::EDIT]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testEditAsNonAdmin(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);
        $targetUser = $this->createUser(2, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, $targetUser, [UserVoter::EDIT]);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testDeleteOwnAccount(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, $user, [UserVoter::DELETE]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testDeleteOtherAsAdmin(): void
    {
        $admin = $this->createUser(1, ['ROLE_ADMIN']);
        $targetUser = $this->createUser(2, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($admin);

        $result = $this->voter->vote($this->token, $targetUser, [UserVoter::DELETE]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testDeleteOtherAsNonAdmin(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);
        $targetUser = $this->createUser(2, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, $targetUser, [UserVoter::DELETE]);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testListAllAsAdmin(): void
    {
        $admin = $this->createUser(1, ['ROLE_ADMIN']);

        $this->token->method('getUser')->willReturn($admin);

        $result = $this->voter->vote($this->token, null, [UserVoter::LIST_ALL]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testListAllAsNonAdmin(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, null, [UserVoter::LIST_ALL]);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testAccessDeniedWhenNotAuthenticated(): void
    {
        $this->token->method('getUser')->willReturn(null);

        $result = $this->voter->vote($this->token, new User(), [UserVoter::VIEW]);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testAbstainForUnsupportedAttribute(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, $user, ['UNSUPPORTED_ATTRIBUTE']);

        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $result);
    }

    private function createUser(int $id, array $roles): User
    {
        $user = new User();
        $user->setNick('User' . $id);
        $user->setEmail('user' . $id . '@example.com');
        $user->setPassword('password');
        $user->setRoles($roles);

        // Usar reflexión para establecer el ID
        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('id');
        $property->setAccessible(true);
        $property->setValue($user, $id);

        return $user;
    }
}
