//the idea is to have a click handler that when you click on one of the stars it will set the
//stars below to on and all the stars above to off
var close_star = '\u2605'; //should absolutely be constant
var open_star = '\u2606'; //shoulds be a constant
var star_count = 3; //count of what the rating is
var amount_of_stars = 5;

function generate_star_span(star_num) {
	return "<span id='star_" + star_num + "' onclick='star_click(" + star_num + ")'>" + close_star + "</span>";
}

function generate_stars() {
	var ret_str = "<span id='stars'>";
	for (i = 1; i <= amount_of_stars; i++) {
		ret_str += generate_star_span(i);
	}
	return ret_str + "</span>";
}

//sets tthe star to open or close depending on what the star_num is and what the star_count is
function set_star(star_num) {
	document.getElementById("star_" + star_num).innerHTML = star_num <= star_count ? close_star : open_star;
} 

//reloads the stars
function reload_stars() {
	for (i = 1; i <= amount_of_stars; i++) {
		set_star(i);
	}
}

//gets called when a star gets clicked
function star_click(star_num) {
	star_count = star_num;
	reload_stars();
	document.getElementById("review_rating").value = star_num;
}

document.getElementById("rating_stars").innerHTML = generate_stars();
reload_stars(); //sets the stars to the right star
