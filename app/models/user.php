<?php
class User extends AppModel
{
    var $name= 'User';
    //var $hasOne=array();
    //var $hasMany=array();
     
    
  var $validate = array(
	  					'username'=>array(
							'emptiness'=>array(
                                                                'required'=>true,
							  	'rule'=> 'notEmpty',
							  	'allowEmpty' => false,
							  	'message'=> 'Please enter  a username'
							  	),
							'uniqueness'=>array(
								'rule'=>'isUnique',
								'message'=>'This username already exists'
								)
						  	),

'password'=>array(
							'emptiness'=>array(
                                                                'required'=>true,
							  	'rule'=> 'notEmpty',
							  	'allowEmpty' => false,
							  	'message'=> 'Please enter  a password'
							  	)),
                                                        
	  					 
'fullname'=>array(
'required'=>true,
    'rule'=>'notEmpty',
	  						'allowEmpty'=>false,
	  						'message'=>'You have not supplied  a value Firstname field'
	  						),
       


	  					);



}

?>
