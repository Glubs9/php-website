<?php session_start() ?>
<!DOCTYPE html>
<html>

  <head>
    <link rel="stylesheet" href="../CSS/Basic.css">
  </head>

  <body>

    <?php require_once "../PHPIncludes/Header.php" ?>

    <p>
      <?php
        require_once "../DataClasses/API.php";
	$api = \DataClasses\API::GetInstance();
	$user = $api->CreateAccount(
		$_POST["username"],
		$_POST["password"],
		$_POST["name"],
		$_POST["email"],
		$_POST["phoneNumber"]
	);
	$_SESSION["user"] = json_encode($user);

	echo "Thanks for joining us $user->Name !";
      ?>
    </p>

    <a href="../HomePage/Home.php">Return to Home</a>

  </body>
</html>
