<?php session_start() //not entirely necersarry ?>
<!DOCTYPE html>
<html>

  <head>
    <link rel="stylesheet" href="../CSS/Basic.css">
  </head>

  <body>

    <?php require_once "../PHPIncludes/Header.php" ?>

    <?php
      require_once "../DataClasses/API.php";
      require_once "./PrintReviews.php";
      $api = \DataClasses\API::GetInstance();
      $collection = $api->GetCollectionById(intval($_GET["id"])); //intval not necersarry
    ?>

    <div id="collection">
      <h3> <?php echo $collection->Name ?> </h3> </br>
      <p> <?php echo $collection->Description ?> </p> </br>
      <img src="<?php echo $collection->ImageUrl ?>" id="collection_image">

      <h3> Items: </h3>

      <ul>
        <?php foreach ($collection->Items as $n): ?>
          <li>
            <img onclick="window.location = 'ItemPage.php?id=<?php echo $n->Id ?>'" src="<?php echo $n->ItemImageUrl?>">
          </li>
        <?php endforeach; ?>
      </ul>

      <h3> Reviews: </h3>
      <?php echo PrintReviews($collection); ?>

      <?php 
        $REVIEWABLE = $collection; //the review form needs reviewable to be set
	$MAX_WORDS = 100;
        require_once "../ReviewPages/ReviewForm.php"; 
      ?>

    </div>

  </body>
</html>
