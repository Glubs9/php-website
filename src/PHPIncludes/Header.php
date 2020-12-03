<?php session_start() ?>
<!-- this file contains the header bar at the top of every page -->
<link rel="stylesheet" href="../CSS/HeaderStyle.css">
<div id="navigation_bar">
  <a class="bar_item" href="../HomePage/Home.php">Home</a>
  <a class="bar_item" href="../LoginPages/Login.php">Login and Sign Up</a> 
  <a class="bar_item" href="../Catalogue/Catalogue.php">Catalogue</a>
  <?php if (array_key_exists("user", $_SESSION)): ?>
    <span class="bar_item" id="user_name"> <?php echo json_decode($_SESSION["user"], true)["Name"] ?></span>
  <?php else: ?>
    <span class="bar_item" id="user_name"> Not Logged In </span>
  <?php endif ?>
</div>
