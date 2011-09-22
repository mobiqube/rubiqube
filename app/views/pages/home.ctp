<div style="" class="login"><?php

echo $form->create('User',array('action'=>'login'));

?>
     
    <?php
echo $form->label('username');
echo $form->text('username');

echo $form->label('password');
echo $form->password('password');

 
echo $form->submit('login',array('div'=>false,'legend'=>'false'));
?>
    <?php
echo $form->end();

?></div>
<br/>
<div style="width:400px;"><?php

echo $form->create('User',array('action'=>'add'));

?>
    <fieldset><legend>Sign-up</legend>
    <?php
echo $form->label('fullname');
echo $form->text('fullname');
echo $form->label('username');
echo $form->text('username');

echo $form->label('password');
echo $form->password('pass');

echo $form->label('verify-password');
echo $form->password('passwordV');

echo $form->submit('sign-me-up',array('div'=>'false','legend'=>'false'));
?>
    </fieldset><?php
echo $form->end();

?></div>

