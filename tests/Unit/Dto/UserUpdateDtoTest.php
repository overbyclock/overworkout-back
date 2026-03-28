<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\UserUpdateDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class UserUpdateDtoTest extends TestCase
{
    private $validator;

    protected function setUp(): void
    {
        $this->validator = Validation::createValidatorBuilder()
            ->enableAttributeMapping()
            ->getValidator();
    }

    public function testAllFieldsOptional(): void
    {
        $dto = new UserUpdateDto();

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
        $this->assertFalse($dto->hasChanges());
    }

    public function testHasChangesWhenNickProvided(): void
    {
        $dto = new UserUpdateDto(nick: 'NewNick');

        $this->assertTrue($dto->hasChanges());
    }

    public function testHasChangesWhenEmailProvided(): void
    {
        $dto = new UserUpdateDto(email: 'new@example.com');

        $this->assertTrue($dto->hasChanges());
    }

    public function testHasChangesWhenAvatarProvided(): void
    {
        $dto = new UserUpdateDto(avatar: 'https://example.com/new.png');

        $this->assertTrue($dto->hasChanges());
    }

    public function testHasChangesWhenPasswordProvided(): void
    {
        $dto = new UserUpdateDto(password: 'NewPass123');

        $this->assertTrue($dto->hasChanges());
    }

    public function testNickValidation(): void
    {
        $dto = new UserUpdateDto(nick: 'ab');

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('3 caracteres', $violations[0]->getMessage());
    }

    public function testNickMaxLength(): void
    {
        $dto = new UserUpdateDto(nick: str_repeat('a', 51));

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('50 caracteres', $violations[0]->getMessage());
    }

    public function testEmailValidation(): void
    {
        $dto = new UserUpdateDto(email: 'invalid-email');

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('El email no es válido', $violations[0]->getMessage());
    }

    public function testPasswordTooShort(): void
    {
        $dto = new UserUpdateDto(password: 'Pass1');

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('6 caracteres', $violations[0]->getMessage());
    }

    public function testPasswordRequiresUppercase(): void
    {
        $dto = new UserUpdateDto(password: 'password123');

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('mayúscula', $violations[0]->getMessage());
    }

    public function testPasswordRequiresNumber(): void
    {
        $dto = new UserUpdateDto(password: 'PasswordABC');

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertStringContainsString('número', $violations[0]->getMessage());
    }

    public function testValidUpdateWithAllFields(): void
    {
        $dto = new UserUpdateDto(
            nick: 'ValidNick',
            email: 'valid@example.com',
            avatar: 'https://example.com/avatar.png',
            password: 'ValidPass123'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
        $this->assertTrue($dto->hasChanges());
    }
}
