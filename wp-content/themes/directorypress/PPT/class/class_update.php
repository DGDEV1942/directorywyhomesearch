<?php

ini_set( 'display_errors', 1 );
error_reporting( E_ALL );

class PremiumPress_Update {
 

	function VALUE($val=""){
	
		// Update server path
		$updateserver = 'http://www.premiumpress.com/UPS/V7/';
	 
		switch($val){
		
		case "remotemysql": { return $updateserver . strtolower(constant('PREMIUMPRESS_SYSTEM')).'.mysql';} break;
		case "sourcearchive": { return $updateserver . 'updateme.php?t='.strtolower(constant('PREMIUMPRESS_SYSTEM'))."&v=".PREMIUMPRESS_VERSION."&l=".get_option("license_key")."&w=".get_site_url(); } break;	
		case "remotever": { return $updateserver . strtolower(constant('PREMIUMPRESS_SYSTEM')).'.ver';} break;	
		case "remotedesc": { return $updateserver . strtolower(constant('PREMIUMPRESS_SYSTEM')).'.desc';} break;
		case "remotedel": { return $updateserver . strtolower(constant('PREMIUMPRESS_SYSTEM')).'.del';} break;	
		case "targetarchive": { return TEMPLATEPATH.'/thumbs/'.strtolower(constant('PREMIUMPRESS_SYSTEM')).'_update.zip'; } break;		 
		case "sourcedir": { return TEMPLATEPATH.'/'; } break;	
		case "targetdir": { return get_option('imagestorage_path').'/PPTUPDATE'; } break;	
		case "updateperformpg": { return 'update.php';} break;	
		case "softwarename": { return constant('PREMIUMPRESS_SYSTEM'); } break;		
		
		}
		
	}



