<?php
exit;
        // create curl resource
        $ch = curl_init();

        //$url = url('/')."/leaves/creditLeavesCron";
        // set url hil-leave.com/
        curl_setopt($ch, CURLOPT_URL,"http://hil-leave.com/leaves/creditLeavesCron");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);


        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $info = curl_getinfo($ch);



        $output = curl_exec($ch);


        curl_close($ch);

?>
