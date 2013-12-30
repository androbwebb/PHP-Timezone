<?php

class PHPTimezone_Location {

    // Some variables

    // These are initiated in the constructor
    private $latitude           ;
    private $longitude          ;

    // These are updated with the update() method
    private $local_time         ;
    private $iso_time           ;
    private $timezone_offset   ;
    private $nautical_suffix    ;
    private $timezone_name      ;


    // These are some constants
    private $URL    = "http://www.earthtools.org/timezone-1.1"  ;
    private $FORMAT = "/{{LAT}}/{{LONG}}"                       ;


    /**
     * @param $latitude
     * @param $longitude
     * @return PHPTimezone_Location object
     */
    public function __construct($latitude, $longitude){
        $this->latitude = floatval($latitude) ;
        $this->longitude = floatval($longitude) ;

        $this->update();

        return $this ;
    }


    public function update(){
        // Generate the correct URL string to call
        $endpoint = $this->URL . preg_replace("/{{LAT}}/i", $this->latitude,preg_replace("/{{LONG}}/i", $this->longitude,$this->FORMAT)) ;

        // Get XML contents from URL
        $contents = file_get_contents($endpoint) ;

        $parser = xml_parser_create('');

        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);

        // Initiate data array
        $response = array() ;

        // Get all attributes from xml_values into nice array
        foreach($xml_values as $index => $data){
            if($data[tag] && $data[value])
                $response[$data[tag]] = $data[value];
        }

        // Set object variables
        $this->local_time       = $response[localtime]  ;
        $this->iso_time         = $response[isotime]    ;
        $this->timezone_offset = $response[offset]     ;
        $this->nautical_suffix  = $response[suffix]     ;

        // Add some for quick access
        $this->timezone_name    = $this->get_closest_timezone_name_from_location();

        return $response ;
    }

    public function get_closest_timezone_name_from_location(){
        $diffs = array();

        // Get all timezones
        $alltimezones = DateTimeZone::listIdentifiers() ;

        foreach($alltimezones as $tz_name) {
            $tz = new DateTimeZone($tz_name);
            $location = $tz->getLocation();

            // Get sum of differences in latitude & longitude
            $diff = abs($this->latitude - $location['latitude']) + abs($this->longitude - $location['longitude']);

            // Save distance
            $diffs[$tz_name] = $diff;
        }


        // Get key of minimum distance.
        asort($diffs, SORT_DESC);

        // Reset array cursor & get first key.
        reset($diffs);
        $answer = key($diffs); // Gets first key

        // Return it
        return $answer;
    }

    public function get_data(){
        return array(
            "location"          =>array(
                "latitude"      => $this->latitude,
                "longitude"     => $this->longitude,
            ),
            "iso_time"          => $this->iso_time,
            "local_time"        => $this->local_time,
            "timezone_offset"  => $this->timezone_offset,
            "nautical_suffix"   => $this->nautical_suffix,
            "timezone_name"    => $this->timezone_name,
        );
    }

}

// Testing w/ NYC lat/long
//$lat = 40.6700 ;
//$lng = -73.9400;
//$z = new PHPTimezone_Location($lat,$lng) ;
//print_r($z->get_data()) ;
