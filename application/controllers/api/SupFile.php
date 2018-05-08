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
class SupFile extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();
        $this->load->database();
        $this->load->model("registration");
        $this->load->helper(array('form', 'url'));
    }

    public function index_post()
    {
        $data = $_FILES;


        $file = $data['userfile']['tmp_name'];

        if (!isset($file))
        {
            echo "Please select an image";
        }
        else
        {
            $image = file_get_contents($data['userfile']['tmp_name']); // il fallait pas mettre de addslashes
            $image_name = addslashes($data['userfile']['name']);
            $image_size = getimagesize($data['userfile']['tmp_name']);
            $array = explode('.', $data['userfile']['name']); // pour trouver l'extension
            $extension = end($array);

            print_r($extension);
        }
    }


}
