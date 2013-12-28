<?php
require_once __DIR__ . '/PHPTimezone.class.php' ;


/**
 * @file This file serves as an API endpoint. The point is really just to save you time, not having to write your own function
 *
 * The following parameters are expected in the request body
 * @param latitude
 * @param longitude
 */


// Hope you guys didnt want XML :)
echo json_encode(get_timezone_with_latitude_longitude($_REQUEST['latitude'],$_REQUEST['longitude']));





/**
 * This handles everything, giving you an easy function to call. It avoids you dealing with the object yourself.
 * @param $latitude
 * @param $longitude
 * @return array
 */
function get_timezone_with_latitude_longitude($latitude, $longitude){
    $z = new PHPTimezone_Location($latitude,$longitude) ;
    return $z->get_data() ;
}
