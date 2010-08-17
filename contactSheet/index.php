<?
require_once 'Flickr/API.php';
require_once 'XML/Tree/Node.php';
require_once '../../config/config.inc';
#
# build the API URL to call
#
$SP = array();

$SP['flickr'] = array(
	'api_key'        => $config['contactSheet']['api_key'],
	'request_format' => 'php_serial',
);

function callFlickrMethod($method,$params = array()) {
	global $SP;

	# create a new api object
	$api =& new Flickr_API(array(
		'api_key'  => $SP['flickr']['api_key'],
	));

	# call a method
	$response = $api->callMethod($method, $params);


	# check the response
	if ($response){
		return $response;
	}else{
		# fetch the error
		return array(
			'code'    => $api->getErrorCode(),
			'message' => $api->getErrorMessage()
		);
	}
}

#
# Get Sets
#
$sets = callFlickrMethod('flickr.photosets.getList',array(
	'user_id' => $config['contactSheet']['user_id']
));
$SP['contactSheet']['photosets'] = array();
foreach($sets->children[1]->children as $child_1) {
	foreach($child_1->children as $child_2) {
		if($child_2->name === 'title') {
			$SP['contactSheet']['photosets']['set_'.$child_1->attributes['id']]['id'] = $child_1->attributes['id'];
			$SP['contactSheet']['photosets']['set_'.$child_1->attributes['id']]['title'] = $child_2->content;
		} else if($child_2->name === 'description') {
			$SP['contactSheet']['photosets']['set_'.$child_1->attributes['id']]['description'] = $child_2->content;
		}
	}
}

#
# Get Sets
#

?>
flickr.photosets.getPhotos
<!DOCTYPE HTML>

<html lang="en">
<head>
	<meta charset="utf-8" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name = "viewport" content = "width = device-width, initial-scale = .4, user-scalable = no">
	<title>iPad/iPhone Contact Sheet View</title>
	<style>
	body {font-family: helvetica, arial sans-serif}
	section#sheet{
		display:block;
	}
	#sheet {
		position:relative;
		background-color:#000;
		padding:4px 0 4px 4px;
		-webkit-touch-callout:none;
	}
	#sheet.editing {
		background-color:#fff;
		border:dotted #3px grey;
	}
	#sheet .actions {
		position:absolute;
		top:0; right:0;
	}
	#sheet .actions .canceledit {
		display:none;
		font-size:300%;
	}
	#sheet.editing .actions .canceledit {
		display:block;
	}
	#sheet img {
		margin:13px 3px 3px 13px;
	}
	#sheet img.selected {
		border:solid 3px #f00;
		margin:10px 0 0 10px;
	}
	</style>
</head>
<body>
<header>
<h1>iPad/iPhone Contact Sheet View</h1>
<p class="prototypecaption">A tool to let photographers choose photos from a shoot</p>
</header>
<section id="sheet">
	<select>
		<?foreach($SP['contactSheet']['photosets'] as $photoset) {?>
			<option value="<?=$photoset['id']?>"><?=$photoset['title']?></option>
		<?}?>
	</select>
</section>
<section id="sheet">
<div class='actions'>
	<button class="canceledit">Cancel</button>
</div>
<img src="http://farm4.static.flickr.com/3486/4556346249_d70a9c1c17_m.jpg">
<img src="http://farm4.static.flickr.com/3486/4556346249_d70a9c1c17_m.jpg">
<img src="http://farm4.static.flickr.com/3486/4556346249_d70a9c1c17_m.jpg">
<img src="http://farm4.static.flickr.com/3486/4556346249_d70a9c1c17_m.jpg">
<img src="http://farm4.static.flickr.com/3486/4556346249_d70a9c1c17_m.jpg">
<img src="http://farm4.static.flickr.com/3486/4556346249_d70a9c1c17_m.jpg">
</section>
<footer>
<a rel="license" href="http://creativecommons.org/licenses/by/3.0/us/"><img alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by/3.0/us/88x31.png" /></a><br /><span xmlns:dc="http://purl.org/dc/elements/1.1/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dc:title" rel="dc:type">Each StandardPixel Prototype</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="http://standardpixel.com" property="cc:attributionName" rel="cc:attributionURL">Eric Gelinas</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by/3.0/us/">Creative Commons Attribution 3.0 United States License</a>.<br />Based on a work at <a xmlns:dc="http://purl.org/dc/elements/1.1/" href="http://s.standardpixel.com/proto" rel="dc:source">standardpixel.com</a>.
</footer>

