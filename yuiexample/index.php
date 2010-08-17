<?php 
$id_map['rowexp'] = Array("path" => "rowexpansion", "title" => "DataTable Control: Row Expansion");
$id_map['rowexp_complex'] = Array("path" => "rowexpansion", "title" => "DataTable Control: Row Expansion - Complex example");

if($_GET["id"]){
	$doc_id = $_GET["id"];

	?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
	<html>
	<head>
		<title>YUI Library Examples: <?= $id_map[ $doc_id ][ 'title' ] ?> </title>



		<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	    	<link rel="stylesheet" type="text/css" href="http://developer.yahoo.com/yui/assets/yui.css?v=3" >
		<link rel="stylesheet" type="text/css" href="http://developer.yahoo.com/yui/assets/dpSyntaxHighlighter.css">
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.7.0/build/datatable/assets/skins/sam/datatable.css" />
	<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.7.0/build/button/assets/skins/sam/button.css" />
	<? if( $_GET[ "test" ] ){ ?>
		<link rel="stylesheet" type="text/css" href="../../j/yui2/build/yuitest/assets/yuitest-core.css" />
		<link rel="stylesheet" type="text/css" href="../../j/yui2/build/yuitest/assets/testlogger.css" />
		<style>
		.yui-log-container{
			width:80%;
			z-index:300;
			position:absolute;
			top:0;
			background-color:#efefef;
			text-align:left;
			margin:10px 100px;
			padding:3px;
			border:dotted 1px #333;
			}
			.yui-log-container .yui-log-ft{
				padding:3px;
				height:100px;
				right:0;
			}
			.yui-log-container .yui-log-bd{
				height:300px;
				overflow:auto;
			}
		</style>
	<? } ?>
	<style>
		.example_nav{text-align:left;}
	</style>
	<!--begin custom header content for this example-->
	<?php
	require "./includes/" . $id_map[ $doc_id ][ 'path' ] . "/" . $doc_id . "_examples/dt_" . $doc_id . "_customheader.php";
	?>
	<!--end custom header content for this example-->



	</head>
	<body class=" yui-skin-sam">
	<div class="example_nav"><a href="?showindex=true">Show all examples</a></div>
	<div id="doc3" class="yui-t2">
	<div id="hd">

		<h1>
			yui example by <span class="standard">standard</span><span class="pixel">pixel</span><span class="com">.com</span>
		</h1>

		<div id="pagetitle"><h1>YUI Library Examples: DataTable Control: <?= $id_map[ $doc_id ][ 'title' ] ?></h1></div>
	</div>
	<div id="bd">


		<div id="yui-main">
			<div class="yui-b">
			  <div class="yui-ge">
				  <div class="yui-u first example">

		<cite class="byline">
			<?php
			require "./includes/" . $id_map[ $doc_id ][ 'path' ] . "/" . $doc_id . "_examples/dt_" . $doc_id . "_contributor.php";
			?>
		</cite>
		<div class="promo">
		<h1><?= $id_map[ $doc_id ][ 'title' ] ?></h1>

		<div class="exampleIntro">
		<?php
		require "./includes/" . $id_map[ $doc_id ][ 'path' ] . "/" . $doc_id . "_examples/dt_" . $doc_id . "_intro.php";
		?>

		</div>	

		<div class="example-container module ">
				<div class="hd exampleHd">
				<p class="newWindowButton yui-skin-sam"><!--<span id="newWindowLinkx"><span class="first-child">--><a href="clean.php?id=<?php echo $doc_id ?>" target="_blank">View example in new window.</a><!--</span></span>-->		
			</div>		<div id="example-canvas" class="bd">


		<!--BEGIN SOURCE CODE FOR EXAMPLE =============================== -->
		<?php
		require "./includes/" . $id_map[ $doc_id ][ 'path' ] . "/" . $doc_id . "_examples/dt_" . $doc_id . "_source.php";
		?>
		<!--END SOURCE CODE FOR EXAMPLE =============================== -->


			</div>


		</div>			
		</div>

		<?php
		require "./includes/" . $id_map[ $doc_id ][ 'path' ] . "/" . $doc_id . "_examples/dt_" . $doc_id . "_description.php";
		?>

	</div>

	<script src="http://developer.yahoo.com/yui/assets/dpSyntaxHighlighter.js"></script>
	<script language="javascript"> 
	dp.SyntaxHighlighter.HighlightAll('code'); 
	</script>


	<script src='http://developer.yahoo.com/yui/assets/YUIexamples.js'></script>
	<? if( $_GET[ "test" ] ){ ?>
		<script type="text/javascript" src="../../j/yui2/build/button/button-min.js"></script>
		<script type="text/javascript" src="includes/<?= $id_map[ $doc_id ][ 'path' ]; ?>/test_<?= $id_map[ $doc_id ][ 'path' ]; ?>.js"></script>
		<script>
			YAHOO.lang.later( 100, null, function(){ 
				var el = document.getElementById( 'yui-log-hd0' );
				el.style.display="block";
				el.style.backgroundColor='#fff';
				el.style.color='#fff';
				el.innerHTML += '<button onclick="var el=document.getElementById( \'yui-gen0\' );el.parentNode.removeChild(el);">Remove Logger</button>' } 
			);
		</script>
	<? } ?>

</body>
</html>
<?
} else {

	echo '<h1>YUI Examples</h1><ul>';
	
	foreach( $id_map as $key => $value ){

		echo '<ul><a href="?id=' . $key . '">[View]</a>&nbsp;<a href="?id=' . $key . '&test=true">[Test]</a>&nbsp;<strong>' . $value[ 'title' ] . '</strong></ul>';
		
	}

	echo '</ul>';

} 
?>