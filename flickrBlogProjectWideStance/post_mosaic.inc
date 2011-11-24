<?
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