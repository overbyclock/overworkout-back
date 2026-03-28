<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mapper;

use App\Dto\Request\UserRegistrationDto;
use App\Dto\Request\UserUpdateDto;
use App\Entity\User;
use App\Mapper\UserMapper;
use App\Service\UserPasswordHashService;
use PHPUnit\Framework\TestCase;

class UserMapperTest extends TestCase
{
    private UserMapper $mapper;

    private $passwordHashService;

    protected function setUp(): void
    {
        $this->passwordHashService = $this->createMock(UserPasswordHashService::class);
        $this->mapper = new UserMapper($this->passwordHashService);
    }

    public function testFromRegistrationDtoCreatesUser(): void
    {
        $dto = new UserRegistrationDto(
            nick: 'TestUser',
            email: 'test@example.com',
            password: 'Password123',
            avatar: 'https://example.com/avatar.png'
        );

        $user = $this->mapper->fromRegistrationDto($dto);

        $this->assertInstanceOf(User::class, $user);
        $this->assertEquals('TestUser', $user->getNick());
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('https://example.com/avatar.png', $user->getAvatar());
        $this->assertNotNull($user->getCreatedAt());
    }

    public function testFromRegistrationDtoWithNullAvatar(): void
    {
        $dto = new UserRegistrationDto(
            nick: 'TestUser',
            email: 'test@example.com',
            password: 'Password123',
            avatar: null
        );

        $user = $this->mapper->fromRegistrationDto($dto);

        $this->assertNull($user->getAvatar());
    }

    public function testUpdateFromDtoUpdatesNick(): void
    {
        $user = new User();
        $user->setNick('OldNick');
        $dto = new UserUpdateDto(nick: 'NewNick');

        $this->mapper->updateFromDto($user, $dto);

        $this->assertEquals('NewNick', $user->getNick());
    }

    public function testUpdateFromDtoUpdatesEmail(): void
    {
        $user = new User();
        $user->setEmail('old@example.com');
        $dto = new UserUpdateDto(email: 'new@example.com');

        $this->mapper->updateFromDto($user, $dto);

        $this->assertEquals('new@example.com', $user->getEmail());
    }

    public function testUpdateFromDtoUpdatesAvatar(): void
    {
        $user = new User();
        $user->setAvatar('old.png');
        $dto = new UserUpdateDto(avatar: 'new.png');

        $this->mapper->updateFromDto($user, $dto);

        $this->assertEquals('new.png', $user->getAvatar());
    }

    public function testUpdateFromDtoUpdatesPassword(): void
    {
        $user = new User();
        $hashedPassword = 'hashed_password_123';

        $this->passwordHashService
            ->expects($this->once())
            ->method('hashPassword')
            ->with($user, 'NewPass123')
            ->willReturn($hashedPassword);

        $dto = new UserUpdateDto(password: 'NewPass123');

        $this->mapper->updateFromDto($user, $dto);

        $this->assertEquals($hashedPassword, $user->getPassword());
    }

    public function testUpdateFromDtoDoesNotChangeNullFields(): void
    {
        $user = new User();
        $user->setNick('OriginalNick');
        $user->setEmail('original@example.com');
        $user->setAvatar('original.png');

        $dto = new UserUpdateDto();

        $this->mapper->updateFromDto($user, $dto);

        $this->assertEquals('OriginalNick', $user->getNick());
        $this->assertEquals('original@example.com', $user->getEmail());
        $this->assertEquals('original.png', $user->getAvatar());
    }

    public function testUpdateFromDtoUpdatesMultipleFields(): void
    {
        $user = new User();
        $user->setNick('OldNick');
        $user->setEmail('old@example.com');

        $this->passwordHashService
            ->method('hashPassword')
            ->willReturn('hashed_pass');

        $dto = new UserUpdateDto(
            nick: 'NewNick',
            email: 'new@example.com',
            password: 'NewPass123'
        );

        $this->mapper->updateFromDto($user, $dto);

        $this->assertEquals('NewNick', $user->getNick());
        $this->assertEquals('new@example.com', $user->getEmail());
        $this->assertEquals('hashed_pass', $user->getPassword());
    }
}
