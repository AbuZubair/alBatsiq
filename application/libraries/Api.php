<?php
/*
 * To change this template, choose Tools | templates
 * and open the template in the editor.
 */

final Class Api {

    // ================================= DASHBOARD =================================== //
     public function getApiData($link) {

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $link);

         // Execute post
        $result = curl_exec($ch);
        //print_r($result);die;
        if ($result === FALSE) {
          die('Curl failed: ' . curl_error($ch));
        }

        // Close connection
        curl_close($ch);

        // do anything you want with your response
        return json_decode($result);
    }

    

}

?> 