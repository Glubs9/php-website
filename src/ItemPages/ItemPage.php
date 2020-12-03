<?php session_start() //not entirely necersarry ?>
<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="../CSS/Basic.css">
  </head>

  <body>

    <?php require_once "../PHPIncludes/Header.php" ?> 

    <!-- Probably want to add a 'go to collection this item is from' -->

    <?php 
      require_once "../DataClasses/API.php";
      require_once "./PrintReviews.php";
      $api = \DataCLasses\API::GetInstance();
      $item = $api->GetItemById(intval($_GET["id"]));
    ?>

    <div id="item">
      <h3> <?php echo $item->ItemName ?> </h3>
      <img src="<?php echo $item->ItemImageUrl ?>" id="item_image">
      <p> <?php echo $item->ItemDescription ?> </p> </br>
      Item Created on: <?php echo $item->ItemCreationDate ?> </br>
      <button onclick="window.location = 'CollectionPage.php?id=<?php echo $api->GetCollectionByItem($item)->Id ?>'">
        Go to collection containing this item
      </button>

      <h3> Reviews: </h3>
      <?php echo PrintReviews($item); ?>

      <?php 
        $REVIEWABLE = $item; //the review form needs reviewable to be set
	$MAX_WORDS = 50;
        require_once "../ReviewPages/ReviewForm.php"; 
      ?>

    </div>

  </body>
</html>
