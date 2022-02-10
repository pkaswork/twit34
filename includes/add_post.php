<?php 
include_once "functions.php";

if (!logged_in()) change_dir();

if (isset($_POST['text']) && !empty($_POST['text']) && isset($_POST['image'])) {
	if(!add_post($_POST['text'], $_POST['image'])) {
		$_SESSION['error'] = 'Что-то пошло не так';
	}
}

change_dir('user_posts.php');