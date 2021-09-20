#!/usr/local/bin/php
<?php
	ob_start();
	session_name('finalproject');     
	session_start();    

	$email = $_GET['email'];
	$parts = explode('@', $email);
	$username = $parts[0];
	// extract the string before @ as the user's username

?>


<!DOCTYPE html>
<html lang="en">
<head>
	<title> Post Your Food </title>
	<link rel="stylesheet" type="text/css"  href="post_style.css">
	<script src="script.js" defer></script>
	<script src="https://unpkg.com/scrollreveal@4"></script>
	<meta charset="UTF-8">
</head>
<body>
	<header>
		<div id="menu">
			<h1>Foorum</h1>
			<?php
				// if user is logged in, display the log out button
				if ($email!==''){ 
			?>
					<div id='logout_div'>
						<button id="logout" onclick="window.location.href='logout.php'">Log out</button>
					</div>
			<?php 
				} 
			?>
			<ul>
				<li><a href="index.html" title="Foorum Homepage">Home</a></li>
				<li>Posting</li>
			  	<li><a href="gallery.php?email=<?php echo "$email";?>" title="Food Gallery">Gallery</a></li>
			  	<li><a href="my_uploads.php?email=<?php echo "$email";?>" title="My Uploads">My Uploads</a></li>
			</ul>
		</div>
	</header>
	<main>
		<?php
			// if user is logged in, display the upload section and live post section
			if ($email!==''){
		?>
				<p id="welcome">
					<span>Welcome <?php echo "$username";?>!</span>
				</p>

				<form enctype="multipart/form-data" id="input_form" method="post" action="<?php echo "post.php?email=$email";?>">
					<p> Upload your food image: </p>
					<input type="file" name="image" id="image"/>
					<br><br>
					<label for="west_radio">Western Cuisine</label>
					<input type="radio" name="cuisine_radio" value="western" id="west_radio">
					<label for="east_radio">Eastern Cuisine</label>
					<input type="radio" name="cuisine_radio" value="eastern" id="east_radio">
					<br><br>
					<label for="text"> Describe your food below: </label> <br>
					<textarea id="text" name="text" rows=2></textarea>
					<br>
					<input type="submit" value="Post" name="post" id="post">
				</form>

				<br><br>

				<div id="updates">
					<?php
						class MyDB extends SQLite3
						{
						    function __construct()
						    {
						        $this->open('posts.db');
						    }
						}
						// create a class that opens posts.db

						$mydb = new MyDB();         // connect to database

						$statement = 'SELECT email, time_created, choice, description, image FROM posts ORDER BY time_created DESC;';
						$run = $mydb->query($statement);          // run the command
						if ($run){             						// so no errors in the query
							while ($row = $run->fetchArray()) {              // while still a row to parse
								$email = $row['email'];
								$time_created = $row['time_created'];
								$choice = $row['choice'];
								$description = $row['description'];
								$image = $row['image'];
								// store all the data in post

								$time_current = time();
								$time_elapsed = $time_current - $time_created;
								// calculate how much time has passed since the post was created

								if ($time_elapsed < 60){
									$time = "less than a minute ago";
								}
								// when posted less than a minute ago
								elseif ($time_elapsed >= 60 && $time_elapsed < 3600){
									$time = round($time_elapsed/60). " min ago";
								}
								// when posted less than an hour ago
								else if ($time_elapsed >= 3600 && $time_elapsed < 3600 * 24){
									$time = round($time_elapsed/3600). " h ago";
								}
								// when posted less than a day ago
								else if ($time_elapsed >= 3600*24 && $time_elapsed < 3600*24*31){
									$time = round($time_elapsed/(3600*24)). " days ago";
									if (round($time_elapsed/(3600*24)) === 1){
										$time = "1 day ago";
									}
								}
								// when posted less than a month ago
								else if ($time_elapsed >= 3600*24*31){
									$time = "more than a month ago";
								}
								// when posted more than a month ago
								

								$parts = explode('@', $email);
								$user = $parts[0];
								// extract username from the logged in email

								$source = "./images/uploads/$choice/$image";

								echo "<div class='post_div'>";
								echo "<p class='username'> $user </p>";
								echo "<p class='uploaded'> uploaded a photo </p>";
								echo "<br>";
								echo "<p class='time'> $time </p>";
								echo "<p class='choice'> #$choice </p>";
								echo "<p class='description'>$description </p>";
								echo "<img src='$source' alt='pic of post' class='post_img'>";
								echo "</div>";

							}
						}
						$mydb->close();
						// read from sql in time order
					?>
				</div>
			</main>
			<footer>
				<small>Created &copy; 2019 by Yunning Qiu</small>
			</footer>
		<?php
			}

			// if user is not logged in, display the message only
			else{
				?>
				<p id='welcome'>
					<span>Welcome! Please log in first to see your history uploads.</span>
				</p>
				<div id='login_div'>
					<button id="login" onclick="window.location.href='login.php'">Log in</button>
				</div>
		<?php
			}
		?>
</body>
</html>


<?php
	/* --------------------------------------------write post information to sql ------------------------------------------*/
	try{   	
		$mydb = new SQLite3('posts.db');
		// create database to store user's information
	}
	catch(Exception $ex) {
		echo $ex -> getMessage();
	}
	$statement = 'CREATE TABLE IF NOT EXISTS posts(email TEXT, time_created INTEGER, choice TEXT, description TEXT, image TEXT);';
	$run = $mydb->query($statement); 
	$mydb->close();
	// create table posts storing each post's information


	if(isset($_POST['post'])){
		/* store all variables user inputs */		
		$description = $_POST['text'];
		$email = $_GET['email'];
		
		if (isset($_POST['cuisine_radio'])){
			$choice = $_POST['cuisine_radio'];
		}
		// store user input description and radio button choice
		
		$time_created = time();
		// store the current time
		
		$fileName = trim($_FILES['image']['name']);	
		// store image name through $_FILE 

		if ($choice==="eastern"){
			$location = dirname(realpath(__FILE__)) . '/images/uploads/eastern/' . $fileName;
			move_uploaded_file($_FILES['image']['tmp_name'], $location);
		}
		// if user clicked eastern, move image to eastern folder
		elseif ($choice==="western"){
			$location = dirname(realpath(__FILE__)) . '/images/uploads/western/' . $fileName;
			move_uploaded_file($_FILES['image']['tmp_name'], $location);
		}
		// if user clicked western, move image to western folder

		$mydb->open('posts.db');
		$statement = "INSERT INTO posts(email, time_created, choice, description, image) VALUES ('$email', $time_created, '$choice', '$description', '$fileName');";
		$run = $mydb->query($statement); 
		$mydb->close();
		// insert a new post record

		echo "<meta http-equiv='refresh' content='0'>";
		// refresh the page after clicking post
	}
?>
