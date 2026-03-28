<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\UserRegistrationDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class UserRegistrationDtoTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testValidDto(): void
    {
        $dto = new UserRegistrationDto(
            nick: 'TestUser',
            email: 'test@example.com',
            password: 'Password123',
            avatar: 'https://example.com/avatar.png'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testNickCannotBeBlank(): void
    {
        $dto = new UserRegistrationDto(
            nick: '',
            email: 'test@example.com',
            password: 'Password123'
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
        $this->assertEquals('El nick es obligatorio', $violations[0]->getMessage());
    }

    public function testNickTooShort(): void
    {
        $dto = new UserRegistrationDto(
            nick: 'ab',
            email: 'test@example.com',
            password: 'Password123'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('3 caracteres', $violations[0]->getMessage());
    }

    public function testNickTooLong(): void
    {
        $dto = new UserRegistrationDto(
            nick: str_repeat('a', 51),
            email: 'test@example.com',
            password: 'Password123'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('50 caracteres', $violations[0]->getMessage());
    }

    public function testEmailCannotBeBlank(): void
    {
        $dto = new UserRegistrationDto(
            nick: 'TestUser',
            email: '',
            password: 'Password123'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('El email es obligatorio', $violations[0]->getMessage());
    }

    public function testEmailMustBeValid(): void
    {
        $dto = new UserRegistrationDto(
            nick: 'TestUser',
            email: 'invalid-email',
            password: 'Password123'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('El email no es válido', $violations[0]->getMessage());
    }

    public function testPasswordCannotBeBlank(): void
    {
        $dto = new UserRegistrationDto(
            nick: 'TestUser',
            email: 'test@example.com',
            password: ''
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThanOrEqual(1, \count($violations));
        $this->assertEquals('La contraseña es obligatoria', $violations[0]->getMessage());
    }

    public function testPasswordTooShort(): void
    {
        $dto = new UserRegistrationDto(
            nick: 'TestUser',
            email: 'test@example.com',
            password: 'Pass1'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('6 caracteres', $violations[0]->getMessage());
    }

    public function testPasswordMustContainUppercase(): void
    {
        $dto = new UserRegistrationDto(
            nick: 'TestUser',
            email: 'test@example.com',
            password: 'password123'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('mayúscula', $violations[0]->getMessage());
    }

    public function testPasswordMustContainLowercase(): void
    {
        $dto = new UserRegistrationDto(
            nick: 'TestUser',
            email: 'test@example.com',
            password: 'PASSWORD123'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('minúscula', $violations[0]->getMessage());
    }

    public function testPasswordMustContainNumber(): void
    {
        $dto = new UserRegistrationDto(
            nick: 'TestUser',
            email: 'test@example.com',
            password: 'PasswordABC'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('número', $violations[0]->getMessage());
    }

    public function testAvatarIsOptional(): void
    {
        $dto = new UserRegistrationDto(
            nick: 'TestUser',
            email: 'test@example.com',
            password: 'Password123',
            avatar: null
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testMultipleValidationErrors(): void
    {
        $dto = new UserRegistrationDto(
            nick: '',
            email: 'invalid',
            password: '123'
        );

        $violations = $this->validator->validate($dto);

        $this->assertGreaterThan(1, \count($violations));
    }
}
