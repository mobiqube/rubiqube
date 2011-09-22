<?php
class DashboardsController extends AppController
{

    var $name='Dashboards';
var $components=array('Auth');
    var $helpers= array('Html','Form','Javascript','Ajax');
var $uses =array();
  
 
    function index()
    {
        
    }
 
  
          
    function beforeFilter()
    {
      // $this->pageTitle="Restricted Area";
          //$this->Auth->allow('logout','initDB','login','display');
        //$this->layout='pages';
        $this->Auth->allow('login','add');
        $this->Auth->autoRedirect = false;
    }
}
?>
