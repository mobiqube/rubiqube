<?php
class ZipDir extends AppModel
{
    var $name= 'ZipDir';
    //var $hasOne=array();
    var $hasMany=array('Extract'=>array('dependent'=>true));
     
    
   
}

?>
