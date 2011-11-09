<!DOCTYPE html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
	<meta name="viewport" content="width=1036">
	<title>Test</title>
	<script src="http://code.jquery.com/jquery-1.7.js" type="text/javascript" charset="utf-8"></script>
	<script src="require.js" type="text/javascript" charset="utf-8"></script>
</head>
<body>
	<script>		
		//$.getScript("simple-model.js", function() {
		//	console.log(arguments);
		//});
		require(["simple-model.js"],
		    function() {
		        //the html variable will be the text
		        //of the some/module.html file
		        //the css variable will be the text
		        //of the some/module.css file.
		
			var sm = new SM({poop:'poop'});
			
			
			
			sm.run('poop');
		    }
		);
	</script>
</body>
</html>