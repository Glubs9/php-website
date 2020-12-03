//this function limits the amount of words allowed to be submitted in the review
function word_limit(event, elem, max){
	
	//in the event that the user is over words they should still be allowed to delete.
	//although I am not a fan of embedding the whole function in an if statement the function
	//did not work without it being like this.
	if (event.key != "Backspace") {
		var elem_val = elem.value;
		var amount_of_words = elem_val.split(" ").length;
		console.log(amount_of_words);
		if(amount_of_words > max){
			event.preventDefault();
			alert("the amount of words entered is too high");
		}
	}
}
