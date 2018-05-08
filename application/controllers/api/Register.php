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
class Register extends REST_Controller {

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
            'firstname'=>$this->post("firstname"),
            'lastname'=>$this->post("lastname"),
            'mail'=>$this->post("mail"),
            'country'=>$this->post("country"),
            'phone'=>$this->post("phone"),
            'birth'=>$this->post("birth"),
            'sexe'=>$this->post("sexe"),
            'password'=>hash("sha256",$this->post("password")),
            'language'=>$this->post("language"));

        //$this->set_response($this->post($data), REST_Controller::HTTP_OK);
        /*Je récupère l'id de l'user et en meme temps j'ajoute en bd*/
        $userId = $this->registration->pushUser($user);
        /*J'ajoute en bd l'etat de connexion de l'utilisateur*/
        $this->registration->isConnected($userId, $this->post("connected"));
        /*Je créer une clée pour l'user*/
        $this->createUserKey($userId);

        mkdir(APPPATH . '/dataClients/' . (strval($userId)), 0700);
    }

    public function index_get()
    {
        $this->set_response("methode get", REST_Controller::HTTP_OK); // CREATED
    }

    public function createUserKey($userId)
    {
        // Build a new key
        $key = $this->_generate_key();

        // If no key level provided, provide a generic key
        $level = $this->put('level') ? $this->put('level') : 1;
        $ignore_limits = ctype_digit($this->put('ignore_limits')) ? (int) $this->put('ignore_limits') : 1;


        // Insert the new key
        if ($this->_insert_key($key, ['level' => $level, 'ignore_limits' => $ignore_limits], $userId))
        {
            $this->response([
                'status' => TRUE,
                'key' => $key
            ], REST_Controller::HTTP_CREATED); // CREATED (201) being the HTTP response code
        }
        else
        {
            $this->response([
                'status' => FALSE,
                'message' => 'Could not save the key'
            ], REST_Controller::HTTP_INTERNAL_SERVER_ERROR); // INTERNAL_SERVER_ERROR (500) being the HTTP response code
        }
    }

    /* Private Data Methods */

    private function _insert_key($key, $data, $userId)
    {
        $data[config_item('rest_key_column')] = $key;
        $data['date_created'] = function_exists('now') ? now() : time();
        $data['id_user'] = $userId;


        return $this->rest->db
            ->set($data)
            ->insert(config_item('rest_keys_table'));
    }

    private function _generate_key()
    {
        do
        {
            // Generate a random salt
            $salt = base_convert(bin2hex($this->security->get_random_bytes(64)), 16, 36);

            // If an error occurred, then fall back to the previous method
            if ($salt === FALSE)
            {
                $salt = hash('sha256', time() . mt_rand());
            }

            $new_key = substr($salt, 0, config_item('rest_key_length'));
        }
        while ($this->_key_exists($new_key));

        return $new_key;
    }

    private function _key_exists($key)
    {
        return $this->rest->db
                ->where(config_item('rest_key_column'), $key)
                ->count_all_results(config_item('rest_keys_table')) > 0;
    }


}
