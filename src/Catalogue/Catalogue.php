<!DOCTYPE html>
<html>

  <head>
    <link rel="stylesheet" href="../CSS/Basic.css">
    <link rel="stylesheet" href="../CSS/NonFlatList.css">
  </head>

  <body>

    <?php require_once "../PHPIncludes/Header.php"?>
    
    <?php
      require_once "../DataClasses/API.php";
      $api = \DataClasses\API::GetInstance();
      $collections = $api->FindCollections();
      $items = $api->FindItems();
      $clients = $api->FindClients();
    ?>

    <h1> Clients </h1>
    <ul id="clients" class="non_flat_list">
      <?php foreach($clients as $n): ?>

      <li>
        <a href="../ItemPages/ClientPage.php?id=<?php echo $n->Username ?>"> <?php echo
           $n->Username ?> </a>
      </li>

      <?php endforeach ?>
    </ul>

    <h1> Collections </h1>
    <ul id="collections">
      <?php foreach($collections as $n): ?>

        <li>
          <img 
            src="<?php echo $n->ImageUrl ?>" 
            onclick="window.location = '../ItemPages/CollectionPage.php?id=<?php echo $n->Id ?>'"
          >
        </li>

      <?php endforeach; ?>
    </ul>

    <h1> Items </h1>
    <ul id="items">
      <?php foreach($items as $n): ?>

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
