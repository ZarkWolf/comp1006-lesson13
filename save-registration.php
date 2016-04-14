<?php
	$page_title = 'Saving your Registration...';
	require_once ('header.php');

	// store the inputs into variables
	$username = $_POST['username'];
	$password = $_POST['password'];
	$confirm = $_POST['confirm'];
	$ok = true;

	// validation
	if (empty($username))
	{
		echo 'Username is required<br />';
		$ok = false;
	}

	if (empty($password))
	{
		echo 'Password is required<br />';
		$ok = false;
	}

	if ($password != $confirm)
	{
		echo 'Passwords must match<br />';
		$ok = false;
	}

	//recaptcha validation
	//store values into variables
	$secret = "6LcPmgQTAAAAAD2XQXfomdwCcyxkQ-x7mJLoGQqz";
	$response = $_POST['g-recaptcha-response'];

	//initialize the curl library to do a hidden post to google
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, "https://www.google.com/recaptcha/api/siteverify");
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_POST, true);

	//create and fill array with our post values
	$post_data = array();
	$post_data['secret'] = $secret;
	$post_data['response'] = $response;
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

	//execute the api call and store the result
	$result = curl_exec($ch);
	curl_close($ch);

	//convert response object from JSON to a PHP array
	$result_array = json_decode($result, true);

	//we only want them to sign-up if success is true
	if($result_array['success'] != true)
	{
		echo 'Are you human?';
		//just for bug fixing
		//echo $result_array['error-codes'];
		$ok = false;
	}

	if ($ok)
	{
		// connect
		require_once('db.php');

		// set up the sql insert
		$sql = "INSERT INTO users (username, password) VALUES (:username, :password)";

		// hash the password
		$hashed_password = hash('sha512', $password);// sha512 always hashes to 128 characters exactly

		// fill the parametres and execute
		$cmd = $conn->prepare($sql);
		$cmd->bindParam(':username', $username, PDO::PARAM_STR, 50);
		$cmd->bindParam(':password', $hashed_password, PDO::PARAM_STR, 128);
		$cmd->execute();

		// disconnect
		$conn = null;

		echo 'Your registration was successful. <a href="login.php">Log In</a>';
	}

	require_once ('footer.php');
?>