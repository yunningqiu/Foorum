/* give the srolling and appearing effect of the webpage*/
ScrollReveal().reveal('div');


/* AJAX calling home_update.php to display the two most recent post in database */
let xmlhttp = new XMLHttpRequest();		           // object to do ajax with
xmlhttp.onreadystatechange = function(){
	if (this.readyState === 4 && this.status === 200){    // when the operation is complete and successful
		document.getElementById("updates_home").innerHTML = this.responseText;
		// change HTML to store what came back
	}
};

xmlhttp.open("POST", "home_update.php", true);         // set POST request to read from php, doing it 'asynchronously'
xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
xmlhttp.send();
