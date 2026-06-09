<?php

declare(strict_types=1);

namespace App\Tests\Unit\Security\Voter;

use App\Entity\User;
use App\Entity\UserBenchmarkAttempt;
use App\Repository\UserBenchmarkAttemptRepository;
use App\Security\Voter\BenchmarkAttemptVoter;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\VoterInterface;

class BenchmarkAttemptVoterTest extends TestCase
{
    private BenchmarkAttemptVoter $voter;

    private $entityManager;

    private $attemptRepository;

    private $token;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->attemptRepository = $this->createMock(UserBenchmarkAttemptRepository::class);
        $this->token = $this->createMock(TokenInterface::class);

        $this->entityManager
            ->method('getRepository')
            ->with(UserBenchmarkAttempt::class)
            ->willReturn($this->attemptRepository);

        $this->voter = new BenchmarkAttemptVoter($this->entityManager);
    }

    private function createUser(int $id): User
    {
        $user = new User();
        $reflection = new \ReflectionClass($user);
        $property = $reflection->getProperty('id');
        $property->setValue($user, $id);

        return $user;
    }

    private function createAttempt(User $user, string $status = UserBenchmarkAttempt::STATUS_IN_PROGRESS): UserBenchmarkAttempt
    {
        $attempt = new UserBenchmarkAttempt();
        $attempt->setUser($user);
        $attempt->setStatus($status);
        $reflection = new \ReflectionClass($attempt);
        $property = $reflection->getProperty('id');
        $property->setValue($attempt, 1);

        return $attempt;
    }

    public function testSupportsView(): void
    {
        $result = (new \ReflectionMethod($this->voter, 'supports'))->invoke($this->voter, BenchmarkAttemptVoter::VIEW, 1);
        $this->assertTrue($result);
    }

    public function testSupportsEdit(): void
    {
        $result = (new \ReflectionMethod($this->voter, 'supports'))->invoke($this->voter, BenchmarkAttemptVoter::EDIT, 1);
        $this->assertTrue($result);
    }

    public function testSupportsDelete(): void
    {
        $result = (new \ReflectionMethod($this->voter, 'supports'))->invoke($this->voter, BenchmarkAttemptVoter::DELETE, 1);
        $this->assertTrue($result);
    }

    public function testSupportsListOwn(): void
    {
        $result = (new \ReflectionMethod($this->voter, 'supports'))->invoke($this->voter, BenchmarkAttemptVoter::LIST_OWN, null);
        $this->assertTrue($result);
    }

    public function testDoesNotSupportRandomAttribute(): void
    {
        $result = (new \ReflectionMethod($this->voter, 'supports'))->invoke($this->voter, 'RANDOM', 1);
        $this->assertFalse($result);
    }

    public function testViewGrantedToOwner(): void
    {
        $user = $this->createUser(1);
        $attempt = $this->createAttempt($user);

        $this->token->method('getUser')->willReturn($user);
        $this->attemptRepository->method('find')->with(1)->willReturn($attempt);

        $result = $this->voter->vote($this->token, 1, [BenchmarkAttemptVoter::VIEW]);
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testViewDeniedToOtherUser(): void
    {
        $owner = $this->createUser(1);
        $otherUser = $this->createUser(2);
        $attempt = $this->createAttempt($owner);

        $this->token->method('getUser')->willReturn($otherUser);
        $this->attemptRepository->method('find')->with(1)->willReturn($attempt);

        $result = $this->voter->vote($this->token, 1, [BenchmarkAttemptVoter::VIEW]);
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testViewDeniedWhenAttemptNotFound(): void
    {
        $user = $this->createUser(1);

        $this->token->method('getUser')->willReturn($user);
        $this->attemptRepository->method('find')->with(999)->willReturn(null);

        $result = $this->voter->vote($this->token, 999, [BenchmarkAttemptVoter::VIEW]);
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testEditGrantedToOwner(): void
    {
        $user = $this->createUser(1);
        $attempt = $this->createAttempt($user);

        $this->token->method('getUser')->willReturn($user);
        $this->attemptRepository->method('find')->with(1)->willReturn($attempt);

        $result = $this->voter->vote($this->token, 1, [BenchmarkAttemptVoter::EDIT]);
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testDeleteGrantedToOwnerWhenInProgress(): void
    {
        $user = $this->createUser(1);
        $attempt = $this->createAttempt($user, UserBenchmarkAttempt::STATUS_IN_PROGRESS);

        $this->token->method('getUser')->willReturn($user);
        $this->attemptRepository->method('find')->with(1)->willReturn($attempt);

        $result = $this->voter->vote($this->token, 1, [BenchmarkAttemptVoter::DELETE]);
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testDeleteDeniedWhenCompleted(): void
    {
        $user = $this->createUser(1);
        $attempt = $this->createAttempt($user, UserBenchmarkAttempt::STATUS_COMPLETED);

        $this->token->method('getUser')->willReturn($user);
        $this->attemptRepository->method('find')->with(1)->willReturn($attempt);

        $result = $this->voter->vote($this->token, 1, [BenchmarkAttemptVoter::DELETE]);
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testListOwnGrantedToAnyAuthenticatedUser(): void
    {
        $user = $this->createUser(1);

        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, null, [BenchmarkAttemptVoter::LIST_OWN]);
        $this->assertEquals(VoterInterface::ACCESS_GRANTED, $result);
    }

    public function testDeniedWhenNotAuthenticated(): void
    {
        $this->token->method('getUser')->willReturn(null);

        $result = $this->voter->vote($this->token, 1, [BenchmarkAttemptVoter::VIEW]);
        $this->assertEquals(VoterInterface::ACCESS_DENIED, $result);
    }

    public function testAbstainForUnsupportedAttribute(): void
    {
        $user = $this->createUser(1);
        $this->token->method('getUser')->willReturn($user);

        $result = $this->voter->vote($this->token, 1, ['UNSUPPORTED']);
        $this->assertEquals(VoterInterface::ACCESS_ABSTAIN, $result);
    }
}
