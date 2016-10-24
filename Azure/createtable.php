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

  $conn = new PDO( "mysql:host=$host;dbname=$db", $user, $pwd);
  $conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
  $conn->exec("SET time_zone='$offset';");
  $sql = "DROP TABLE notifications";
  $conn->query($sql);
  $sql = "CREATE TABLE notifications(
    id VARCHAR(30),
    time_created DATETIME)";
  $conn->query($sql);
} catch(Exception $e){
  die(print_r($e));
}
echo "Ok";
?>
