<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security\Voter;

use App\Entity\Training;
use App\Entity\User;
use App\Security\Voter\TrainingVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class TrainingVoterTest extends TestCase
{
    private TrainingVoter $voter;

    private $token;

    protected function setUp(): void
    {
        $this->voter = new TrainingVoter();
        $this->token = $this->createMock(TokenInterface::class);
    }

    public function testViewPublicTraining(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);
        $training = new Training();
        $training->setTrainingUser(null); // Entrenamiento público

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, $training, [TrainingVoter::VIEW]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testViewOwnTraining(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);
        $training = new Training();
        $training->setTrainingUser($user);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, $training, [TrainingVoter::VIEW]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testViewOtherTrainingAsAdmin(): void
    {
        $admin = $this->createUser(1, ['ROLE_ADMIN']);
        $owner = $this->createUser(2, ['ROLE_USER']);
        $training = new Training();
        $training->setTrainingUser($owner);

        $this->token->method('getUser')->willReturn($admin);

        $result = $this->voter->vote($this->token, $training, [TrainingVoter::VIEW]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testViewOtherTrainingAsNonAdmin(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);
        $owner = $this->createUser(2, ['ROLE_USER']);
        $training = new Training();
        $training->setTrainingUser($owner);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, $training, [TrainingVoter::VIEW]);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testCreateAllowedForAuthenticatedUser(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, null, [TrainingVoter::CREATE]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testEditOwnTraining(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);
        $training = new Training();
        $training->setTrainingUser($user);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, $training, [TrainingVoter::EDIT]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testEditOtherTrainingAsAdmin(): void
    {
        $admin = $this->createUser(1, ['ROLE_ADMIN']);
        $owner = $this->createUser(2, ['ROLE_USER']);
        $training = new Training();
        $training->setTrainingUser($owner);

        $this->token->method('getUser')->willReturn($admin);

        $result = $this->voter->vote($this->token, $training, [TrainingVoter::EDIT]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testEditOtherTrainingAsNonAdmin(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);
        $owner = $this->createUser(2, ['ROLE_USER']);
        $training = new Training();
        $training->setTrainingUser($owner);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, $training, [TrainingVoter::EDIT]);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testDeleteOwnTraining(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);
        $training = new Training();
        $training->setTrainingUser($user);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, $training, [TrainingVoter::DELETE]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testDeleteOtherTrainingAsNonAdmin(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);
        $owner = $this->createUser(2, ['ROLE_USER']);
        $training = new Training();
        $training->setTrainingUser($owner);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, $training, [TrainingVoter::DELETE]);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testListAllAsAdmin(): void
    {
        $admin = $this->createUser(1, ['ROLE_ADMIN']);

        $this->token->method('getUser')->willReturn($admin);

        $result = $this->voter->vote($this->token, null, [TrainingVoter::LIST_ALL]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testListAllAsNonAdmin(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, null, [TrainingVoter::LIST_ALL]);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testListUserOwnTrainings(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, 1, [TrainingVoter::LIST_USER]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testListUserOtherTrainingsAsAdmin(): void
    {
        $admin = $this->createUser(1, ['ROLE_ADMIN']);

        $this->token->method('getUser')->willReturn($admin);

        $result = $this->voter->vote($this->token, 2, [TrainingVoter::LIST_USER]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testListUserOtherTrainingsAsNonAdmin(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, 2, [TrainingVoter::LIST_USER]);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testAccessDeniedWhenNotAuthenticated(): void
    {
        $this->token->method('getUser')->willReturn(null);

        $result = $this->voter->vote($this->token, new Training(), [TrainingVoter::VIEW]);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testAbstainForUnsupportedAttribute(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, new Training(), ['UNSUPPORTED']);

        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $result);
    }

    private function createUser(int $id, array $roles): User
    {
        $user = new User();
        $user->setNick('User'.$id);
        $user->setEmail('user'.$id.'@example.com');
        $user->setPassword('password');
        $user->setRoles($roles);

        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('id');

        $property->setValue($user, $id);

        return $user;
    }
}
