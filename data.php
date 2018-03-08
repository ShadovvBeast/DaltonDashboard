<?php
ini_set('display_errors',1); error_reporting(E_ALL);
include_once 'db.php';
/**
 * Created by PhpStorm.
 * User: blossom
 * Date: 06/03/2018
 * Time: 12:38
 */
$conn->query('DELETE FROM df_sensor_data WHERE timestamp < (NOW() - INTERVAL 120 MINUTE)');
$conn->query('SET SQL_BIG_SELECTS=1');
$stmt = $conn->prepare('SELECT l.sensor_id, l.sensor_name, l.sensor_type, l.value, l.timestamp
						 FROM df_sensor_data l 
						 LEFT JOIN df_sensor_data r ON l.sensor_id = r.sensor_id AND l.timestamp < r.timestamp 
						 WHERE r.timestamp IS NULL
						 ORDER BY l.sensor_id');

$stmt->execute();
echo json_encode($stmt->fetchAll());
