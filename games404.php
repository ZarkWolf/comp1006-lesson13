<?php
	$page_title = null;
	$page_title = 'COMP1006 Web Application | Page Not Found';

session_start();
	require_once('header.php');
?>

<div class="jumbotron">
	<h3>Something is missing</h3>
	<p>The page you are looking for does not exist. Try one of the links above.</p>
<?php
	require_once('footer.php');
?>