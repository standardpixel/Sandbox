<?php
include_once('../../config/facebookPhotoPosting.inc');


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
}

?>
<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=1036">
	<title>Facebook Photo Post options</title>
	
	<style>
		
		body {
			font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
			font-weight: 300;
			
		}
		
		h2 {font-weight:500;}
		
		h3 {padding:0;margin:0;font-weight:500;}
		
		ul {display:block;margin:0;padding:0;}
		
		li {list-style-type:none;display:inline-block;border:solid 1px #e7e7e7;height:290px;vertical-align:top;padding:5px;}
		
		li.selected {background-color:#DCE8F6;}
		
		header, footer {border-bottom:dotted 1px #ccc;}
		
		footer {padding-bottom:20px;}
		
		section.loading img {width:5%; height:5%;visibility:hidden;}
		
		section.loading h2 {color:#FF0084;}
		
		input {visibility:hidden;}
		
	</style>
</head>
<body>
	<header>
		<h1>Test postr</h1>
		<div id="fb-root"></div>
		      <script src="http://connect.facebook.net/en_US/all.js"></script>
		      <script>
			FB.init({ 
				appId:'120831071331992', cookie:true, 
				status:true, xfbml:true 
			});
			
			FB.Event.subscribe('auth.login', function(response) {
				window.location.reload();
			});
		      </script>
		
		<?if ($cookie) { ?>
		      <h2>Welcome <?= $user->name ?></h2>
		<?} else { ?>
		      <h2><fb:login-button perms="publish_stream"></fb:login-button></h2>
		<?}?>
	</header>
	
	<?if ($cookie) { ?>
	<div id="content">
		<section>
			<h2>Share</h2>
			<ul>
				<li class="selected">
					<img src="http://farm6.static.flickr.com/5215/5409329204_02821fc0a9_m.jpg" align="top">
					<h3>"Orangy Burbon" photo</h3>
					<input type="radio" name="item" value="photo1" checked>
				</li>
				<li>
					<img src="http://farm6.static.flickr.com/5213/5409336876_45e74d723c_m.jpg" align="top">
					<h3>"Coffee" photo</h3>
					<input type="radio" name="item" value="photo2" checked>
				</li>
				<li>
					<img src="http://farm5.static.flickr.com/4001/4359342967_f1623deebe_m.jpg" align="top">
					<input type="radio" name="item" value="set1">
					<h3>"FlickrHQ" Set</h3>
				</li>
			</ul>
		</section>
		<section class="loading">
			<h2></h2>
			<img src="http://l.yimg.com/g/images/snapping-maggie.gif">
		</section>
		<section>
			<h2>As a</h2>
			<button value="feed">Feed</button>
			<button value="note">Note</button>
			
		</section>
	</div>
	<?} else {?>
		<h2>This demo requires Facebook authorization.</h2>
	<?}?>
	
	<footer>
	</footer>
	
	<?if ($cookie) { ?>
	<script src="http://yui.yahooapis.com/3.4.1/build/yui/yui.js"></script>
	<script>
		YUI().use('node', 'event', 'selector-css3', function (Y) {
			
			var icon         = 'http://farm3.static.flickr.com/2690/buddyicons/24111544@N00.jpg',
			    post_types   = {},
			    radio_nodes  = Y.all('section input[name=item]');
			    button_nodes = Y.all('section button'),
			    list_nodes   = Y.all('ul li');
			
			/*
			*  Define items to share
			*/
			post_types.photo1 = {
				message     : 'Orangy Burbon',
				picture     : 'http://farm6.static.flickr.com/5215/5409329204_02821fc0a9_m.jpg',
				description : 'I enjoyed it',
				caption     : 'This was the first ever cocktail created in the flickr saloon. It contains simple syrup, bourbon, bitters, and ice. It is topped with a slice of orange. This was created by the baby stealing jerk named Jude. This was back when we were on the 9th floor using a bar which was made out of cardboard. It was constructed out of an old bike shipping box which we had in the office.',
				link        : 'http://www.flickr.com/photos/standardpistol/5409329204/',
				actions     : {'name':'StandardPistol\'s photostream','link':'http://http://www.flickr.com/photos/standardpistol/'}
			};
			
			post_types.photo2 = {
				message     : 'Coffee',
				picture     : 'http://farm6.static.flickr.com/5213/5409336876_45e74d723c_m.jpg',
				description : 'I am sharing this for some reason',
				caption     : 'Sitting in a cafe and drinking coffee',
				link        : 'http://www.flickr.com/photos/standardpistol/5409336876/',
				actions     : {'name':'StandardPistol\'s photostream','link':'http://http://www.flickr.com/photos/standardpistol/'}
			};
			
			post_types.set1 = {
				message     : 'FlickrHQ flickr set',
				picture     : 'http://farm5.static.flickr.com/4001/4359342967_f1623deebe_s.jpg',
				description : 'Some pretty goofy pictures',
				caption     : 'Pictures takes around FlickrHQ',
				link        : 'http://www.flickr.com/photos/standardpistol/sets/72157623440865020/',
				actions     : {'name':'View in lightbox','link':'http://www.flickr.com/photos/standardpistol/5409336392/in/set-72157623440865020/lightbox/'}
			};
			
			/*
			*  Helper functions
			*/
			function handleResponse(r) {
				toggleLoading();
				
				if (!r || r.error) {
					setStatusMessage('There was an error. See browser console for more info.');
					console.error(r.error);
				} else {
					setStatusMessage('Post successful (id: '+r.id+')');
				}
			}
			
			function setStatusMessage(msg) {
				Y.one('section.loading h2').set('innerHTML',msg);
			}
			
			function toggleLoading() {
				var loading_node = Y.one('section.loading img');
				
				if(loading_node.getStyle('visibility')=='hidden') {
					setStatusMessage('loading...');
					loading_node.setStyle('visibility','visible');
				} else {
					setStatusMessage('');
					loading_node.setStyle('visibility','hidden');
				}
			}
			
			/*
			*  Sharing types
			*/
			function shareFeed(item) {
				FB.api('/me/feed', 'post', post_types[item], handleResponse);
			}
			
			function shareNote(item) {
				var item_object = Y.clone(post_types[item]);
				
				item_object.icon    = icon;
				item_object.subject = item_object.message;
				item_object.message = '<img src="' + item_object.picture + '"><div>' + item_object.caption + '</div>';

				FB.api('/me/notes', 'post', item_object, handleResponse);
			}
			
			/*
			*  Setup events
			*/
			button_nodes.on('click',function(e) {
				
				var item = null;
				
				radio_nodes.each(function(node) {
					if(node.get('checked')) {
						item = node.get('value');
					}
					
					if(!item) {
						item = radio_nodes.item(0).get('value');
					}
				});
				
				switch(e.target.get('value')) {
					case 'feed':
						toggleLoading()
						shareFeed(item);
					break;
					
					case 'note':
						toggleLoading()
						shareNote(item);
					break;
				}
			});
			
			list_nodes.on('click',function(e) {
				var list_node = e.target.ancestor('li',true);
				
				list_nodes.removeClass('selected');
				list_node.addClass('selected');
				list_node.one('input').set('checked','checked');
			});

		});
	</script>
	<?}?>
</body>
</html>