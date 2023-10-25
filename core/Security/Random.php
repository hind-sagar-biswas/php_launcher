<?php

namespace Core\Security;

class Random {
    public static function key(): string {
        return uniqid(rand(), true);
    }

    public static function hex(): string {
        return bin2hex(random_bytes(32));
    }

    public static function numString(int $len): string {
        $num = "";
        for ($i = 0; $i < $len; $i++) {
            $num .= rand(0, 9);
        }
        return $num;
    }

    public static function uid(int $length = 10)
    {
        $str = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $salt = bin2hex(openssl_random_pseudo_bytes(6));
        $characters = str_shuffle($str . $salt);
        $randomString = substr($characters, 0, $length);
        return $randomString;
    }

    public static function firstName(): string
    {
        $data = json_decode(file_get_contents(ROOTPATH . 'core/Data/first_name.json'), true);
        return $data['data'][rand(0, $data['total'] - 1)];
    }

    public static function lastName(): string
    {
        $data = json_decode(file_get_contents(ROOTPATH . 'core/Data/first_name.json'), true);
        return $data['data'][rand(0, $data['total'] - 1)];
    }

    public static function fullName(): string {
        return self::firstName() . ' ' . self::lastName();
    }

    public static function domain(): string {
        $data = json_decode(file_get_contents(ROOTPATH . 'core/Data/tld.json'), true);
        $tld = $data['data'][rand(0, $data['total'] - 1)];

        $word = str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyz', 3));
        $word = substr($word, 0, rand(3, 6));

        return $word . '.' . $tld;
    }

    public static function username(string|null $name = null): string {
        $name ??= self::fullName();
        return strtolower(str_replace(' ', '.', $name)) . random_int(1, 999);
    }

    public static function email(string|null $name = null): string {
        $name ??= self::fullName();
        return strtolower(str_replace(' ', '.', $name)) . random_int(1, 999) . '@'. self::domain();
    }

    public static function phoneNumber(string|null $country = null): string {
        $data = json_decode(file_get_contents(ROOTPATH . 'core/Data/country_moba.json'), true)['data'];
        
        if ($country !== null) {
            $country = strtolower($country);

            if (!array_key_exists($country, $data)) {
                d("Country `$country` not in datalist. Choosing a random country.", "ERROR");
                $country = self::country();
            }
        }
        else $country = self::country();
        $moba = $data[$country];
        
        return $moba['code'] . self::numString($moba['size']);
    }

    public static function country()
    {
        $data = json_decode(file_get_contents(ROOTPATH . 'core/Data/country.json'), true);
        return $data['data'][rand(0, $data['total'] - 1)];
    }

    public static function person() {
        $fName = self::firstName();
        $lName = self::lastName();
        $country = self::country();

        return [ 
            'first_name' => $fName,
            'last_name' => $lName, 
            'country' => $country,
            'website' => self::domain(),
            'email' => self::email("$fName $lName"),
            'number' => self::phoneNumber($country),
        ];
    }

    public static function choice(array $options)
    {
        return $options[array_rand($options, 1)];
    }

    public static function commodity()
    {
        $data = json_decode(file_get_contents(ROOTPATH . 'core/Data/commodities.json'), true);
        return $data['data'][rand(0, $data['total'] - 1)];
    }

    public static function equipment()
    {
        $data = json_decode(file_get_contents(ROOTPATH . 'core/Data/equipments.json'), true);
        $ict = $data['ict'];
        $science = $data['science'];
        $diagnosis = $data['diagnosis'];

        $full = [...$ict, ...$science, ...$diagnosis];
        return self::choice($full);
    }

    public static function ictEquipment()
    {
        $data = json_decode(file_get_contents(ROOTPATH . 'core/Data/equipments.json'), true);
        return self::choice($data['ict']);
    }

    public static function scienceEquipment()
    {
        $data = json_decode(file_get_contents(ROOTPATH . 'core/Data/equipments.json'), true);
        return self::choice($data['science']);
    }

    public static function diagnosisEquipment()
    {
        $data = json_decode(file_get_contents(ROOTPATH . 'core/Data/equipments.json'), true);
        return self::choice($data['diagnosis']);
    }
}