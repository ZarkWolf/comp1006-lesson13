<!DOCTYPE html>
<html lang="en">
	<head>
		<meta content="text/html; charset=utf-8" http-equiv="content-type">
		<title><?php echo $page_title; ?></title>
		<!-- Latest compiled and minified CSS -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
		<!-- Optional theme -->
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap-theme.min.css">

		<!-- Font Awesome CSS -->
		<link href="//netdna.bootstrapcdn.com/twitter-bootstrap/2.3.2/css/bootstrap-combined.no-icons.min.css" rel="stylesheet">
		<link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet">
	</head>

	<body>
		<nav class="navbar navbar-default">
			<a href="default.php" title="COMP 1006 Web Application" class="navbar-brand"><i class="icon-gamepad icon-2"></i> COMP 1006 Web App</a>
			<ul class="nav navbar-nav navbar-right">
				<?php
				// private links
				if(!empty($_SESSION['user_id']))
				{
				?>
				<li><a href="game.php" title="Add a game to the list">Add Game</a></li>
				<li><a href="games.php" title="View the games list">View Games</a></li>
				<li><a href="gallery.php" title="View the gallery of video game covers">Cover Gallery</a></li>
				<li><a href="logout.php" title="Logout of your account">Logout</a></li>

				<?php
				}
				else
				{
				?>

				<!-- public links -->
				<li><a href="register.php" title="Register">Register</a></li>
				<li><a href="login.php" title="Login to our site">Login</a></li>
				<?php } ?>
			</ul>
		</nav>

		<main class="container">