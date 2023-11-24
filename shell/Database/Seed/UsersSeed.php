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
                "username" => 'admin',
                "phone" => "12345",
                "email" => "admin@root.com",
                "is_superadmin" => 1,
                "password" => '4813494d137e1631bba301d5acab6e7bb7aa74ce1185d456565ef51d737677b2', // root
            ],
        ];

        for ($i = 0; $i < 20; $i++) {
            $name = Random::fullName();
            $seeds[] = [
                "username" => Random::username($name),
                "email" => Random::email($name),
                "phone" => Random::phoneNumber(),
                "password" => '5e884898da28047151d0e56f8dc6292773603d0d6aabbdd62a11ef721d1542d8', // password
            ];
        }

        return $seeds;
    }
}
