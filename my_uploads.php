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
	<title> My Uploads </title>
	<meta charset="UTF-8">
	<link rel="stylesheet" type="text/css" href="post_style.css">
	<script src="script.js" defer></script>
	<script src="https://unpkg.com/scrollreveal@4"></script>
</head>
<body>
	<header>
		<div id="menu">
			<h1>Foorum</h1>
			<?php
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
				<li><a href="post.php?email=<?php echo "$email";?>" title="Post your food">Posting</a></li>
			  	<li><a href="gallery.php?email=<?php echo "$email";?>" title="Food Gallery">Gallery</a></li>
			  	<li>My Uploads</li>
			</ul>
		</div>
	</header>
	<main>
		<?php
			// if user is logged in, aka email is not empty
			if ($email!==''){
				echo "<p id='welcome'><span>Welcome $username! Here displays all your history uploads.</span></p>";
				echo "<div class='gallery_div'>";
				echo "<figure>";

				class MyDB extends SQLite3
				{
				    function __construct()
				    {
				        $this->open('posts.db');
				    }
				}

				$mydb = new MyDB();         // connect to database

				$statement = "SELECT image,choice FROM posts WHERE email='$email' ORDER BY time_created DESC;";
				$run = $mydb->query($statement);				// run the command
				if ($run){										// so no errors in the query
					while ($row = $run->fetchArray()) {				// while still a row to parse
						$image = $row['image'];
						$choice = $row['choice'];
						$source = "./images/uploads/$choice/$image";
						echo "<img src='$source' alt='pic of post' class='gallery_img'>";
						// display the all images this user upload
					}
				}
				$mydb->close();

				?>
					</figure>
				</div>
			</main>
			<footer>
				<small>Created &copy; 2019 by Yunning Qiu</small>
			</footer>
		<?php
			}

			// if user is not logged in, display message
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

