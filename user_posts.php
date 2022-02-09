<?php
include_once "includes/functions.php";
$error = get_error_message();

if (isset($_GET['id']) && !empty($_GET['id'])) {
	$id = $_GET['id'];
} else if (isset($_SESSION['user']['id']) && !empty($_SESSION['user']['id'])) {
	$id = $_SESSION['user']['id'];
} else {
	$id = 0;
}

$posts = get_posts($id);
$title = "Твиты пользователей(я)";
//debug($_SESSION, true);
if (!empty($posts))
	$title = "Твиты пользователей(я) @" . $posts[0]['login'];

include_once "includes/header.php";
include_once "includes/tweet_form.php";
include_once "includes/posts.php";
include_once "includes/footer.php";
?>
		
