<!DOCTYPE html>
<html>

  <head>
    <link rel="stylesheet" href="../CSS/Basic.css">
  </head>

  <body>

    <?php require_once "../PHPIncludes/Header.php" ?>

    <button onclick="window.history.back()"> Go Back </button>

    <?php 
      require_once "../DataClasses/API.php";
      $api = \DataClasses\API::GetInstance();
      $user = json_decode($_POST["user"]);
      $reviewable = json_decode($_POST["reviewable"]);
    ?>

    <script>
      function GoBack() {
        setTimeout(function(){ window.history.back() }, 1000);
      }
    </script>

    <script>alert('<?php echo var_dump($user) ?>'); </script>

    <!-- note: If I had more time I would factorize this into a list of conditions and messages and
      loop those conditions over the information -->
    <?php if (!(property_exists($user, "Banned"))): ?>
      <script>
        alert("Sorry, you cannot create a review as you are logged in as a client");
        GoBack();
      </script>
    <?php elseif ($user->Banned): ?>
      <script>
        alert("Sorry, you cannot create a review as you are banned");
        GoBack();
      </script>
    <?php elseif ($api->AlreadyReviewed($reviewable, $user)): ?>
      <script>
        alert("Sorry, you cannot create a review as you have already reviewed this item");
        goBack();
      </script>
    <?php elseif ((!empty($_POST["review_text"])) && (substr_count($_POST["review_text"], ' ')) < 10): ?>
      <script>
        alert("Sorry, You cannot create a review as your review is too short");
        goBack();
      </script>
    <?php else: ?>
      <h2> Thank you for your review </h2>

      <?php 
        $rating = $_POST["rating"];
        $reviewText = $_POST["review_text"];
        $api->Review($reviewable, $user, $rating, $reviewText);
      ?>
    <?php endif ?>

  </body>

</html>
