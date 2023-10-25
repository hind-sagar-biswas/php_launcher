<?php

namespace Shell\Database\Seed;

use Core\Db\TableSeeder;
use Core\Security\Random;

class UsersSeed implements TableSeeder
{
    public function seeds(): array
    {
        $seeds = [
            [
                "uid" => Random::uid(),
                "username" => 'admin',
                "phone" => "12345",
                "email" => "admin@root.com",
                "is_superadmin" => 1,
                "password" => 'root',

            ],
        ];

        for ($i = 0; $i < 20; $i++) {
            $name = Random::fullName();
            $seeds[] = [
                "uid" => Random::uid(),
                "username" => Random::username($name),
                "email" => Random::email($name),
                "phone" => Random::phoneNumber(),
                "password" => 'root',
            ];
        }

        return $seeds;
    }
}
