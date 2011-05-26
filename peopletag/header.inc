<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml"
xmlns:fb="http://www.facebook.com/2008/fbml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<title>Generic Photo Site : <?=$app['page_title']?></title>

<style>
	body {
		font-family: "HelveticaNeue-Light", "Helvetica Neue Light", "Helvetica Neue", Helvetica, Arial, "Lucida Grande", sans-serif;
		font-weight: 300;
	}
	
	/*
	*  Person Picker
	*/
	.picker {
		position:absolute;
		left:50%;
		right:50%;
		top:100px;
		width:300px;
		background-color:white;
		border:solid black 1px;
		padding:10px;
	}
	.picker ul {
		max-height:200px;
		overflow:auto;
	}
	.picker ul li:hover {
		background-color:black;
		color:white;
		cursor:pointer;cursor:hand
	}
	
	/*
	*  Photo
	*/
	.photo_container {
		margin-bottom:20px;
		min-height:400px;
		width:900px;
		position:relative;
	}
	
	.photo_container .sidebar {
		position:absolute;
		top:0;
		right:0;
		bottom:0;
		width:200px;
	}
	
	.photo_container h2 {
		margin:5px 0 0 0;
		padding:0;
	}
</style>
</head>
<body>
	<h1>Generic Photo Site : <?=$app['page_title']?></h1>