<?php

require './facebook.php';

$facebook = new Facebook(array(
  'appId'  => '120831071331992',
  'secret' => '3d9936963a7a2c2357c6b07394e0f9ab',
  'cookie' => true, // enable optional cookie support
));

$session = $facebook->getSession();

$me = null;
// Session based API call.
if ($session) {
  try {
    $uid = $facebook->getUser();
    $friends = $facebook->api('/me/friends');
  } catch (FacebookApiException $e) {
    error_log($e);
  }
}
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>People Picker</title>
</head>
<body>
	<h1>People Picker</h1>
	<ul>
		<? foreach($friends['data'] as $friend){ ?>
			<li data-id="<?=$friend['id']?>"><?=$friend['name']?></li>
		<? }; ?>
	</ul>
	
	<button class='close-action'>Cancel</button>
</body>
</html>