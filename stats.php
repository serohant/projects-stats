<?php 
date_default_timezone_set('Europe/Istanbul');
error_reporting(0);

function getDirContents($dir, &$results = array()) {
  global $filename;
  $files = scandir($dir);
  if(count($files) <= 1){
      echo "[ERROR] Please put statics.php into some files";
      die();
  }
  foreach ($files as $key => $value) {
      $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
      if (!is_dir($path)) {
          if($path != $dir.DIRECTORY_SEPARATOR.$filename){
            $ext = explode('.',$value)[1];
              if($ext == "php" || $ext == "css" || $ext == "scss"){
                $results[] = $path;
              }
          }
      } else if ($value != "." && $value != ".." && $value != ".git") {
          getDirContents($path, $results);
      }
  }
  return $results;
}
$path = __DIR__;
$filename = basename(__FILE__, ".php").".php";
getDirContents($path, $files);
foreach ($files as $key => $value) {
  $totalline += count(file($value));
  $tempctime = filectime($value);
  $tempmtime = filemtime($value);
  if($key == 0){
    $projectctime = $tempctime;
    $projectetime = $tempmtime;
  }else{
    if($tempctime < $projectctime){
      $projectctime = $tempctime;
    }
    if($tempmtime > $projectetime){
      $projectetime = $tempmtime;
    }
  }
}

$date1 = new DateTime(date('Y-m-d'));
$date2 = new DateTime(date('Y-m-d', $projectctime));
$interval = $date1->diff($date2);
$perDay = $totalline/$interval->d;

$project['creationDate'] = date('d.m.Y H:i:s', $projectctime);
$project['endDate'] = date('d.m.Y H:i:s', $projectetime);
$project['totalHour'] = $interval->d*24;
$project['totalLines'] = $totalline;
$project['linesPerDay'] = $perDay;

print_r($project);

?>
