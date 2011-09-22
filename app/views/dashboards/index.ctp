 
<div style="margin: 0 auto;">
  <div id="menu">
   <?php
   echo $this->element('menu');


            ?>
      <span style="margin-left: 15em;">
          
        <b><i>Hello : <?php echo $session->read('Auth.User.username');?></i></b><br/>
    
       
      </span>
  </div>

      
        
        <div class="zippedDiv" >

<?php

echo $form->create('ZipDir',array('action'=>'upload','type'=>'file'));

echo $form->file('ZippedFile');
        echo $form->submit('Upload');
        echo $form->end();
    ?>
             
</div>
    
     

    
</div>