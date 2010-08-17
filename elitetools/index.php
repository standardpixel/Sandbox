<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>Yelp Elite Tools</title>
	<meta name="author" content="Eric Gelinas">
	<!-- Date: 2009-12-22 -->
</head>
<body>
	<h1>Yelp Elite Tools</h1>
	<p id='location_greeting'>Getting Location...</p>
	<?
	$data = "http://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20html%20where%20url%3D%22http%3A%2F%2Fwww.yelp.com%2Fuser_details_bookmarks%3Fuserid%3DmOCM896HwdM4sL961RRNHg%26sort_by%3Dname_desc%22%20and%20xpath%3D'%2F%2Fdiv%5B%40id%3D%22bookmarks_main%22%5D%2F%2Fscript'&format=xml&diagnostics=false";

	// Create a curl handle to a non-existing location
	$ch = curl_init($data);

	// Execute
   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	echo "<script>var Yelp={};Event={};Event.observe = function(){};</script><div id=\"yqlresults\" style=\"display:none;\">" . str_replace("\n", "", curl_exec($ch)) . "</div>";
	curl_close($ch);
	?>
	<script src="http://yui.yahooapis.com/3.0.0/build/yui/yui-min.js"></script>
	<script>
	
	YUI.add('gallery-simpleloc', function(Y) {

		function toRad(deg) {
			return deg * Math.PI/180;
		}

		Y.simpleLoc = {
			getDistance : function(loc1,loc2) {
				var R = 6371; // km
				var dLat = toRad((loc2.lat-loc1.lat));
				var dLon = toRad((loc2.lon-loc1.lon)); 
				var a = Math.sin(dLat/2) * Math.sin(dLat/2) + toRad(Math.cos(loc1.lat)) * toRad(Math.cos(loc2.lat)) * Math.sin(dLon/2) * Math.sin(dLon/2); 
				var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
				var d = R * c;
				return d;
			},
			kmToMiles : function(kmVal) {
				return parseFloat((kmVal / 1.609344).toPrecision(15));
			}
			
		};

	}, '0.0001' ,{requires:['node']})

	YUI().use('node','gallery-simpleloc','io',function(Y){
		var location_node = Y.one('#location_greeting');
		
		function fail(){
			return 'I don\'t know where you are but I do know that there are ' + Y.simpleLoc.kmToMiles(Y.simpleLoc.getDistance(
				{//Reseda
					lat:34.201,
					lon:-118.535
				},
				{//Sunnyvale
					lat:37.3490,
					lon:-122.0263
				}
			)) + ' miles between Reseda and Sunnyvale'
		}
		
		//get geolocation
		if(navigator.geolocation){
			navigator.geolocation.getCurrentPosition(
				function(position){ //FTW
					
					//Make sense of the lat long
					Y.on('io:complete', function(){
						console.log(arguments);
						var request = Y.io(uri);
					}, this);
					location_node.set('innerHTML','lat: ' + position.coords.latitude + ' | long: ' + position.coords.longitude);
				},
				function(){
					location_node.set('innerHTML',fail());
				}//FAIL
			);
		} else {
			location_node.set('innerHTML',fail());
		}
		
		
		console.log(Yelp);
	});

	</script>
</body>
</html>
