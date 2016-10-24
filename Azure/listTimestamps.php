<?php
// DB connection info
$host = "us-cdbr-azure-southcentral-f.cloudapp.net";
$user = "";
$pwd = "";
$db = "acsm_ff56b7c975fec78";

try {
  define('TIMEZONE', 'Europe/Paris');
  date_default_timezone_set(TIMEZONE);
  $now = new DateTime();
  $mins = $now->getOffset() / 60;
  $sgn = ($mins < 0 ? -1 : 1);
  $mins = abs($mins);
  $hrs = floor($mins / 60);
  $mins -= $hrs * 60;
  $offset = sprintf('%+d:%02d', $hrs*$sgn, $mins);

  $plantID = $_GET["plantID"];
  $conn = new PDO("mysql:host=$host;dbname=$db", $user, $pwd);
  $conn->exec("SET time_zone='$offset';");

  $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  $stmt = $conn->prepare("SELECT UNIX_TIMESTAMP(time_created) AS tid FROM notifications WHERE id=:plantid ORDER BY time_created DESC LIMIT 10");
  $stmt->bindValue(":plantid", $plantID);

  $retarray = array();

  if ($stmt->execute()) {
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $retarray[] = (object) [
        'tid' => (int) $row["tid"],
        'quant' => 10
      ];
    }
  }
  echo json_encode($retarray);
} catch(Exception $e){
  die(print_r($e));
}
?>
