<?php
 //ini_set('upload_max_filesize', '10024');
 set_time_limit(120);
class UploadFileComponent extends Object
{
    var $components=array('Session');
function uploadFiles($folder, $formdata, $itemId = null,$arrayName=null, $extras=array()) {
	// setup dir names absolute and relative
	$folder_url = WWW_ROOT.$folder;
	$rel_url =$folder;

	// create the folder if it does not exist
	if(!is_dir($folder_url)) {
		mkdir($folder_url);
	}

	// if itemId is set create an item folder
	if($itemId) {
		// set new absolute folder
		$folder_url = WWW_ROOT.$folder.'/'.$itemId;
		// set new relative folder
		$rel_url = $folder.'/'.$itemId;
		// create directory
		if(!is_dir($folder_url))
                    {
			mkdir($folder_url);
                    }
	}

	// list of permitted file types, this is only images but documents can be added
	$permitted = array(
               'application/octet-stream', 'application/zip');

	// loop through and deal with the files
        //debug($formdata[$arrayName]['name']);//this->data['Mail']['Attach']
	foreach($formdata[$arrayName] as $file)
            {
           // debug($file);
		// replace spaces with underscores
            $f = end(explode('.',$file['name']));
            if($f !== 'zip')
                {
               // $this->redirect('/users/login');
                $result= -1;
                
                break;
                }
                else
                    {
                    $file['type']='application/zip';
                    }
		$filename = str_replace(' ', '_', $file['name']); //.strtotime("now");
		// assume filetype is false
                
		$typeOK = false;
		// check filetype is ok
		foreach($permitted as $type)
                    {
                     //debug($file['type']);
			if($type == $file['type'])
                            {
                             
				$typeOK = true;
				break;
			   }

		    }

		// if file type ok upload the file
		if($typeOK)
                    {


			// switch based on error code
			switch($file['error']) {
				case 0:
					// check filename already exists
					if(!file_exists($folder_url.'/'.$filename)) {
						// create full filename
						$full_url = $folder_url.'/'.$filename;
						$url = $rel_url.'/'.$filename;
						// upload the file
						$success = move_uploaded_file($file['tmp_name'], $url);
					}
                                        else {
						// create unique filename and upload file
 						ini_set('date.timezone', 'Europe/London');
					$now = date('Y-m-d-His');
						$full_url = $folder_url.'/'.$now.$filename;
						$url = $rel_url.'/'.$now.$filename;
                                            $full_url = $folder_url.'/'.$now.$filename;
						//$url = $rel_url.'/'.$now.$filename;
                                                $filename=$now.$filename;
                                            //.$filename;
                                            //unlink($url);
 						$success = move_uploaded_file($file['tmp_name'], $url);
					}
					// if upload was successful
					if($success) {
						// save the url of the file

						$result['url'][] = $rel_url.'/';
                                                $result['name'][]=$filename;
                                                $result['filetype'][]=$file['type'];
                                                $result['itemId'][]=$itemId;
                                                // $result['description'][]=$description;
					}
                                        else
                                            {
						$result['errors'][] = "$filename was not uploaded. Please try again.";
					    }
					break;
				case 3:
					// an error occured
					$result['errors'][] = "Error uploading $filename. Please try again.";
                                    
					break;

                                    case 4:
                                        $result['errors'][] = "No file Selected";
                                        break;
                                case 1:
				case 2:
                                    case 7:
                                      case 8:  //// an error occured file is too large
					$result['errors'][] = "Error uploading $filename. File is too large Please try again.";

					break;
				default:
					// an error occured
					$result['errors'][] = "System error uploading $filename. Contact webmaster.";
				      break;
			}



		} 

                else {
			// unacceptable file type
			$result['errors'][] = "$filename cannot be uploaded. Acceptable file types is: .zip";
		}
	}
        if($extras)
            {

   foreach($extras as $key =>$v)
   {
  if(!empty($v)){$result['extras'][]=$v ;}
   }}

return $result;
}

function trimDir($dir=null,$extractTo=null)
    {
        $countedDirs=0;
         
        //debug(WWW_ROOT.'/'.$dir);
        //exit;
        $dir=WWW_ROOT.$dir.'\\';
          App::import('File', 'Folder');
        $folder = new Folder($dir);
        $folders = $folder->findRecursive();
      //debug($folders);
      
        foreach($folders as $f)
            {
            if(is_dir($f))
                {
                $this->trimDir($f,$extractTo);
                }
                else
                    {
                    $parentDirectory=WWW_ROOT.$extractTo;
                    $filename=basename($f);
                    $directoryToDel=pathinfo($f);
                    if(!file_exists($parentDirectory.DS.$filename))
                        
                   {
                        copy($f,$parentDirectory.DS.$filename);
                        unlink($f);
                        
                        }
                    }
                    
                    
            }
// closedir($dh);
        //rmdir($directoryToDel['dirname']);
          //  debug(dirname($directoryToDel['dirname']));
           
            $folder = new Folder($extractTo);
             //debug($folder->tree());
        $folders = $folder->tree();
      //  debug($folders);
            foreach($folders as $file)
                {
                $parentDirectory=str_replace('/','\\',$parentDirectory);
                $parentDirectory.='\\';
              //debug($parentDirectory);
                foreach($file as $D)
                {
                  //debug($D);  
                
                if(is_dir($D) && $D !==$parentDirectory)
                    {
                    $folder->delete($D);
                    }
                }}
         
          return 1;
        
    
    }
}
?>