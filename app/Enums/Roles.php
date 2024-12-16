<?php

namespace App\Enums;

enum Roles: int
{
    case ADMIN = 1;
    case PROFESSOR = 2;
    case ASSISTANT = 3;

    public function role(): string
    {
        return match ($this) {
            self::ADMIN => 'Administrador(a)',
            self::PROFESSOR => 'Professor(a)',
            self::ASSISTANT => 'Colaborador(a)',
        };
    }

    public static function allRoles(): array
    {
        $roles = [];
        foreach (Roles::cases() as $role) {
            $roles[] = array(
                'name' => Roles::tryFrom($role->value)->role(),
                'id' => $role->value,
            );
        }

        return $roles;
    }

    public static function random(): self
    {
        $count = count(self::cases()) - 1;

        return self::cases()[rand(0, $count)];
    }
}
