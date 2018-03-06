<?php
$servername = "daltonafcom.ipagemysql.com";
$username = "6C352n50Rc3Ye1l";
$password = "QDQNf6UcU5vCDAtu";
$dbname = "ss_dbname_h2b5gkgl7n";
$conn = null;
try {
	$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
	// set the PDO error mode to exception
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e)
{
	echo "Error: " . $e->getMessage();
}
//$conn = null;
