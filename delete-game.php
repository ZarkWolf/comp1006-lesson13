<?php ob_start(); // start the output buffer
			// authentication check
			require_once('auth.php');

	try
	{
		// read the selected game_id from the url's querystring using GET
		$game_id = $_GET['game_id'];

		// if game_id is a number
		if(is_numeric($game_id))
		{
			// connect through embed
			require_once('db.php');

			//making cover image deletable
			//first find the cover image name if it exists
			$sql = "SELECT cover_image FROM games WHERE game_id = :game_id";
			$cmd = $conn->prepare($sql);
			$cmd->bindParam(':game_id', $game_id, PDO::PARAM_INT);
			$cmd->execute();
			$cover_image = $cmd->fetchColumn();

			//delete image if it exists
			if(!empty($cover_image))
			{
				unlink("images/$cover_image");
			}

			// write and run the delete query
			$sql = "DELETE FROM games WHERE game_id = :game_id";

			$cmd = $conn->prepare($sql);
			$cmd->bindParam(':game_id', $game_id, PDO::PARAM_INT);
			$cmd->execute();

			// disconnect
			$conn = null;

			// redirect back to games.php
			header('location:games.php');
		}
	}
	catch (Exception $error)
	{
		// send ourselves an email using the built-in PHP function to send email
		mail('wolfgar_10@hotmail.com', 'Games Application Error', $error, 'From:wolfgar_10@hotmail.com');

		header('location:error.php');
	}
	// clear the output buffer
	ob_flush();
?>