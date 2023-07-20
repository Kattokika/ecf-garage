<?php

namespace App\Service;

class PasswordGenerator
{
    public const KEYSPACE = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    public function generate_password(
        $length = 16,
    ): string
    {
        $str = '';
        $max = mb_strlen(PasswordGenerator::KEYSPACE, '8bit') - 1;
        for ($i = 0; $i < $length; ++$i) {
            try {
                $str .= PasswordGenerator::KEYSPACE[random_int(0, $max)];
            } catch (\Exception $e) {
                # TODO: handle?
            }
        }
        return $str;
    }
}