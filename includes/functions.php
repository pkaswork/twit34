<?php
include_once "config.php";

function debug($var, $stop = false) {
	echo "<pre>";
	var_dump($var);
	echo "</pre>";
	if ($stop) die();
}

function get_url($page ='') {
	return HOST . "/$page";
}

function get_page_title($title = "") {
	if (!empty($title)) {
		return HOST . " - $title";
	} else {
		return HOST;
	}
}

function db() {
	try {
		return new PDO("mysql:host=". DB_HOST. ";dbname=". DB_NAME . ";charset=utf8", DB_USER, DB_PASS,
			[
				PDO::ATTR_EMULATE_PREPARES => false,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
				PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
			]
		);
	} catch (PDOException $e) {
		die($e->getMessage());
	}
}

function db_query($sql, $exec = false) {
	if (empty($sql)) return false;

	if ($exec) return db()->exec($exec);

	return db()->query($sql);
}

function get_posts($user_id = 0) {
	if ($user_id > 0) {
		return db_query("SELECT posts.*, users.name, users.login, users.avatar FROM `posts` JOIN `users` ON users.id = posts.user_id WHERE posts.user_id = $user_id;")->fetchAll();
	} else {
		return db_query("SELECT posts.*, users.name, users.login, users.avatar FROM `posts` JOIN `users` ON users.id = posts.user_id;")->fetchAll();
	}
}

function get_user_info($login){
	return db_query("SELECT * FROM `users` WHERE login = $login;")->fetch();
}

function add_user($login, $pass) {
	/* INSERT INTO `users` (`login`, `pass`, `name`, `avatar`) VALUES (NULL, 'qwe', 'qwe', 'qwe', 'images/no_avatar.png'); */
	$login = trim($login);
	$name = ucfirst($login);
	$password = password_hash($pass, PASSWORD_DEFAULT);
	return db_query("INSERT INTO `users` (`login`, `pass`, `name`) VALUES ('$login', '$password', '$name');", true);
}

function register_user($auth_data) {
	debug($auth_data , true);
	if (empty($auth_data) || !isset($auth_data['login']) || empty($auth_data['login'])) return false;

	$user = get_user_info($auth_data['login']);

	if (!empty($user)) {
		$_SESSION['error'] = 'Пользователь ' . $auth_data['login'] . ' уже существует';
		header("Location: " . get_url('register.php'));
	}

	if ($auth_data['pass'] !== $auth_data['pass2']) {
		$_SESSION['error'] = 'Пароли не совпадают ';
		header('Location: ' . get_url('register.php'));
		die;
	}

	if (add_user($auth_data['login'], $auth_data['pass'])) {
		header('Location: ' . get_url(''));
		die;
	}
}

function login($auth_data) {

}

function get_error_message()
{
	$error = '';
	if (!empty($_SESSION['error']) && isset($_SESSION['error'])) {
		$error = $_SESSION['error'];
		$_SESSION['error'] = '';
	}

	return $error;
}