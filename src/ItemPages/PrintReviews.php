<?php declare(strict_types=1) ?>

<?php
require_once "../DataClasses/Reviewable.php";
//although this uses Review.php it is not necersarry to include it

function makeStars(int $rating) 
{
    $close_star = "&#9733;";
    $open_star = "&#9734;";
    $ret_str = "";

    for ($i = 1; $i <= 5; $i++) {
	    $ret_str .= (($i <= $rating) ? $close_star : $open_star); //span to separate the stars
    }

    return $ret_str;
}

function PrintReviews(\DataClasses\Reviewable $reviewable) 
{
        $printStr = "
          <link rel='stylesheet' href='../CSS/NonFlatList.css'>
	  <link rel='stylesheet' href='../CSS/ReviewsStyle.css'>
          <ul id='reviews' class='non_flat_list'>
        ";

	foreach ($reviewable->Reviews as $n){
	
		$reviewer = $n->Reviewer; //the string can't handle a property of a property
		$stars = makeStars($n->Rating);
		$printStr .= "
                  <li class='review'>

                    <span class='rating'> $stars </span> </br>
                    <p> $n->ReviewText </p>
                    reviewed by: $reviewer->Username </br>
                  </li>
		";
	}

        $printStr .= "</ul>";
	return $printStr;
}

?>
