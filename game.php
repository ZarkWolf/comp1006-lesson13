<?php
			// authentication check
			require_once('auth.php');

			// set page title and embed header
			$page_title = 'Video Game Listings';
			require_once('header.php');

			// check if we have a numeric game ID in the querystring
			if((!empty($_GET['game_id'])) && (is_numeric($_GET['game_id'])))
			{

				// if we do, store in a variable
				$game_id = $_GET['game_id'];

				// connect through embed
				require_once('db.php');

				// select all the data for the chosen game
				$sql = "SELECT * FROM games WHERE game_id = :game_id";
				$cmd = $conn->prepare($sql);
				$cmd->bindParam(':game_id', $game_id, PDO::PARAM_INT);
				$cmd->execute();
				$games = $cmd->fetchAll();

				// store each value from the database into a variable
				foreach ($games as $game)
				{
					$name = $game['name'];
					$age_limit = $game['age_limit'];
					$release_date = $game['release_date'];
					$size = $game['size'];
					$cover_image = $game['cover_image'];
				}

				// disconnect
				$conn = null;
			}
		?>

		<h1>Game Details</h1>

		<form action="save-game.php" method="post" enctype="multipart/form-data"><!--the default for "method" is "get" which is not encrypted, it shows in the URL-->
			<fieldset>
				<label for="name" class="col-sm-2">Name:</label>
				<input name="name" id="name" placeholder="Enter a title" value="<?php echo $name; ?>" required />
			</fieldset>
			<fieldset>
				<label for="age_limit" class="col-sm-2">Age Requirement:</label>
				<input name="age_limit" id="age_limit" placeholder="Minimum age" type="number" min="0" max="20" maxlength="2" value="<?php echo $age_limit; ?>" required />
			</fieldset>
			<fieldset>
				<label for="release_date" class="col-sm-2">Release Date:</label>
				<input name="release_date" id="release_date" placeholder="Year of release" type="number" min="1980" max="2016" maxlength="4" value="<?php echo $release_date; ?>" required />
			</fieldset>
			<fieldset>
				<label for="size" class="col-sm-2">Size:</label>
				<input name="size" id="size" placeholder="Size in gigabytes" value="<?php echo $size; ?>" required />
			</fieldset>
			<fieldset>
				<label for="cover_image" class="col-sm-2">Cover Image:</label>
				<input type="file" id="cover_image" name="cover_image" />
			</fieldset>

			<?php
				if(!empty($cover_image))
				{
					echo '<img src="images/' . $cover_image . '" title="Cover Image" class="col-sm-offset-2 thumbnail" />';
				}
			?>

			<input name="game_id" id="game_id" type="hidden" value="<?php echo $game_id; ?>" />
			<button class="btn btn-primary col-sm-offset-2">Save</button>
		</form>
<?php // embed footer
	require_once('footer.php');
?>