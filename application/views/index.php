<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<!doctype>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<meta name="viewport"
		  content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Dev Forum</title>

	<script src="https://code.jquery.com/jquery-3.3.1.min.js" type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/underscore.js/1.9.1/underscore-min.js"
			type="text/javascript"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/backbone.js/1.3.3/backbone-min.js"
			type="text/javascript"></script>

	<!-- Include the app.js file -->
	<script src="../../assets/js/app.js" type="text/javascript"></script>

	<!-- Router -->
	<script src="../../assets/js/routers/approuter.js" type="text/javascript"></script>

	<!-- Views -->
	<script src="../../assets/js/views/loginView.js" type="text/javascript"></script>
	<script src="../../assets/js/views/signupView.js" type="text/javascript"></script>
	<script src="../../assets/js/views/homeView.js" type="text/javascript"></script>
	<script src="../../assets/js/views/questionView.js" type="text/javascript"></script>
	<script src="../../assets/js/views/newQuestionView.js" type="text/javascript"></script>
	<script src="../../assets/js/views/questionAnswerView.js" type="text/javascript"></script>
	<script src="../../assets/js/views/answerView.js" type="text/javascript"></script>
	<script src="../../assets/js/views/bookmarksView.js" type="text/javascript"></script>
	<script src="../../assets/js/views/categoryView.js" type="text/javascript"></script>
	<script src="../../assets/js/views/tagsView.js" type="text/javascript"></script>
	<script src="../../assets/js/views/profileView.js" type="text/javascript"></script>
	<script src="../../assets/js/views/navbarView.js" type="text/javascript"></script>

	<!-- Models -->
	<script src="../../assets/js/models/user.js" type="text/javascript"></script>
	<script src="../../assets/js/models/question.js" type="text/javascript"></script>
	<script src="../../assets/js/models/answer.js" type="text/javascript"></script>

	<!-- Adding CDN -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
		  integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
		  integrity="sha512-... (hash value) ..." crossorigin="anonymous"/>
	<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

	<!-- Include Noty CSS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.css"/>

	<!-- Include Noty JavaScript -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/noty/3.1.4/noty.min.js"></script>

	<!-- Script CDN -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"
			integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49"
			crossorigin="anonymous"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
			integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
			crossorigin="anonymous"></script>
	<script src="../../assets/plugins/jquery-validate/jquery.validate.js"></script>

	<!-- include the css -->
	<link rel="stylesheet" href="../../assets/css/styles.scss"/>

	<!-- include a theme -->
	<link rel="stylesheet" href="../../assets/plugins/css/themes/default.css"/>

	<!-- Include Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&display=swap"
		  rel="stylesheet">

</head>
<body>

<div id="navbar-container"></div>
<main class="container"></main>

<?php include 'components/loginView.php'; ?>

<?php include 'components/signupView.php'; ?>

<?php include 'components/homeView.php'; ?>

<?php include 'components/categoryView.php'; ?>

<?php include 'components/tagsView.php'; ?>

<?php include 'components/profileView.php'; ?>

<?php include 'components/questionsView.php'; ?>

<?php include 'components/newQuestionView.php'; ?>

<?php include 'components/questionAnswerView.php'; ?>

<?php include 'components/answersView.php'; ?>

<?php include 'components/bookmarksView.php'; ?>

<?php include 'components/navbarView.php'; ?>

</body>
</html>
