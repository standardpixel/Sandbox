<?
require_once('../../config/config.inc');
require_once('simplepie.inc');

#
# Read file
#
$data = file($config['shitourdunstanfavs']['data_file']);

#
# Read RSS Feed
#
$feed = new SimplePie();
$feed->set_feed_url('http://api.flickr.com/services/feeds/photos_faves.gne?nsid=68756453@N00&lang=en-us&format=rss_200');
$feed->init();
$feed->handle_content_type();
$feed->enable_order_by_date(false);
$item = $feed->get_item(0);
$count = $feed->get_item_quantity();

#
# Build Tweet
#
$tweet = $item->get_title() . ' - ' . $item->get_link();

if($count > 0) {
	if($data[0] !== $tweet) {
		#
		# Write this status to file
		#
		$fp = fopen('data.txt', 'w');
		fwrite($fp, $tweet);
		fclose($fp);
		
		#
		# Tell client whats up
		#
		echo 'about to tweet: ' . $tweet . ' <br>';
		
		#
		# Send the tweet
		#
		$status = mail(
			$config['shitourdunstanfavs']['twitter_email'],
			$tweet,
			''
		);

		#
		# Echo output of mail command
		#
		echo $status;
	} else {
		
		#
		# Let the client know there are no new tweets
		#
		echo 'nothing to tweet';
	}
}
?>