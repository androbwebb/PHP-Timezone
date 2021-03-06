PHP-Timezone
============

A small php script that can convert a lat/long coordinate pair into the time at that location.

Uses http://www.earthtools.org 's timezone tool. My project is basically a wrapper for that.


## Requirements
(PHP 5 >= 5.3.0)


## Useage

##### Class PHPTimezone_Location
Represents a location - Given a latitude and longitude, this object holds data about the time at that location.

Retrievable information:
+  Local Time
+  Iso Time
+  Timezone Offset
+  Nautical Suffix
+  Timezone Name
  
#### API

```php
require_once 'PHPTimezone.class.php' ;

$z = new PHPTimezone_Location($latitude,$longitude) ;
print_r($z->get_data()) ;
```
Results:
```
Array
(
    [location] => Array
        (
            [latitude] => 40.67
            [longitude] => -73.94
        )

    [iso_time] => 2013-12-28 04:47:52 -0500
    [local_time] => 28 Dec 2013 04:47:52
    [timezone_offset] => -5
    [nautical_suffix] => R
    [timezone_name] => America/New_York
)
```

