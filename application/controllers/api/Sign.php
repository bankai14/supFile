<?php

defined('BASEPATH') OR exit('No direct script access allowed');

// This can be removed if you use __autoload() in config.php OR use Modular Extensions
/** @noinspection PhpIncludeInspection */
require APPPATH . '/libraries/REST_Controller.php';


// use namespace
use Restserver\Libraries\REST_Controller;

/**
 * This is an example of a few basic user interaction methods you could use
 * all done with a hardcoded array
 *
 * @category        Controller
 * @author          Yassine Zitouni
 * @license         MIT
 * @link            www.SupFile
 */

class Sign extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model("searchBarModel");
    }

    public function in_post()
    {
        $userConnexion = array(
            'mail'=>$this->post("email"),
            'password'=>hash("sha256",$this->post("password")));


    }

    public function up_post()
    {

    }

}
