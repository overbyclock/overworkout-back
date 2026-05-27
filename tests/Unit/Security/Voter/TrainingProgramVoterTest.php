<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security\Voter;

use App\Entity\TrainingProgram;
use App\Entity\User;
use App\Security\Voter\TrainingProgramVoter;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class TrainingProgramVoterTest extends TestCase
{
    private TrainingProgramVoter $voter;

    private $token;

    protected function setUp(): void
    {
        $this->voter = new TrainingProgramVoter();
        $this->token = $this->createMock(TokenInterface::class);
    }

    public function testViewIsGrantedToAnyone(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);
        $program = new TrainingProgram();

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, $program, [TrainingProgramVoter::VIEW]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testListAllIsGrantedToAnyone(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, null, [TrainingProgramVoter::LIST_ALL]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testCreateRequiresAdmin(): void
    {
        $admin = $this->createUser(1, ['ROLE_ADMIN']);
        $this->token->method('getUser')->willReturn($admin);

        $result = $this->voter->vote($this->token, null, [TrainingProgramVoter::CREATE]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testCreateDeniedForNonAdmin(): void
    {
        $user = $this->createUser(1, ['ROLE_USER']);
        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, null, [TrainingProgramVoter::CREATE]);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testEditRequiresAdmin(): void
    {
        $admin = $this->createUser(1, ['ROLE_ADMIN']);
        $program = new TrainingProgram();

        $this->token->method('getUser')->willReturn($admin);

        $result = $this->voter->vote($this->token, $program, [TrainingProgramVoter::EDIT]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testDeleteRequiresAdmin(): void
    {
        $admin = $this->createUser(1, ['ROLE_ADMIN']);
        $program = new TrainingProgram();

        $this->token->method('getUser')->willReturn($admin);

        $result = $this->voter->vote($this->token, $program, [TrainingProgramVoter::DELETE]);

        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testDeniedWhenNotAuthenticated(): void
    {
        $this->token->method('getUser')->willReturn(null);

        $result = $this->voter->vote($this->token, null, [TrainingProgramVoter::CREATE]);

        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
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
