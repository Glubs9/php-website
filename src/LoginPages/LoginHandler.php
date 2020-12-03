<?php session_start() ?>
<!DOCTYPE html>
<html>

  <head>
    <link rel="stylesheet" href="../CSS/Basic.css">
    <meta http-equiv = "refresh" content = "0; url = ../HomePage/Home.php" />
  </head>

  <body>

    <?php require_once "../PHPIncludes/Header.php" ?>

    <p>
      <?php
        require_once "../DataClasses/API.php";
        $api = \DataClasses\API::GetInstance();
        $username = $_POST["username"];
        $password = $_POST["password"];
        $user = $api->Login($username, $password);
        $_SESSION["user"] = json_encode($user); //the reason I am storing as a string is that php session do weird things to objects so this removes that problem.
        echo "Welcome $user->Name";
      ?>
    </p>

    <p> this page should automatically redirect to the home page </p>
    <a href="../HomePage/Home.php">Return to Home</a>

  </body>
</html>
