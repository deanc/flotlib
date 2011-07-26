<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html>

<head>
	<title>FLOT Examples</title>
	<style type="text/css">
		
	</style>
	<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../flot/excanvas.min.js"></script><![endif]-->
	<script src="../flot/jquery.js" type="text/javascript"></script>
	<script src="../flot/jquery.flot.js" type="text/javascript"></script>
</head>

<body>

<h1>Example of a line graph</h1>

<?php

require_once('../FlotTimeLine.php');

	$flot = new FlotTimeLine;
	

	// add 1 data set
	$flot->addSet('balance1', 'Balance 1', 'points: { show: true }');
	$flot->addData('balance1', strtotime('01/01/2011')*1000, 900);
	$flot->addData('balance1', strtotime('02/01/2011')*1000, 1250);
	$flot->addData('balance1', strtotime('03/01/2011')*1000, 1900);
	$flot->addData('balance1', strtotime('04/01/2011')*1000, 1900);
	$flot->addData('balance1', strtotime('05/01/2011')*1000, 1900);
	
	// add another data set
	$flot->addSet('balance2', 'Balance 2', 'yaxis: 2,points: { show: true }');
	$flot->addData('balance2', strtotime('01/01/2011')*1000, 2010);
	$flot->addData('balance2', strtotime('02/01/2011')*1000, 1789);
	$flot->addData('balance2', strtotime('03/01/2011')*1000, 1978);
	$flot->addData('balance2', strtotime('04/01/2011')*1000, 1200);
	$flot->addData('balance2', strtotime('05/01/2011')*1000, 1500);	
	
	echo $flot->draw();

?>
</body>
</html>