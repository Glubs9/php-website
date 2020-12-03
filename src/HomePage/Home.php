<?php session_start() ?>
<!DOCTYPE html>
<html>

  <head>
    <link rel="stylesheet" href="../CSS/Basic.css">
  </head>

  <body>

    <?php require_once "../PHPIncludes/Header.php"?>

    <?php
       require_once "../DataClasses/API.php";
       $api = \DataClasses\API::GetInstance();
       $topCollection = $api->FindTopCollection();
       $topItems = $api->FindTopItems();
    ?>

    <h2> Top Rated Collection </h2>
    <div id="collection">
      <img 
        src="<?php echo $topCollection->ImageUrl ?>" 
        id="collection_image"
        onclick="window.location='../ItemPages/CollectionPage.php?id=<?php echo $topCollection->Id ?>'"
      >
    </div>

    <h2> Top Rated Items </h2>

    <ul id="top_items">
      <?php foreach($topItems as $n): ?>

        <li>
          <img 
            src="<?php echo $n->ItemImageUrl ?>" 
            onclick="window.location = '../ItemPages/ItemPage.php?id=<?php echo $n->Id ?>'"
          >
        </li>

      <?php endforeach; ?>
    </ul>

  </body>

</html>
