<?php
include_once 'db.php';
/**
 * Created by PhpStorm.
 * User: blossom
 * Date: 06/03/2018
 * Time: 12:38
 */
$stmt = $conn->prepare('SELECT l.sensor_id, l.sensor_name, l.sensor_type, l.value, l.timestamp
						 FROM df_sensor_data l 
						 LEFT JOIN df_sensor_data r ON l.sensor_id = r.sensor_id AND l.timestamp < r.timestamp 
						 WHERE r.timestamp IS NULL');

$stmt->execute();
echo json_encode($stmt->fetchAll());
