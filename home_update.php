#!/usr/local/bin/php
<?php
	ob_start();
	session_name('finalproject');     
	session_start();    

	class MyDB extends SQLite3
	{
	    function __construct()
	    {
	        $this->open('posts.db');
	    }
	}

	$mydb = new MyDB();         // connect to database

	$statement = 'SELECT email, time_created, choice, description, image FROM posts ORDER BY time_created DESC;';
	$run = $mydb->query($statement);			// run the command

	$count = 0;
	if ($run){											// so no errors in the query
		while ($row = $run->fetchArray()) {				// while still a row to parse
			if ($count < 2){							// only get the first two posts 
				$count++;
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
	}
	$mydb->close();
	// read the first two posts from sql in time order
?>