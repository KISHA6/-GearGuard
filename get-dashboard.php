<?php
require_once("../config/db.php");

$data = [];

// total equipment
$eq = mysqli_query($conn, "SELECT COUNT(*) as total FROM equipment");
$data['total_equipment'] = mysqli_fetch_assoc($eq)['total'];

// active equipment
$active = mysqli_query($conn, "SELECT COUNT(*) as total FROM equipment WHERE status='working'");
$data['active_equipment'] = mysqli_fetch_assoc($active)['total'];

// pending requests
$req = mysqli_query($conn, "SELECT COUNT(*) as total FROM maintenance_requests WHERE status='pending'");
$data['pending_requests'] = mysqli_fetch_assoc($req)['total'];

echo json_encode($data);
