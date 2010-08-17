<?
header("Content-Type:text/xml");

$data = 'http://maps.google.com/maps/api/geocode/json?'.getenv('QUERY_STRING');

// Create a curl handle to a non-existing location
	$ch = curl_init($data);
	
	// Execute
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	echo '(function() {window.geo_data=';
	echo curl_exec($ch);
	echo ';})();';
	curl_close($ch);
?>