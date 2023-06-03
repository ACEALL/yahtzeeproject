<!DOCTYPE html>
<html>
<head>
	<title><?php echo $page_title; ?></title>	
	<link rel="stylesheet" href="../views/headers/style.css" type="text/css" media="screen" />
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
</head>
<body>
	<div id="header">
	<h1>Yahtzee</h1>
		<div id="user-info">
			<?php
			if(isset($_SESSION['user_id'])) {
				// User is logged in
				echo '<span>Welcome, '.$_SESSION['name'].'</span>';
				echo '<a href="/yahtzee/views/logout.php" id="logout-link">Logout</a>';
			} else {
				// User is not logged in
				echo '<a href="/yahtzee/views/login.php">Login /</a>';
				echo '<a href="/yahtzee/views/register.php">Register</a>';
			}
			?>
		</div>
		
	</div>
	<div id="navigation">
		<ul>
			<li><a href="/yahtzee/views/index.php">Main Menu</a></li>
		</ul>
	</div>
	<div id="content">
	
</body>
</html>