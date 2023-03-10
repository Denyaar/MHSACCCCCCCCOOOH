<?php

use App\Models\User;

/**
 * Created by PhpStorm for mycouncillor
 * User: VinceGee
 * Date: 7/18/2022
 * Time: 2:45 AM
 */

function generateUsername($firstName, $lastName): string
{
    $parts = explode(' ', $firstName.' '.$lastName);
    $name_first = array_shift($parts);
    $name_last = array_pop($parts);
    $name_middle = trim(implode(' ', $parts));

    $maiden = substr($name_first, 0, 1);
    $middle = substr($name_middle, 0, 1);

    $username = strtolower($maiden . $middle . $name_last);

    $name = $username;
    $i = 0;
    do {
        //Check in the database here
        $exists = User::where('name', '=', $name)->exists();
        if($exists) {
            $i++;
            $name = $username . $i;
        }
    }while($exists);

    return $name;
}

function convertToSlug($word){
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '_', $word)) );
    return $slug;
}

function boolToString($value){
    if ($value == true){
        return 'Yes';
    } else {
        return 'No';
    }
}


function send_notification_FCM($notification_id, $title, $message, $id, $type)
{

    $accesstoken = env('FCM_KEY');

    $URL = 'https://fcm.googleapis.com/fcm/send';


    $post_data = '{
            "to" : "' . $notification_id . '",
            "data" : {
              "body" : "",
              "title" : "' . $title . '",
              "type" : "' . $type . '",
              "id" : "' . $id . '",
              "message" : "' . $message . '",
            },
            "notification" : {
                 "body" : "' . $message . '",
                 "title" : "' . $title . '",
                  "type" : "' . $type . '",
                 "id" : "' . $id . '",
                 "message" : "' . $message . '",
                "icon" : "new",
                "sound" : "default"
                },

          }';
    // print_r($post_data);die;

    $crl = curl_init();

    $headr = array();
    $headr[] = 'Content-type: application/json';
    $headr[] = 'Authorization: ' . $accesstoken;
    curl_setopt($crl, CURLOPT_SSL_VERIFYPEER, false);

    curl_setopt($crl, CURLOPT_URL, $URL);
    curl_setopt($crl, CURLOPT_HTTPHEADER, $headr);

    curl_setopt($crl, CURLOPT_POST, true);
    curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);

    $rest = curl_exec($crl);

    if ($rest === false) {
        // throw new Exception('Curl error: ' . curl_error($crl));
        //print_r('Curl error: ' . curl_error($crl));
        $result_noti = 0;
    } else {

        $result_noti = 1;
    }

    //curl_close($crl);
    //print_r($result_noti);die;
    return $result_noti;
}
