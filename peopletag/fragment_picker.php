<?php

require './lib_auth.php';
require './lib_facebook.php';

require_once '../../config/generic_photo.inc';

#
# Setup app array
#

$app = array(
	user => array(
		nsid     => (string) $auth_response->children[1]->children[5]->attributes['nsid'],
		username => (string) $auth_response->children[1]->children[5]->attributes['username'],
		fullname => (string) $auth_response->children[1]->children[5]->attributes['fullname']
	),
	facebook_user => null,
	facebook_api => new Facebook($config['facebook_api']),
	facebook_contacts => null,
	flickr_contacts => array(),
	error_msg => null
);

#
# Get facebook session
# 

$app['facebook_session'] = $app['facebook_api']->getSession();

#
# Get facebook users
#

if ($app['facebook_session']) {
  try {
    $uid = $app['facebook_api']->getUser();
    $app['facebook_contacts'] = $app['facebook_api']->api('/me/friends');
  } catch (FacebookApiException $e) {
    $app['error_msg'] = $e;
  }
}

#
# Get flickr users
#

$api_response_get_nsid = $api->callMethod('flickr.contacts.getList', array(
	'auth_token' => $_COOKIE['auth_token']
));

if($api_response_get_nsid) {
	$contacts_array = $api_response_get_nsid->children[1]->children;

	for($i=0;count($contacts_array) > $i; $i++) {
		array_push($app['flickr_contacts'],(array) $contacts_array[$i]->attributes);
	}
} else {
	$app['error_msg'] = 'I could not find your Flickr users. Sorry???';
}
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>People Picker</title>
</head>
<body>
	<? if(!$app['error_msg']) { ?>
		<h1>People Picker</h1>
		<h2>Flickr Contacts</h2>
		<ul>
			<? foreach($app['flickr_contacts'] as $friend){ ?>
				<? if($friend['nsid']) { ?>
					<li data-id="<?=$friend['nsid']?>"><?=$friend['username']?></li>
				<? } ?>
			<? }; ?>
		</ul>
		<h2>Facebook Contacts</h2>
		<ul>
			<? foreach($app['facebook_contacts']['data'] as $friend){ ?>
				<li data-id="<?=$friend['id']?>"><?=$friend['name']?></li>
			<? }; ?>
		</ul>

		<button class='close-action'>Cancel</button>
	<? } else { ?>
		<?= $app['error_msg']; ?>
	<? } ?> 
</body>
</html>