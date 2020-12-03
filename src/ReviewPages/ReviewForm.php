<?php
$user = json_decode($_SESSION["user"]); //session shoulld have already started
//check user type and not banned
$reviewable = $REVIEWABLE; //this file requires the item to be defined 
$max_words = $MAX_WORDS; //this file reqiures the max words to be defined
//The keywords needing to be defined would be better off in a function
?>

<script src="../ReviewPages/ReviewValidate.js"></script>
<link rel="stylesheet" href="../CSS/ReviewFormStyle.css"></link>

<form action="../ReviewPages/ReviewAdder.php" method="post">
  <input type="hidden" name="user" value='<?php echo json_encode($user) ?>'>
  <input type="hidden" name="reviewable" value='<?php echo json_encode($reviewable) ?>'>
  <input type="hidden" name="rating" id="review_rating" value="3">
  <span id="rating_label"> Rating:   </span>
  <div id="rating_stars">
    <!-- ok so do like unicdoe images later -->
    <!-- all with javascript -->
  </div>
  <label for="review_text"> Review (optional) </label> </br>
  <textarea name="review_text" onkeydown="word_limit(event, this, <?php echo $max_words ?>)"> </textarea> </br>
  <input type="submit" value="Review">
</form> </br> 

<script src="../ReviewPages/ReviewStars.js"></script>
