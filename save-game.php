<?php ob_start(); // start the output buffer

	//authorization
	require_once('auth.php');

	try
	{
		// store the form inputs in variables
		$name = $_POST['name'];
		$age_limit = $_POST['age_limit'];
		$release_date = $_POST['release_date'];
		$size = $_POST['size'];

		// add game_id in case we are editing
		$game_id = $_POST['game_id'];

		// create a flag to track the completion status
		$ok = true;

		// do our form validation before saving
		if(empty($name))
		{
			echo'Name is required<br />';
			$ok = false;
		}
		if(empty($age_limit) || !is_numeric($age_limit))
		{
			echo'Age requirement is required and must be a number<br />';
			$ok = false;
		}
		if(empty($release_date) || !is_numeric($release_date))
		{
			echo'Release date is required and must be a number<br />';
			$ok = false;
		}
		if(empty($size) || !is_numeric($size))
		{
			echo'Size is required and must be a number<br />';
			$ok = false;
		}

		//check for photo, validate, and save it if we have one
		if(!empty($_FILES['cover_image']['name']))
		{
			$cover_image = $_FILES['cover_image']['name'];
			$type = $_FILES['cover_image']['type'];
			$tmp_name = $_FILES['cover_image']['tmp_name'];

			//validate file type
			if($type != 'image/jpeg')
			{
				echo 'Invalid JPG<br />';
				$ok = false;
			}
			else
			{
				if($ok)
				{
					$final_image = session_id() . "-" . $cover_image;
					move_uploaded_file($tmp_name, "images/$final_image");//save it
				}
			}
		}

		// save ONLY if the form is complete
		if($ok)
		{

			// connect through embed
			require_once('db.php');

			// if we have an existing game_id, run an update
			if(!empty($game_id))
			{
				$sql = "UPDATE games SET name = :name, age_limit = :age_limit, release_date = :release_date, size = :size, cover_image = :cover_image WHERE game_id = :game_id; ";
			}
			else
			{
				// set up an sql command to save the new game
				$sql = "INSERT INTO games (name, age_limit, release_date, size, cover_image) VALUES (:name, :age_limit, :release_date, :size, :cover_image); ";
			}

			// set up command object
			$cmd = $conn->prepare($sql);

			// fill the placeholders with the input variables
			$cmd->bindParam(':name', $name, PDO::PARAM_STR, 50);
			$cmd->bindParam(':age_limit', $age_limit, PDO::PARAM_INT);
			$cmd->bindParam(':release_date', $release_date, PDO::PARAM_INT);
			$cmd->bindParam(':size', $size, PDO::PARAM_INT);
			$cmd->bindParam(':cover_image', $final_image, PDO::PARAM_STR, 255);

			// add the game_id parameter if updating
			if(!empty($game_id) && is_numeric($game_id))
			{
				$cmd->bindParam(':game_id', $game_id, PDO::PARAM_INT);
			}

			// execute the insert
			$cmd->execute();

			// show message
			echo "Game Saved";

			// disconnecting from the database
			$conn = null;

			// redirect back to games.php
			header('location:games.php');
		}
	}
	catch (Exception $error)
	{
		// send ourselves an email using the built-in PHP function to send email
		mail('Dracalas@gmail.com', 'Games Application Error', $error, 'From:wolfgar_10@hotmail.com');

		header('location:error.php');
	}

	require_once('footer.php');

	// clear the output buffer
	ob_flush();
?>