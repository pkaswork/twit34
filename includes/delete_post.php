<?php 
include_once "functions.php";

if (!logged_in()) change_dir('');

if (isset($_GET['id']) && !empty($_GET['id'])) {
	if(!delete_post($_GET['id'])) {
		$_SESSION['error'] = 'Что-то пошло не так';
	}
}

change_dir('user_posts.php');