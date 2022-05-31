<?php 
        // create curl resource 
        $ch = curl_init(); 

        //$url = url('/')."/leaves/expireCompensatoryLeavesCron";
        // set url 
        curl_setopt($ch, CURLOPT_URL, "http://hil-leave.com/leaves/expireCompensatoryLeavesCron"); 

        //return the transfer as a string 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 

        // $output contains the output string 
        $output = curl_exec($ch); 

        // close curl resource to free up system resources 
        curl_close($ch);      
?>