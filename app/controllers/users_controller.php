<?php
class UsersController extends AppController
{

    var $name='Users';
var $components=array('Auth');
    var $helpers= array('Html','Form','Javascript','Ajax');

  
function view($id=null)
    {
    if(!$id)
        {
        $this->Session->setFlash('Invalid User');
        $this->redirect(array('action'=>'index'));
        }
       $this->set('user',$this->User->read(null,$id));
    }
function add()
    {
Configure::write('debug',2);
//e($this->Auth->password('admin'));
    $this->autoRender=false;
        if(!empty($this->data))
                {
            $this->User->create();
            // $this->data['User']['lastlogin'] =date('Y-m-d:h:s:i');
              if($this->data['User']['pass'] === $this->data['User']['passwordV'])
                      {
               $this->data['User']['password'] =$this->Auth->password($this->data['User']['pass']);
              
              //$this->data['User']['username'] =$this->Auth->password($this->data['User']['pass']);;
             //debug(strtotime("now"));
            if($this->User->save($this->data))
                    {
                $this->Session->setFlash('New User added');
                $this->redirect('/');
                    }
                    else
                    {
                                      $this->Session->setFlash('Unable to  add user');
                                        $this->redirect('/');
                    }
                      }
                      else
                          {
                             $this->Session->setFlash('Password Mismatch');
                             $this->redirect('/');
 //debug($this->data);
                          }
                }
                 
    
     
    }
    function delete($id=null)
    {

         
        if(!empty($this->data))
  if(!$id)
            {
            $this->Session->setFlash('Invalid Id for User');
            //$this->redirect(array('action=>'index');
            
            }
            if($this->User->del($id))
                    {
$this->Session->setFlash('User removed');
$this->redirect(array('action'=>'index'));
                    }
             
    }
    
 function login()
 {
      $this->AutoRender=false;
      if(!$this->Auth->user() && empty($this->data))
              {
          $this->redirect('/');
              }
     Configure::write('debug',0);

     if(!empty($this->data['User']))
                             {

  		if($this->Auth->user('id'))
			{
                    $this->Session->setFlash('Welcome '.$this->Auth->user('username'));
			  $this->redirect(array('controller'=>'dashboards','action'=>'index'));
			}
                        
			 
                         

 }

 else
     {
     if($this->Auth->user('id') )
			{
			  $this->redirect(array('controller'=>'dashboards','action'=>'index'));
			}

			 
     }

 }

  function logout()
    {

         $this->Session->setFlash('Logout Successful');
          
         $this->redirect($this->Auth->logout());
    }

    function edit($id=null)
    {
         
        if(!$id && empty($this->data))
                {

            $this->Session->setFlash('Invalid User');
            $this->redirect(array('action'=>'index'));
                }
if(!empty($this->data))
        {
    //if no password is supplied, we dont change
    if(trim($this->data['User']['password'])==Security::hash('',null,true));
    {
        unset($this->data['User']['password']);
    }
        
if($this->User->save($this->data))
        {
    $this->Session->setFlash('User has been saved!');
    $this->redirect(array('action'=>'index'));
        }
        else
            {
            $this->Session->setFlash('User could not be saved, please try again later');

            }}
            if($id&&empty($this->data))
                    {
                $this->data=$this->User->read(null,$id);
                $this->data['User']['password']='';
                    }

                    //For the Parent Group

                     
     

    }

          function __checkAcl($aco_name=null,$action=null)
            {
                if($this->Auth->user())
                        {
                    $aco = new Aco();
                $aro = new Aro;
                $inflect=new Inflector();
                $aro_record_user= $aro->findByAlias($this->Auth->user('username'));
               // $grp='administrators/';

                    //Get the names from the Group Model
                    $groupName=$this->User->Group->findById($this->Auth->user('group_id'));
                    //debug($groupName);
                    $aro_record=$aro->findByAlias($groupName['Group']['name']);
                   // $grp='staffs/';

                     $aco =new Aco();
       $aco_record= $aco->find(array("Aco.model"=>$this->name,"Aco.alias"=>$aco_name));



if($this->Acl->check($aro_record['Aro']['alias'],$inflect->singularize($this->name).'/'.$aco_name,$action)||$this->Acl->check($aro_record_user['Aro']['alias'],$inflect->singularize($this->name).'/'.$aco_name,$action))
                {
    $value =1 ;
                }
                else
                    {
                   $value =0;
                    }
                    return $value;
                    
                    }
            }

