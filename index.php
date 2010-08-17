<?php
#
# Constants
#
$root_dir = "./";
$prototypes_array = array();

#
# Extract from index file of a dir
#
function getIndexInfo($file) {
	$max_lines = 100;
	
	if(is_file($file)) {
		$file_array = file($file);
		$title      = false;
		$caption    = false;
		$line_pos   = 0;
		$output     = array();
		
		foreach($file_array as $line) {
			if(strpos($line,'<h1') > -1 || strpos($line,'prototypecaption') > -1) {
				if(strpos($line,'<h1') > -1) {
					$title = strip_tags(trim($line));
				} else if(strpos($line,'prototypecaption') > -1) {
					$caption = strip_tags(trim($line));
				}
			}
			
			if($line_pos >= $max_lines || ($title && $caption)) {
				$line_pos = 0;
				break;
			} else {
				$line_pos++;
			}
		}
		
		$output['title']   = $title;
		$output['caption'] = $caption;
		$output['lines']   = $file_array;
		return $output;
			
	} else {
		return false;
	}
	
}

#
# Get a list of directories
#
function getPrototypes() {
	global $root_dir;
	global $prototypes_array;
	
	if (is_dir($root_dir)) {
	    if ($dh = opendir($root_dir)) {
	        while (($file = readdir($dh)) !== false) {
			if(is_dir($root_dir . $file) && strrpos($file,".") < -1) {
				if(is_file($root_dir . $file . '/index.php')) {
					$index_file_name = $root_dir . $file . '/index.php';
				} else if(is_file($root_dir . $file . '/index.html')) {
					$index_file_name = $root_dir . $file . '/index.html';
				}
				
				if($index_file_name) {
					$filekey                                    = fileatime($index_file_name) . $file;
					$prototypes_array[$filekey]['content']      = getIndexInfo($index_file_name);
					$prototypes_array[$filekey]['last_updated'] = fileatime($index_file_name);
					$prototypes_array[$filekey]['created']      = filectime($index_file_name);
					$prototypes_array[$filekey]['path']         = './'.$file;
					$index_file_name                            = null;
				}
			}
	        }
	        closedir($dh);
	
		asort($prototypes_array);
		
		return $prototypes_array;
	    }
	}
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN"
   "http://www.w3.org/TR/html4/strict.dtd">

<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<title>standardpixel.com prototypes</title>
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.1/build/reset-fonts-grids/reset-fonts-grids.css">
	<!--link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.1/build/base/base-min.css"-->

<style>
body.sp-blueprint {
font-family: georgia, arial sans-serif;
font-size:11px;
text-align:left;
margin:20px 0;
padding:0;
}

body.sp-blueprint .wrapper{
/*border:solid 1px;*/
width:617px;
margin:0 auto;
}

.sp-blueprint h1 {
font-size:30px;
margin:0;
padding:0 0 5px 0;
font-weight:bold;
color:#333;
}
.sp-blueprint h1 .hothothot {
color:#BB0000;	
}
.sp-blueprint p.sub-title {
/*border:solid 1px;*/
width:100%;
font-size:20px;
display:block;
margin:0 0 10px 0;
padding:3px 0 10px 0;
color:#8E99A4;
border-bottom:solid 3px;
}

.sp-blueprint ul {
margin:0;
padding:0;
}

.sp-blueprint ul li {
list-style:none;
margin:0 0 5px 0px;
position:relative;
padding-bottom:0px;
}
.sp-blueprint ul li div.status {
display:none;
width:15px;
height:15px;
background-color:#f00;
position:absolute;
top:0;left:0;
-moz-border-radius: 40px;
-webkit-border-radius: 40px;
}
.sp-blueprint ul li div.link-wrapper {
font-size:14px;
width:278px;
position:absolute;
top:10px;left:0px;
}
.sp-blueprint ul li div.description {
width:334px;
padding-top:12px;
margin:0 0 0 274px;
font-size:14px;
color:#8E99A4;
-webkit-transition-property: color;
-webkit-transition-duration: .5s;
}
.sp-blueprint ul li:hover div.description {
color:#000;
text-shadow: #ddd 3px 3px 10px;
}

.sp-blueprint ul li:hover div.subdue {
	color:#8E99A4;
}

.sp-blueprint ul li a {
text-decoration:none;
color:#000;
font-weight:800;
-webkit-transition-property: color;
-webkit-transition-duration: .5s;
}

.sp-blueprint ul li:hover a {
color:#BB0000;
text-shadow: #ddd 3px 3px 10px;
}

.sp-blueprint .footer {
display:none;
padding-top:10px;
font-size:8px;
border:solid 1px;
}
.sp-blueprint .footer div {
display:inline;
}
.cc-img {
width:44px;
height:15.5px;
float:left;
padding-right:10px;
}
</style>
</head>
<body class="sp-blueprint">
<div class="wrapper">
	<h1>Standard<span class='hothothot'>Pixel</span> Prototypes</h1>
	<p class="sub-title">The following prototypes are in various degrees between awesomeness and brokeness. Basically this is where I drop ideas.</p>

	<ul>

		<?$prototypes = getPrototypes();?>
		<?if($prototypes) {?>
			<?foreach($prototypes as $prototype) {?>
				<?if(strlen($prototype['content']['title'])) {?>
					<li>
						<div class="status"></div>
						<div class="link-wrapper"><a href="<?=$prototype['path']?>"><?=$prototype['content']['title']?></a></div>
						<?if(strlen($prototype['content']['caption'])) {?>
							<div class="description">
							<?=$prototype['content']['caption']?>
							</div>
						<?} else {?>
							<div class="description subdue">
							[ No Description ]
							</div>
						<?}?>
					</li>
				<?}else{?>
					<!--<?=$prototype['path']?> did not have a title and will not be shown-->
				<?}?>
			<?}?>
		<?} else {?>
			<li>There are no prototypes to show. Shit! I hope the datacenter is okay.</li>
		<?}?>


	</ul>
		Each StandardPixel Prototype by Eric Gelinas is licensed under a Creative Commons Attribution 3.0 United States License.
		Based on a work at standardpixel.com.
	<p class="footer"><a rel="license" href="http://creativecommons.org/licenses/by/3.0/us/"><img class="cc-img" alt="Creative Commons License" style="border-width:0" src="http://i.creativecommons.org/l/by/3.0/us/88x31.png" /></a><br /><span xmlns:dc="http://purl.org/dc/elements/1.1/" href="http://purl.org/dc/dcmitype/InteractiveResource" property="dc:title" rel="dc:type">Each StandardPixel Prototype</span> by <a xmlns:cc="http://creativecommons.org/ns#" href="http://standardpixel.com" property="cc:attributionName" rel="cc:attributionURL">Eric Gelinas</a> is licensed under a <a rel="license" href="http://creativecommons.org/licenses/by/3.0/us/">Creative Commons Attribution 3.0 United States License</a>.<br />Based on a work at <a xmlns:dc="http://purl.org/dc/elements/1.1/" href="http://s.standardpixel.com/proto" rel="dc:source">standardpixel.com</a>.</p>
</div>
</body>
</html>
