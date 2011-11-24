<?
	require_once 'simplepie.inc';
	require_once 'post_mosaic.inc';
?>

<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">

	<title>Photo Blog</title>

	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.2r1/build/reset/reset-min.css">
	<link rel="stylesheet" type="text/css" href="style.css">
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