            function security($id)
            {
                if($this->__checkAcl($this->action,'create')==1){

                    if(!empty($this->data))
                        {


                    //Let's get the Aro , i.e. the group
debug($this->data);
                    $aro_foreign_key=$this->data['User']['id'];
                    $username=$this->data['User']['username'];
                    $aro = new Aro();
                    $aro_record=$aro->findByAlias($username);
                    $aro_alias=$aro_record['Aro']['alias'];
                    $aco_of_aro=$aro_record['Aco'];

                    //Let's run through the security selection

                    $sec_access=$this->data['User']['SecurityAccess'];
                   
 //debug($sec_access);
                    $aco= new Aco();
                    $inflect=new Inflector();
                    foreach($sec_access as $aco_id =>$access_type)
                    {
                    $aco_record = $aco->findById($aco_id);
                    $model_plural=$inflect->pluralize($aco_record['Aco']['model']);

                    if($access_type=='allow')
                        {

                        $this->Acl->allow($aro_alias, $aco_record['Aco']['model'].'/'.$aco_record['Aco']['alias'],'*');
                        $this->Session->setFlash('Privilege added for user!');
                        //$this->redirect('/users/login');

                        }
                        else if($access_type='deny')
                            {
                            $this->Acl->deny($aro_alias,$aco_record['Aco']['model'].'/'.$aco_record['Aco']['alias'],'*');
                            $this->Session->setFlash('Access denied for user');
                          //  $this->redirect('/users/login');

                            }
                        
                    }
                        }
                    //Let's gather the Aco Selections available

                        $aco= new Aco();

                        // List the whole tree
                        $aco_tree=$aco->generateTreeList();

                        //Now get the details of the Aco records

                        $aco_records = $aco->find('all');
                            $aco->recursive=0;
                            $aco_groups=$aco->find('all',array('group'=>'model'));
                        $this->set(compact('aco_tree','aco_records','aco_groups'));

                        $this->set('current_alias',$this->User->name.':'.$this->User->id);
                        if(empty($this->data))
                        {

                            $this->data=$this->User->read(null,$id);
                        }
                }
                else
                    {
                                                        $this->Session->setFlash('Access denied');
                                                        $this->redirect('/users/login');
  
                    }
                        }

                        
function uProfile()
                        {
    if(!$this->Auth->user())
            {
        $this->Session->setFlash('You have to be logged  update your profile');
                                $this->redirect('/users/login');
            }

    else{
    if(!empty($this->data))

        {
    if($this->data['User']['id']!==$this->Auth->user('id'))
    {
        $this->Session->setFlash('You connot make changes to another account');
                                $this->redirect('/users/login');
    }
    //if no password is supplied, we dont change
    if(trim($this->data['User']['password'])==Security::hash('',null,true));
    {
        unset($this->data['User']['password']);
    }

if($this->User->save($this->data))
        {
    $this->Session->setFlash('You have updated your profile successfully!');
    $this->redirect(array('action'=>'settings',$this->data['User']['id']));
        }
        else
            {
            $this->Session->setFlash('Profile could not be updated, please try again later');
            $this->redirect('/users/settings');

            }}
                        }

                        }
                        
                        function settings($id=null)
                        {
                           $this->layout='pages';
                           if(!$this->Auth->user())
                                    {
                                $this->Session->setFlash('You have to be logged in to access user profile ');
                                $this->redirect('/users/login');
                                    }

                                    else if(!$id)
                                    {
                                $this->Session->setFlash('ooOpsie, No user selected!');
                                $this->redirect('/users/login');
                                    }
        if(($id) && ($this->Auth->user('id')==$id))
                                    {
$this->User->recursive=2;
$user = $this->User->read(null,$id);
$this->set('user',$user);
                                    }
                                    else if(($id) && ($this->Auth->user('id')!=$id))
                                    {
                              $this->Session->setFlash('You cannot access profile page for another account!');
                                $this->redirect('/users/login');
                                    }
                        }

                        //Password change action
                        function changePassword($id=null)
                        {
                            if(!$this->Auth->user())
                                    {
                                $this->Session->setFlash('You have to be logged in to change your password');
                                $this->redirect('/users/login');
                                    }

                                     



                                    if(!empty($this->data))
                    {
                   if($this->data['User']['password']==$this->data['User']['passwordVerify']&&($this->data['User']['id']==$this->Auth->user('id')))
                           {


                                if($this->User->updateAll(array('User.password'=>'\''.$this->Auth->password($this->data['User']['password']).'\''),array('User.id'=>$this->Auth->user('id'))))
        {$this->Session->setFlash('Password Successfully changed. Change will be effected when next you login');
        $this->redirect('/users/login');
        }
              else{


                   $this->Session->setFlash('Unable to change your password!');
                   $this->redirect('/users/settings');
                       }
                        }

                       else{


                   $this->Session->setFlash('ooOpsie, something does not fit right here...password have not changed!');
                      $this->redirect('/users/login'); }
                       }

                        }



                        function resetPassword()
                        {
                       
                        }
            
    function beforeFilter()
    {
      // $this->pageTitle="Restricted Area";
          //$this->Auth->allow('logout','initDB','login','display');
        //$this->layout='pages';
        $this->Auth->allow('login','add');
        $this->Auth->autoRedirect = false;
         $this->Auth->logoutRedirect = array('controller' => 'pages', 'action' => 'display');
       
    }
}
?>
