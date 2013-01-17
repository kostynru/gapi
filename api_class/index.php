<?php
include 'getting_api_class.php';
$gapi = new GAPI('localhost', 17, '33803832e8265e5d3f0885efc5301875', 'xml');
$result = $gapi->getToken();
echo $result;
