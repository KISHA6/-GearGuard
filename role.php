<?php
// DO NOT REMOVE THIS FILE

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}
