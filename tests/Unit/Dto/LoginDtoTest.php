<?php

declare(strict_types=1);

namespace App\Tests\Unit\Dto;

use App\Dto\Request\LoginDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validation;

class LoginDtoTest extends TestCase
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
        $dto = new LoginDto(
            email: 'test@example.com',
            password: 'password123'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(0, $violations);
    }

    public function testEmailCannotBeBlank(): void
    {
        $dto = new LoginDto(
            email: '',
            password: 'password123'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('El email es obligatorio', $violations[0]->getMessage());
    }

    public function testEmailMustBeValid(): void
    {
        $dto = new LoginDto(
            email: 'not-an-email',
            password: 'password123'
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('El email no es válido', $violations[0]->getMessage());
    }

    public function testPasswordCannotBeBlank(): void
    {
        $dto = new LoginDto(
            email: 'test@example.com',
            password: ''
        );

        $violations = $this->validator->validate($dto);

        $this->assertCount(1, $violations);
        $this->assertEquals('La contraseña es obligatoria', $violations[0]->getMessage());
    }
}
