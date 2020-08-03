<?php
namespace App\Traits;

trait RandomStringGenerator
{
    /**
     * Generate a random activation code
     *
     * @return void
     */
    private function randomCode($length = 6)
    {
        $chars = '123456789ABCDEFGHJKLMNPQRSTUVWXYZ';

        while (true) {
            // Generate a random string
            for ($i = 0, $randstring = ''; $i < $length; $i++) {
                $randstring .= $chars[rand(0, (strlen($chars) - 1))];
            }

            return $randstring;
        }
    }
}
