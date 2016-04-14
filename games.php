<?php ob_start();

			// authentication check
			require_once('auth.php');

			// set page title and embed header
			$page_title = 'Video Game Listings';
			require_once('header.php');
		?>

		<h1>Video Games</h1>

		<div class="col-sm-12 text-right">
			<form action="games.php" method="get" class="form-inline">
				<label for="keywords">Keywords:</label>
				<input name="keywords" id="keywords" />
				<select name="search_type" id="search_type">
					<option value="OR">Any Keyword</option>
					<option value="AND">All Keywords</option>
				</select>
				<button class="btn btn-primary">Search</button>
			</form>
		</div>

		<?php
			// add an error handler in case anything breaks
			try
			{
				// connect through embed
				require_once('db.php');

				// write the query to fetch game data
				$sql = "SELECT * FROM games";

				//check for search keywords
				$final_keywords = null;
				
				if(!empty($_GET['keywords']))
				{
					$keywords = $_GET['keywords'];

					//convert the single value of the keywords to an array
					$word_list = explode(" ", $keywords);

					// start the where clause and initialize the variables
					$sql .= " WHERE ";
					$where = "";
					$counter = 0;

					//check for OR / AND from the seatch type dropdown
					$search_type = $_GET['search_type'];

					foreach($word_list as $word)
					{
						//loop through the array of words
						if($counter > 0)
						{
							$where .= " $search_type ";
						}

						$where .= " name LIKE ?";
						$word_list[$counter] = '%' . $word . '%';
						$counter++;
					}

					$sql .= $where;
				}

				//add order by at the end
				$sql .= " ORDER BY name";
				//echo $sql; used to see if mutliple statements were being collected properly from the URL

				// run the query and store the results into memory
				$cmd = $conn->prepare($sql);
				$cmd->execute($word_list);//only required if executing 2 SQL statements at once
				$games = $cmd->fetchAll();

				// start the table and add the headings
				echo '<table class="table table-striped table-hover sortable"><thead>
				<th><a href="#">Name</a></th>
				<th><a href="#">Age Requirement</a></th>
				<th><a href="#">Release Date</a></th>
				<th><a href="#">Size (Gigabytes)</a></th>
				<th>Edit</th>
				<th>Delete</th></thead><tbody>';

				// loop through the data, creating a new table row for each game and putting each value in a new column
				foreach($games as $game)
				{
					echo '<tr><td>' . $game['name'] . '</td>
					<td>' . $game['age_limit'] . '</td>
					<td>' . $game['release_date'] . '</td>
					<td>' . $game['size'] . '</td>
					<td><a href="game.php?game_id=' . $game['game_id'] . '">Edit</a></td>
					<td><a href="delete-game.php?game_id=' . $game['game_id'] . '" title="Delete this game" onclick="return confirm(\'Are you sure you want to delete this game?\');">Delete</a></td></tr>';
				}

				// close the table
				echo '</tbody></table>';

				//facebook plugin
				echo '<div class="fb-comments" data-href="http://gc200305022.computerstudi.es/comp1006/lessons/lesson12/games.php" data-numposts="10"></div>';

				// disconnect
				$conn = null;
			}
			catch (Exception $error)
			{
				// send ourselves an email using the built-in PHP function to send email
				mail('Dracalas@gmail.com', 'Games Application Error', $error, 'From:wolfgar_10@hotmail.com');

				header('location:error.php');
			}

			echo '<script src="scripts/sorttable.js"></script>';

			// embed footer
			require_once('footer.php');

			ob_flush();
		?>