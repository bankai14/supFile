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
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Yassine Zitouni
 * @license         MIT
 * @link            www.kallam.fr
 */
class Connexion extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model("registration");
    }

    public function index_post()
    {
        $user = array(
            'mail'=>$this->post("email"),
            'password'=>hash("sha256",$this->post("password")));


        $resp = $this->registration->connexion($user);

        if($resp['exist'] == true)
        {
            $this->set_response($resp, REST_Controller::HTTP_ACCEPTED);
        }
        else{
            $this->set_response($resp, REST_Controller::HTTP_UNAUTHORIZED);
        }

    }

    private function _key_exists($key)
    {
        return $this->rest->db
                ->where(config_item('rest_key_column'), $key)
                ->count_all_results(config_item('rest_keys_table')) > 0;
    }


}
