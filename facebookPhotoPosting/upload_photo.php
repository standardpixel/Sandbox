<?
include_once('../../config/facebookPhotoPosting.inc');
include('lib_facebook.php');

function get_facebook_cookie($app_id, $app_secret) {
	$args = array();
	parse_str(trim($_COOKIE['fbs_' . $app_id], '\\"'), $args);
	ksort($args);
	$payload = '';
  
	foreach ($args as $key => $value) {
		if ($key != 'sig') {
			$payload .= $key . '=' . $value;
		}
	}

	if (md5($payload . $app_secret) != $args['sig']) {
		return null;
	}
  
	return $args;
}

$cookie = get_facebook_cookie(YOUR_APP_ID, YOUR_APP_SECRET);

if($cookie) {
	$user = json_decode(file_get_contents(
	    'https://graph.facebook.com/me?access_token=' .
	    $cookie['access_token']));
	
	$facebook = new Facebook(array('appId' => YOUR_APP_ID, 'secret' => YOUR_APP_SECRET, 'fileUpload' => true));
	
	// Upload a photo to a user’s profile
	// Your app needs photo_upload permission for this to work
	$facebook->setFileUploadSupport(true);
	
	/*
	$photo = $facebook->api(
		'/me/photos', 
		'POST',
	        array( 
			'source'  => 'http://farm5.static.flickr.com/4146/5409336392_60921dc0e0_z.jpg',
	                'message' => 'Photo uploaded via the PHP SDK!',
	                'access_token' => $cookie['access_token']
	 	)
	);
	
	print_r($photo);
	*/
	
	$arr_attachment = array('access_token' => $session['access_token'],
		'source' => '@images/' . $_GET['id'] . '.jpg',
		'message' => $_GET['message'],
		'access_token' => $cookie['access_token']
	);
	
	$curl = curl_init();
	curl_setopt($curl, CURLOPT_URL, "https://graph.facebook.com/me/photos?access_token=" . $cookie['access_token']);
	curl_setopt($curl, CURLOPT_HEADER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_POST, true);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $arr_attachment);

	$_photo = curl_exec($curl);
	
	print_r($_photo);
}
?>

<?if($cookie) {?>
<?} else {?>
Bye	
<?}?>