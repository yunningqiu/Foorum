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
	<title> Food Gallery </title>
	<link rel="stylesheet" type="text/css" href="post_style.css">
	<script src="script.js" defer></script>
	<script src="https://unpkg.com/scrollreveal@4"></script>
	<meta charset="UTF-8">
</head>
<body>
	<header>
		<div id="menu">
			<h1>Foorum</h1>
			<ul>
				<li><a href="index.html" title="Foorum Homepage">Home</a></li>
				<li><a href="post.php" title="Post your food">Posting</a></li>
			  	<li>Gallery</li>
			  	<li><a href="my_uploads.php?email=<?php echo "$email";?>" title="My Uploads">My Uploads</a></li>
			</ul>
		</div>
	</header>
	<main>
		<p id="welcome">
			<span>Welcome! Here displays all user-uploaded images categorized by western or eastern cuisines.</span>
		</p>

		<div class="gallery_div">
			<h2 class="gallery_head"><span class="green">Western Cuisine</span></h2>
			<figure id="western_div">
				<?php
					class MyDB extends SQLite3
					{
					    function __construct()
					    {
					        $this->open('posts.db');
					    }
					}

					$mydb = new MyDB();         // connect to database

					$statement = "SELECT image FROM posts WHERE choice='western' ORDER BY time_created DESC;";
					$run = $mydb->query($statement);          // run the command
					if ($run){             						// so no errors in the query
						while ($row = $run->fetchArray()) {              // while still a row to parse
							$image = $row['image'];
							$source = "./images/uploads/western/$image";
							echo "<img src='$source' alt='pic of post' class='gallery_img'>";
							// display all images that are chosen western
						}
					}
					$mydb->close();
				?>
			</figure>
		</div>

		<div class="gallery_div">
			<h2 class="gallery_head"><span class="green">Eastern Cuisine</span></h2>
			<figure id="eastern_div"> 
				<?php
					$mydb = new MyDB();         // connect to database

					$statement = "SELECT image FROM posts WHERE choice='eastern' ORDER BY time_created DESC;";
					$run = $mydb->query($statement);          // run the command
					if ($run){             						// so no errors in the query
						while ($row = $run->fetchArray()) {              // while still a row to parse
							$image = $row['image'];
							$source = "./images/uploads/eastern/$image";
							echo "<img src='$source' alt='pic of post' class='gallery_img'>";
							// display all images that are chosen western
						}
					}
					$mydb->close();
				?>
			</figure>
		</div>
	</main>
</body>
</html>

