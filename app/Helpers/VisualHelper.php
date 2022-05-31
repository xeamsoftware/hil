<?php

namespace App\Helpers;

use PhpOffice\PhpSpreadsheet\Shared\Date;

class VisualHelper
{
    static function repairDate($dateForRepair){

        if (strstr($dateForRepair, '-') !== false) {
            $date = explode('-', $dateForRepair);
        }

        if (strstr($dateForRepair, '/') !== false) {
            $date = explode('/', $dateForRepair);
        }

        if (strstr($dateForRepair, '.') !== false) {
            $date = explode('.', $dateForRepair);
        }

        $chars = array("\r\n", '\\n', '\\r', "\n", "\r", "\t", "\0", "\x0B");

        $str = str_replace($chars, ' ', $date[0]);

        $date[0] = preg_replace("/[^a-zA-Z0-9 .\-_;!:?äÄöÖüÜß<>='\"]/", '', $str);


        $str = str_replace($chars, ' ', $date[1]);

        $date[1] = preg_replace("/[^a-zA-Z0-9 .\-_;!:?äÄöÖüÜß<>='\"]/", '', $str);

        $str = str_replace($chars, ' ', $date[2]);

        $date[2] = preg_replace("/[^a-zA-Z0-9 .\-_;!:?äÄöÖüÜß<>='\"]/", '', $str);

        if($date[0] > 31 || strlen($date[0]) > 2 || strlen($date[1]) > 2 || $date[1] > 12 || strlen($date[2]) != 4){
            return ['error' => 'Kindly Add Date In DD-MM-YYYY'];
        }
        $date = (int)$date[0] . '-' . (int)$date[1] . '-' . (int)$date[2];
        $date = date('Y-m-d', strtotime($date));
        return ['date' => $date];

    }
}
