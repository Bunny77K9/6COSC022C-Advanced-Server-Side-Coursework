<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>404 - Page Not Found</title>
	<style>
		body {
			display: flex;
			align-items: center;
			justify-content: center;
			height: 100vh;
			margin: 0;
			font-family: Arial, sans-serif;
			background-color: #fff;
			color: #333;
		}
		.container {
			display: flex;
			align-items: center;
		}
		.error-code {
			font-size: 48px;
			font-weight: bold;
		}
		.separator {
			border-left: 1px solid #ccc;
			height: 70px;
			margin: 0 30px;
		}
		.error-message {
			font-size: 18px;
		}
		a {
			color: #0066cc;
			text-decoration: none;
		}
		a:hover {
			text-decoration: underline;
		}
	</style>
</head>
<body>
<div class="container">
	<div class="error-code">404</div>
	<div class="separator"></div>
	<div class="error-message">This page could not be found. <a href="<?php echo base_url(); ?>">Home</a></div>
</div>
</body>
</html>
