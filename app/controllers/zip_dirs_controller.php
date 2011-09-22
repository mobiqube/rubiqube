<?php

class ZipDirsController extends AppController {

    var $name = 'ZipDirs';
    var $components = array('Auth', 'UploadFile');
    var $helpers = array('Html', 'Form', 'Javascript', 'Ajax');

    function index() {
        
    }

    function upload() {

        if (!empty($this->data)) {
            $result = $this->UploadFile->uploadFiles('zips', $this->data, $this->Auth->user('id'), 'ZipDir');
        }
        
        //debug($result);
            if($result['errors'])
                {
                
                //debug($result['errors']);
                foreach($result['errors'] as $error)
                    {
                    
                  $err.= $error .'<br/>';  
                  
                    }
                   $this->Session->setFlash($err);
                  $this->redirect('/users/login');
                 //exit;
                }
                elseif($result ==-1)
            {
            
             $this->Session->setFlash('Sorry, this file is not a \'.zip\' file!');
               $this->redirect('/users/login');
            }
else{
       
    $countUrl = count($result['url']);
        $countFiles = count($result['name']);
        $countTypes = count($result['filetype']);

        for ($fp = 0; $fp < $countUrl; $fp++) {
            $zipDet[$fp]['filepath'] = $result['url'][$fp];
            $zipDet[$fp]['user_id'] = $this->Auth->user('id');
        }

        for ($fp = 0; $fp < $countFiles; $fp++) {
            $zipDet[$fp]['filename'] = $result['name'][$fp];
        }
        for ($fp = 0; $fp < $countTypes; $fp++) {
            $zipDet[$fp]['filetype'] = $result['filetype'][$fp];
        }
        //debug($zipDet);
        if (!empty($zipDet)) {
            $this->ZipDir->create();
            if ($this->ZipDir->saveAll($zipDet)) {
                $this->Session->setFlash('Successfully uploaded compressed file');
                $dirId = $this->ZipDir->getLastInsertId();
                $this->redirect('/zip_dirs/unpack/' . $dirId);
            } else {
                $this->Session->setFlash('upload failed');
                // $this->redirect('/users/login');
            }
        }
    }
    }
    
    
    
    //Extract the zipped file
    function unpack($id=null) {
        //debug($id);
        $zipp = $this->ZipDir->find('first', array('conditions' => array('ZipDir.id' => $id)));
        
        $fullpath = WWW_ROOT. $zipp['ZipDir']['filepath']  . $zipp['ZipDir']['filename'];
        //$dirName=str_replace('.','_',$zipp['ZipDir']['filename
        //debug($fullpath);
        $dirName =  basename($zipp['ZipDir']['filename'],".zip");
        $dirName =  $dirName;
        $zipObj = new ZipArchive;
        $file = $zipObj->open($fullpath);

        if ($file == TRUE) {
            $extractTo = $zipp['ZipDir']['filepath'] . $dirName;
            $zipObj->extractTo($extractTo);

            $zipObj->close();

            App::import('File', 'Folder');
            $folder = new Folder($extractTo);
            $folders = $folder->find();
$p=0 ;
//debug($file);
               //debug($extractTo);
               // debug($folders);
   

 
                 
    
     
           $trim = $this->UploadFile->trimDir($extractTo,$extractTo);
           if($trim==1){
           
 
               foreach ($folders as $file) 
           
                {
                //debug($file);
                if(!is_dir($file))
                {
                   $extr[$p]['filename']=$file; 
                   $extr[$p]['filepath']=$extractTo;
                   $extr[$p]['zipfile']=$dirName.'.zip';
                   $extr[$p]['zip_dir_id']=$id;
                   $extr[$p]['user_id']=$this->Auth->user('id');
                   $p++;
                }
                 
//                else
//                    {
//                    $trim= $this->UploadFile->trimDir($file, $extractTo);
//                    $this->unpack($id);
//                    }
                
                     
                }
               // $p++;
                if($extr){
                    $this->ZipDir->Extract->create();
                if($this->ZipDir->Extract->saveAll($extr)){
                $this->Session->setFlash('Unzip Successfully');
                $this->redirect('/users/login');
                }
                else
                    {
                   $this->Session->setFlash('Unzip Failed');
                  
                 $this->redirect('/users/login');
                    }
            }
            else
                {
                $this->Session->setFlash('Unzipping Failed, Unable to read directory structure');
                
// $this->redirect('/users/login');
                }
        } else {
            $this->Session->setFlash('Failed to unzip');
           // $this->redirect('/users/login');
        }
    }

    }
    function beforeFilter() {
        // $this->pageTitle="Restricted Area";
        //$this->Auth->allow('logout','initDB','login','display');
        //$this->layout='pages';
        $this->Auth->allow('login', 'add','index','unpack','trimDir','upload');
        $this->Auth->autoRedirect = false;
    }

}

?>
