<?php
function filesInDir($tdir)
{
  $files = Array();
  
	if ($dh = opendir($tdir)) {
		$in_files = Array();

		while ($a_file = readdir($dh)) {
			if ($a_file[0] != '.') {
				if (is_dir($tdir."/".$a_file)) {
					$in_files = filesInDir($tdir."/".$a_file);
					if (is_array($in_files)) {
            $files = array_merge ($files, $in_files);
          }
				} else {
					array_push ($files, $tdir."/".$a_file);
				}
			}
		}
		closedir ($dh); 
	}
  
  return $files ; 
}

function filenameCompare($a, $b) {
  return strcmp($a, $b);
}

function rmdir_rf($dirname) {
  if ($dirHandle = opendir($dirname)) {
    chdir($dirname);
    while ($file = readdir($dirHandle)) {
      if ($file == '.' || $file == '..') {
        continue;
      }
      if (is_dir($file)) {
        rmdir_rf($file);
      }
      else {
        unlink($file);
      }
    }
    chdir('..');
    rmdir($dirname);
    closedir($dirHandle);
  }
}

function addDirectoryToZip($zip, $dir, $base)
{
  $newFolder = str_replace($base, '', $dir);
  $zip->addEmptyDir($newFolder);
  foreach(glob($dir . '/*') as $file) {
    if(is_dir($file)) {
      $zip = addDirectoryToZip($zip, $file, $base);
    }
    else {
      $newFile = str_replace($base, '', $file);
      $zip->addFile($file, $newFile);
    }
  }
  return $zip;
}

function DirsInDir($tdir)
{
  $files = Array();
  $i = 0;
  
	if ($dh = opendir($tdir)) {

		while ($a_file = readdir($dh)) {
			if ($a_file[0] != '.') {
				if (is_dir($tdir."/".$a_file)) {
					$files[$i++] = $a_file;
				}
			}
		}
		closedir ($dh); 
	}
  
  return $files ; 
}

function uploadfileMove($prefix, $fname, $tmpname) {
  $dstfname = str_replace(' ', '_', $fname);
  $dstfname = str_replace('’', '_', $dstfname);
  $dstfname = str_replace('\'', '_', $dstfname);
  $dstfname = str_replace('#', '_', $dstfname);
  
  $dstpath = $prefix.$dstfname;
  $dstpath2 = iconv("utf-8", "euc-kr", $dstpath);
  
  for ($i = 2 ; file_exists($dstpath2) ; $i++) {
    $dstpath = $prefix.$i.$dstfname;
    $dstpath2 = iconv("utf-8", "euc-kr", $dstpath);
  }
  
  return (move_uploaded_file($tmpname, $dstpath2)) ? $dstpath : "";
}

function fileDelete($path) {
  $path2 = iconv("utf-8", "euc-kr", $path);
  unlink($path2);
}
?>
