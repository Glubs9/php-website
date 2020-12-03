<!DOCTYPE html>
<html>

  <head>
    <link rel="stylesheet" href="../CSS/Basic.css">
  </head>

  <body>

    <?php require_once "../PHPIncludes/Header.php" ?> 

    <?php
      require_once "../DataClasses/API.php";
      $api = \DataClasses\API::GetInstance();
      $client = $api->GetClientByName($_GET["id"]);
    ?>

    <h1> <?php echo $client->Username ?> </h1>

    <!-- add other info later -->
    <ul>
      <?php foreach ($client->Collections as $n): ?>
        <li>
          <img onclick="window.location = 'CollectionPage.php?id=<?php echo $n->Id ?>'" src="<?php echo $n->ImageUrl?>">
        </li>
      <?php endforeach; ?>
    </ul>

  </body>

</html>	
