<?
	require_once 'simplepie.inc';
	
	$feed = new SimplePie();
	$feed->set_feed_url('http://blog.flickr.net/en/feed/atom/');
	$feed->init();
	$feed->handle_content_type();
	
	function getPhotosFromContent($content) {
		preg_match_all('/http:\/\/farm.+?\.jpg/', $content, &$result);
		
		return $result[0];
	}
	
	function squarify($photo_url,$size = 's') {
		
		$result = str_replace('_m.jpg','.jpg',$photo_url);
		$result = str_replace('_z.jpg','.jpg',$result);
		return str_replace('.jpg','_' . $size . '.jpg',$result);
		
	}
	
	function writePost($item,$options=array()) {
		
		$options   = array(
			post_class  => 'post',
			photo_class => 'photo',
			info_class  => 'info'
		);
		$content   = $item->get_content();
		$photos    = getPhotosFromContent($content);
		$count     = count($photos);
		$iteration = 0;
		
		$out = '<div class="' . $options['post_class'] . '">';
		
			if($count > 3) {
				foreach($photos as $photo) {
					if($iteration < 4) {
						$out = $out . '<div class="' . $options['photo_class'] . '" style="background:url(' . squarify($photo) . ');width:75px;height:75px;"></div>';
						$iteration = $iteration +1;
					}
				}
			} elseif($count > 0) {
				$out = $out . '<div class="' . $options['photo_class'] . '" style="background:url(' . squarify($photos[0],'m') . ') 50% 50%;width:150px;height:150px;"></div>';
			} else {
				$out = $out . '<div class="' . $options['photo_class'] . ' no-photo">' . strip_tags($content) . '</div>';
			}
			
			$out = $out . '<div class="' . $options['info_class'] . '"><h3>' . $item->get_title() . '</h3><span>' . $item->get_date('F jS') . '</span></div>';
			
		$out = $out . '</div>';
		
		return $out;
		
	}
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Photo Blog</title>

	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.2r1/build/reset/reset-min.css">
	<style>
		body {
			font-family:arial, helvetica;
		} 
		div.post{
			opacity:.7;
			-webkit-transition: opacity .5s linear;
			-mos-transition: opacity .5s linear;
			-o-transition: opacity .5s linear;
			transition: opacity .5s linear;
			width:150px;
			height:150px;
			overflow:hidden;
			border:solid 1px #ddd;
			display:inline-block;
			margin:0 10px 10px 0;
			position:relative;
			cursor: pointer;
			cursor: hand;
		} 
		div.post:hover{
			opacity:1;
		}
		div.photo{
			float:left;
		}
		div.photo.no-photo{
			padding:3px;
			font-size:11px;
			color:#333;
		}
		div.info{
			position:absolute;
			bottom:0px;left:0px;right:0px;
			background-color:#000;
			color:#fff;
			opacity:.7;
			padding:3px;
			font-size:11px;
		}
		div.info h3{
			font-weight:bolder;
			white-space:nowrap;
			overflow:hidden;
			text-overflow:ellipsis;
		}
		div.info:hover h3{
			white-space:normal;
		}
	</style>
</head>
<body>
	<div id="main_container">
		<header></header>
		<div id="content_container">
			<? foreach ($feed->get_items() as $item) { ?>

				<?= writePost($item); ?>

			<?}?>
		</div>
		<footer></footer>
	</div>

	<script type="text/javascript" charset="utf-8"
	        src="http://yui.yahooapis.com/3.4.1/build/yui/yui-min.js">
	</script>
</body>
</html>