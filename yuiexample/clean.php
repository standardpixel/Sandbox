<?php 
if($_GET["id"]){
	$doc_id = $_GET["id"];
} else {
	$doc_id = 'rowexp_complex';
} 

$id_map['rowexp_basic'] = Array("path" => "rowexpansion", "title" => "DataTable Control: Row Expansion - Basic");
$id_map['rowexp_complex'] = Array("path" => "rowexpansion", "title" => "DataTable Control: Row Expansion - Complex example");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>


    <meta http-equiv="content-type" content="text/html; charset=utf-8">
<title><?= $id_map[ $doc_id ][ 'title' ]; ?></title>

<style type="text/css">
/*margin and padding on body element
  can introduce errors in determining
  element position and are not recommended;
  we turn them off as a foundation for YUI
  CSS treatments. */
body {
	margin:0;
	padding:0;
}
</style>

<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.7.0/build/fonts/fonts-min.css" />
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.7.0/build/datatable/assets/skins/sam/datatable.css" />
<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/dragdrop/dragdrop-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/element/element-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/datasource/datasource-min.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.7.0/build/datatable/datatable-min.js"></script>


<!--begin custom header content for this example-->
<?php
require "./includes/" . $id_map[ $doc_id ][ 'path' ] . "/" . $doc_id . "_examples/dt_" . $doc_id . "_customheader.php";
?>
<!--end custom header content for this example-->

</head>

<body class=" yui-skin-sam">


<h1><?= $id_map[ $doc_id ][ 'title' ]; ?></h1>

<div class="exampleIntro">
	<?php
	require "./includes/" . $id_map[ $doc_id ][ 'path' ] . "/" . $doc_id . "_examples/dt_" . $doc_id . "_intro.php";
	?>	
</div>

<!--BEGIN SOURCE CODE FOR EXAMPLE =============================== -->
<?php
require "./includes/" . $id_map[ $doc_id ][ 'path' ] . "/" . $doc_id . "_examples/dt_" . $doc_id . "_source.php";
?>
<!--END SOURCE CODE FOR EXAMPLE =============================== -->

</body>
</html>