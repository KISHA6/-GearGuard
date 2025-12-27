<?php
session_start();

$max = 5;
$window = 300;

$_SESSION['login_attempts'] ??= 0;
$_SESSION['last_attempt'] ??= time();

if (time() - $_SESSION['last_attempt'] > $window) {
    $_SESSION['login_attempts'] = 0;
}

if ($_SESSION['login_attempts'] >= $max) {
    die("Too many login attempts. Try again later.");
}

$_SESSION['login_attempts']++;
$_SESSION['last_attempt'] = time();
