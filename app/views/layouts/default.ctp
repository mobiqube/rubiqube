<?php echo $html->docType();

 ?>
<html>
    <head>
        <?php echo $html->css('cake.generic');?>
        <title>Rubiqube Zip Extractor</title>
    </head>
    <body>
    
        <div class="container">
            <div>
                <?php if($session->check('Message.flash'))
                   {
               ?>
                  <?php  echo $session->flash();
                  
                    }
                  
                else if($session->check('Message.auth'))
                        {
                 ?>
				<div class="error" style="margin:5px 0px -20px 80px;">
               <?php  //echo $session->flash('auth');
               ?>
               
                                </div>
                <?php
                }
                ?>
                           
            </div>
   <?php echo $content_for_layout;
    ?></div>
    </body>
</html>