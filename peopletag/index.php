<?
include 'lib_auth.php';



$app = array(
	user => array(
		nsid     => (string) $auth_response->children[1]->children[5]->attributes['nsid'],
		username => (string) $auth_response->children[1]->children[5]->attributes['username'],
		fullname => (string) $auth_response->children[1]->children[5]->attributes['fullname']
	),
	photos => array()
);

#
# Get photos from Flickr
#
$user_photos = $api->callMethod('flickr.people.getPhotos', array(
	'auth_token' => $_COOKIE['auth_token'],
	'user_id'        =>"me",
	'per_page'       =>10,
	'page'           =>1
));

#
# Clean up photos array
#

for($i=0;count($user_photos->children[1]->children) > $i;$i++) {
	$att = $user_photos->children[1]->children[$i]->attributes;
	
	if($att['id']) {
		array_push($app['photos'],$att);
	}
}

function displayPhoto($photo_row) {
	echo '<a href="http://www.flickr.com/photos/standardpixel/'.$photo_row["id"].'/" title="'.$photo_row["title"].' on Flickr"><img src="http://farm'.$photo_row["farm"].'.static.flickr.com/'.$photo_row["server"].'/'.$photo_row["id"].'_'.$photo_row["secret"].'_z.jpg" alt="'.$photo_row["title"].'" border="0" /></a>';
}

function displayTitle($photo_row) {
	echo '<h2>'.$photo_row["title"].'</h2>';
}
?>

<? include('header.inc'); ?>
	
	<?if($auth_response) {?>
		<div id="content">
		
			<? for($i=0;count($app['photos']) > $i; $i++) { ?>
			
				<div class="photo_container">
					<div class="photo_inner_container" id="photo-<?= $app['photos'][$i]['id']; ?>">
						<?= displayPhoto($app['photos'][$i]); ?>
						<?= displayTitle($app['photos'][$i]); ?>
					</div>

					<div class="sidebar">
						<button class="tag-somebody">Tag Somebody</button>
					</div>
				</div>
			
			<? } ?>

			<p><fb:login-button autologoutlink="true"></fb:login-button></p>

			<div id="fb-root"></div>
		</div>
		<script src="http://yui.yahooapis.com/3.3.0/build/yui/yui-min.js"></script>
		<script>
		
			/*
			*  My Shit
			*/
			YUI().use('node','io',function(Y){
				var tag_somebody_button_node = Y.one('button.tag-somebody'),
				    content_node             = Y.one('#content'),
				    picker                   = null;
			
				tag_somebody_button_node.on('click',function() {
					content_node.append('<div class="picker"></div>');
					picker = content_node.one('.picker');
				
					Y.io('./picker.php', {on:{complete:function(a) {
						picker.append(arguments[1].responseText);
					
						picker.all('ul li').on('click',function(e) {
							console.log(e.currentTarget.getAttribute('data-id'));
							var dom_node = Y.Node.getDOMNode(picker);
							dom_node.parentNode.removeChild(dom_node);
						});
					
						picker.one('button.close-action').on('click',function() {
							var dom_node = Y.Node.getDOMNode(picker);
							dom_node.parentNode.removeChild(dom_node);
						});
					}}});
				});
			});
		
			/*
			*  Facebook Shit
			*/
			window.fbAsyncInit = function() {
			FB.init({appId: '120831071331992', status: true, cookie: true,
			xfbml: true});
			};
			(function() {
			var e = document.createElement('script');
			e.type = 'text/javascript';
			e.src = document.location.protocol +
			'//connect.facebook.net/en_US/all.js';
			e.async = true;
			document.getElementById('fb-root').appendChild(e);
			}());
		</script>
	
	<? } else { ?>
		<?= $auth_write ?>
	<? } ?>
<? include('footer.inc'); ?>