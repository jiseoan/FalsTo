<?php include 'env.php'; ?>
<?php include 'file.php'; ?>
<?php
function Backup($backup_dir, $web_dir, $db_dir, $db_ip, $db_port, $db_user, $db_pwd, $db_name) {
  date_default_timezone_set('Asia/Seoul');
  $cur = time();
  $dirname = $backup_dir."/".date("Ymd_His");
  $ok = false;
  
  if (mkdir($dirname)) {
    chdir($dirname);
    $zip = new ZipArchive();
    if ($zip->open('src.zip', ZIPARCHIVE::OVERWRITE) == true) {
      addDirectoryToZip($zip, $web_dir, $web_dir);
      $zip->close();
      
      chdir($db_dir);
      $result = passthru("mysqldump -P ".$db_port." -h ".$db_ip." -u".$db_user." -p".$db_pwd." --add-locks -f --databases ".$db_name." > ".$dirname."/".$db_name.".sql");
      $ok = true;
    }
    else {
      rmdir_rf($dirname);
    }
  }
  
  return $ok;
}
?>