	function CHECK(){
 
		$canContinue=true;
	 
		// Get the remote update version		
		$response = wp_remote_get( $this->VALUE('remotever') );
		if( !is_wp_error( $response ) ) {
				$newversion = $response['body'];
		} else {
			$canContinue=false;
		}
	 
		// Get the local current version
		$oldversion = PREMIUMPRESS_VERSION;
		
		
		// Get the remote update description
		$response = wp_remote_get( $this->VALUE('remotedesc') );
		if( !is_wp_error( $response ) ) {
		
			$description = $response['body'];
		 
		} else {
			$canContinue=false;
		}
	 
		if($canContinue){

			// Replace the new line character
			$description = str_replace("\n", "\n<br />", $description);
		
			// Compare the versions
			$versioncheck = version_compare($newversion, $oldversion);
		
		}
		 
		// Check if the remote version is older than the local version
		if ($versioncheck == "1" && $canContinue) {
		
		update_option("ppt_new_theme_version",true);
		
		return '<fieldset style="background:#ffe1e1;"><legend><strong>Theme Updates</strong></legend>
		<img src="'.PPT_PATH.'/img/admin/update_no.png" align="middle" style="float:right; padding-left:10px;"> 			
		<p><b>New Update Available (Version '.$newversion.')</b></p>'.$description.' <br><p><a href="admin.php?page=updates&updateme=1" id="UPDATEME"><b>Click here to update now</b></a></p> <br><small>Please note this upgrade tool is still under beta testing and might not work on all hosting accounts due to file/folder permissions.</small></fieldset><br>
		
		
	 

<br>	
		
		';
		
		 
		}else{
		
		update_option("ppt_new_theme_version",false);
		
			return '<fieldset class="last" style="background:#ddffde;"><legend><strong>Theme Updates</strong></legend> 
			<img src="'.PPT_PATH.'/img/admin/update_yes.png" align="middle" style="float:right; padding-left:10px;"> 
			
			<p><b>Congratulations!</b></p> 
			You are currently running the latest version of ' . $this->VALUE('softwarename') . ', there are no new updates available at this time.</fieldset><br>';

			 
		}		
		
		 
		
 
 
	
	
	}
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	









function STARTUPDATE(){

 //die($this->VALUE('sourcearchive')."<--".$this->VALUE('targetarchive'));
	
	// Get the remote update version
	$response = wp_remote_get( $this->VALUE('remotever') );
	if( !is_wp_error( $response ) ) {
		$newversion = $response['body'];
	} else {
			$errors = error_get_last();
			print("ERROR: " . $errors['type'] . "<br />\n");
			print($errors['message']);
			die();
	}
	
	
	// Get the local current version
	$oldversion = PREMIUMPRESS_VERSION; 
	
	if($newversion != $oldversion){
	
		// Remove any old update archives
		@unlink($this->VALUE('targetarchive')); 
 
		// Get the update archive from remote server
		if(!copy($this->VALUE('sourcearchive'), $this->VALUE('targetarchive'))) {
				$errors = error_get_last();
				print("COPY ERROR: " . $errors['type'] . "<br />\n");
				print($errors['message']);
				die();
		}
		 
		// Call the unzip function
		$this->unzip($this->VALUE('targetarchive'));
		 


	}
	
	update_option("license_key","");
	

 	return '<fieldset class="last" style="background:#ddffde;"><legend><strong>Theme Updated Successfully</strong></legend> 
			<img src="'.PPT_PATH.'/img/admin/update_yes.png" align="middle" style="float:right; padding-left:10px;"> 
			
			<p><b>Congratulations!</b></p> 
			' . $this->VALUE('softwarename') . ' has been successfully updated from version '.$oldversion.' to version '.$newversion.' <br><br>
			
			<p>If you notice your version number below does not update AFTER <a href="admin.php?page=updates">clicking here</a> then it has not be possible to automatically change files on your hosting due to file/folder permissions, instead you can download the update below and manually replace the changed files yourself.</p>
			
			<p><a href="'.get_option('imagestorage_link').strtolower(constant('PREMIUMPRESS_SYSTEM')).'_update.zip" target="_blank">Click here to download the update files.</a></p>
			
			</fieldset><br>
			
<script language="javascript">
function redirect(){
   window.location = "'.admin_url().'admin.php?page=ppt_admin.php";
}
setTimeout(redirect, 2000);</script>';
 

}
















	
	
	











/****************************************/

// Copy all the files from the current dir and subdirs
// to the target dir as a roll back source
function smartCopy($source, $dest, $options=array('folderPermission'=>0755,'filePermission'=>0755)) 
    { 
        $result=false; 
        
        if (is_file($source)) { 
            if ($dest[strlen($dest)-1]=='/') { 
                if (!file_exists($dest)) { 
                    cmfcDirectory::makeAll($dest,$options['folderPermission'],true); 
                } 
                $__dest=$dest."/".basename($source); 
            } else { 
                $__dest=$dest; 
            } 
            $result=copy($source, $__dest); 
            chmod($__dest,$options['filePermission']); 
            
        } elseif(is_dir($source)) { 
            if ($dest[strlen($dest)-1]=='/') { 
                if ($source[strlen($source)-1]=='/') { 
                    //Copy only contents 
                } else { 
                    //Change parent itself and its contents 
                    $dest=$dest.basename($source); 
                    @mkdir($dest); 
                    chmod($dest,$options['filePermission']); 
                } 
            } else { 
                if ($source[strlen($source)-1]=='/') { 
                    //Copy parent directory with new name and all its content 
                    @mkdir($dest,$options['folderPermission']); 
                    chmod($dest,$options['filePermission']); 
                } else { 
                    //Copy parent directory with new name and all its content 
                    @mkdir($dest,$options['folderPermission']); 
                    chmod($dest,$options['filePermission']); 
                } 
            } 

            if ($source != $dest){
                $dirHandle=opendir($source); 
                while($file=readdir($dirHandle)) 
                { 
                if($file!="." && $file!=".." && $file!="PPTUPDATE") 
                    { 
                         if(!is_dir($source."/".$file)) { 
                            $__dest=$dest."/".$file; 
                        } else { 
                            $__dest=$dest."/".$file; 
                        } 
                        //echo "$source/$file ||| $__dest<br />"; 
                        $result=$this->smartCopy($source."/".$file, $__dest, $options); 
                    } 
                } 
                closedir($dirHandle); 
            }
        } else { 
            $result=false; 
        } 
        return $result; 
    }

 
 
// Extract the files from the update archive
function unzip($file,$folderPath=""){

 	$e='';
	
	if(strlen($folderPath) > 5){
			$uPath = $folderPath;
	}else{
			$uPath = TEMPLATEPATH."/";
	}	
	
	if (function_exists('zip_open')) {  

		$zip	=	zip_open($file); 	
		
		 	
		if (!$zip) { return "Unable to proccess file '{$file}'"; } 	 
		while($zip_entry = zip_read($zip)) { 		
		  	
			
		   $zdir	= $uPath.dirname(zip_entry_name($zip_entry));
		   $zname	= $uPath.zip_entry_name($zip_entry);
		   
		   if(!zip_entry_open($zip,$zip_entry,"r")) {$e.="Unable to proccess file '{$zname}'";continue;}
		   if(!is_dir($zdir)) $this->mkdirr($zdir,0777);       
		  // print "{$zdir} | {$zname} <br />\n";
	
		   $zip_fs=zip_entry_filesize($zip_entry);
		   if(empty($zip_fs)) continue;
	
		   $zz=zip_entry_read($zip_entry,$zip_fs);
 
		   $z=fopen($zname,"w");
		   if(!$z){
		   echo '<div class="msg msg-error"><p><b>File Update Failed</b> '.$zname.'</p><br> <center><b>Permission Denied - Manual Update Required</b></center></div><br />';
		   }else{
		   fwrite($z,$zz);
		   fclose($z);
		   }
 
		   zip_entry_close($zip_entry);
	
		} 
		zip_close($zip);
	
	
	}else{
	
		include(TEMPLATEPATH."/PPT/class/class_pclzip.php");  
		$zip = new PclZip($file);
		
		
		if ($zip->extract(PCLZIP_OPT_PATH, $uPath) == 0) {
		die("Error : ".$zip->errorInfo(true)); 	
		} 
	
	}
	

    return($e);
} 

function mkdirr($pn,$mode=null) {
 

  if(is_dir($pn)||empty($pn)) return true;
  $pn=str_replace(array('/', ''),DIRECTORY_SEPARATOR,$pn);

  if(is_file($pn)) {trigger_error('mkdirr() File exists', E_USER_WARNING);return false;}

  $next_pathname=substr($pn,0,strrpos($pn,DIRECTORY_SEPARATOR));
  if($this->mkdirr($next_pathname,$mode)) {if(!file_exists($pn)) {return mkdir($pn,$mode);} }
  return false;
}

// Delete all the files in a target dir
// and remove the target dir\
function delete_directory($dir) { 
        if (is_dir($dir)) { 
                $objects = scandir($dir); 
                foreach ($objects as $object) { 
                        if ($object != "." && $object != "..") { 
                                if (filetype($dir."/".$object) == "dir") {
                                        delete_directory($dir."/".$object); 
                                } else {
                                        unlink($dir."/".$object); 
                                }
                        } 
                } 
                reset($objects); 
                @rmdir($dir); 
        } else {
                return true;
        }
}













 


}

?>