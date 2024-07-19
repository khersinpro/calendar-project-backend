<?php

namespace App\Service;

class EmailNormalizerService
{
    /**
     * Normalize email address
     * Example for gmail: user.name@gmail.com -> username@gmail.com
     */
    static function normalizeEmail(string $email): string
    {
        $email = strtolower($email);
        list($firstPart, $secondPart) = explode('@', $email);

        if ($secondPart === 'gmail.com') {
            $firstPart = str_replace('.', '', $firstPart);
        }

        return $firstPart . '@' . $secondPart;
    }
}