<script>
(function(){
	/*
	* Lets just call these few helpers StandardPixel "Framework"
	*/
	var S = {};
	
	//Shortcuts to selector methods
	S.one = function(selector) { return document.querySelector(selector);};
	S.all = function(selector) { return document.querySelectorAll(selector);};
	
	//Dev shit
	S.console = function() {
		var i = 0;
		while(i < arguments.length) {
			var argument = arguments[i];
			
			if(argument instanceof Object){
				for(var ii in argument) {
					console.log(ii + '(' + argument[ii] + ')');
				}
			} else {
				console.log(argument);
			}
			
			i++
		}
	}
	
	//Removes style class out of the stupid DOM space separated list
	S.removeStyleClass = function(element,className) {
		if(element){
			var el_cn = element.className;

			if(el_cn.length && el_cn.indexOf(className) > -1) {
				var classNames = el_cn.split(' ');
				for(var i=0,l=classNames.length;l>i;i++) {
					var cn = classNames[i];
					if(cn === className){
						classNames[i] = null;
					}
				}
				element.className = classNames.join(' ');

				return true;
			}
		}
		return false;
	}

	/*
	* A little configuration
	*/
	var selected_image_class = 'selected',
	    editing_class        = 'editing',
	    touchandhold_time    = 1000; //ms
	

	/*
	* This is the contact sheet behavior object
	*/
	S.contactSheet = {
		//Object which holds the state information for this componant
		state : {
			activeTouch:null
		},
		//Event listener for the touch event
		onTouchSheet : function(event) {
			
			var activeTouch = setTimeout(function(){S.contactSheet.onTouchAndHoldPhoto(event)},touchandhold_time);
			S.contactSheet.state.activeTouch = activeTouch;
			
			/*
			if(event.target.tagName === 'IMG') {
				S.contactSheet.onClickImage(event);
			}
			*/
			if(event.target && event.target.parentNode) {
				if(event.target.parentNode.className.indexOf('canceledit') > -1) {
					S.contactSheet.onClickCancelEditingAction(event);
				}
			}
			
		},
		onTouchSheetEnd : function(event) {
			if(S.contactSheet.state.activeTouch) {
				clearTimeout(S.contactSheet.state.activeTouch);
				S.contactSheet.state.activeTouch = null;
			}
		},
		//This will be called if the user touches a photo for more than the configured time
		onTouchAndHoldPhoto : function() {
			var sheet =  S.one('#sheet');
			if(S.contactSheet.state.activeTouch){
				sheet.className = editing_class;
				S.contactSheet.state.activeTouch = null;
			}
		},
		//Event listener for clicks on the sheet element
		onClickSheet : function(event) {
			if(event.target.tagName === 'IMG') {
				S.contactSheet.onClickImage(event);
			}
			if(event.target && event.target.parentNode) {
				if(event.target.parentNode.className.indexOf('canceledit') > -1) {
					S.contactSheet.onClickCancelEditingAction(event);
				}
			}
		},
		//Event listener which will be called by onClickSheet if an image is the source of the event
		onClickImage : function(event) {
			S.contactSheet.toggleSelectedState(event.target);
		},
		onClickCancelEditingAction : function(event) {
			S.removeStyleClass(S.one('#sheet'),editing_class);
		},
		toggleSelectedState : function(image) {
			if(image.className.indexOf(selected_image_class) > -1) { //It does
				S.removeStyleClass(image,selected_image_class);
			} else { //It dont
				image.className = selected_image_class;
			}
		},
		init : function() {
			var scope         = S.contactSheet,
			    sheet_element = S.one('#sheet');
			
			/*
			*  Set up event listeners
			*/
			sheet_element.addEventListener('click',scope.onClickSheet);
			sheet_element.addEventListener('touchstart',scope.onTouchSheet);
			sheet_element.addEventListener('touchend',scope.onTouchSheetEnd);
		}
	}
	
	/*
	* Init Page
	*/
	S.contactSheet.init();
})();
</script>
</body>
</html>
