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
 * @link            http::smartbe
 */
class MeteoApi extends REST_Controller {

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        // Configure limits on our controller methods
        // Ensure you have created the 'limits' table and enabled 'limits' within application/config/rest.php
        $this->methods['users_get']['limit'] = 500; // 500 requests per hour per user/key
        $this->methods['users_post']['limit'] = 100; // 100 requests per hour per user/key
        $this->methods['users_delete']['limit'] = 50; // 50 requests per hour per user/key
        $this->load->database();
        $this->load->model("signup_qwirk");
    }

    public function index_post()
    {
        $lat = $this->post("lat");
        $long = $this->post("long");

        $geoloc = array('lat' => $lat,
                        'long' => $long);

        $geoloc = json_encode($geoloc);
        
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://localhost/smartbeApi/index.php/api/MeteoApi/weatherForecast");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $geoloc);
        curl_setopt($ch, CURLOPT_POST, 1);

        $headers = array();
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        //print_r($result);
        if (curl_errno($ch)) {
            $error =  'Error:' . curl_error($ch);
            $this->set_response($error, REST_Controller::HTTP_BAD_REQUEST); // CREATED
        }
        else{
            print_r($result);
        }
        curl_close ($ch);
    }

    public function weatherForecast_post()
    {
        $lat = $this->post("lat");
        $long = $this->post("long");

        $this->load->helper('url');

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, "http://api.openweathermap.org/data/2.5/forecast?lat=".$lat."&lon=".$long."&APPID=d5c41a0ac5565a5f9042778c0a29de48");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

        $headers = array();
        $headers[] = "Appid: d5c41a0ac5565a5f9042778c0a29de48";
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($ch);
        if (curl_errno($ch)) {
            $error =  'Error:' . curl_error($ch);
            $this->set_response($error, REST_Controller::HTTP_BAD_REQUEST);
        }
        else{
            //print_r(json_decode($result));
            $this->set_response($this->post($data), REST_Controller::HTTP_OK);
        }
        curl_close ($ch);
    }

    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function forgotPassword_post()
    {
        $email = $this->post("email");
        $newPassword = $this->generateRandomString();

        $this->signup_qwirk->updatePassword($email, $newPassword);

        $this->email->from("qwirk@supinfo.com");
        $this->email->to($email,'Changer mot de passe');
        $this->email->subject('Nouveau mot de passe');
        $this->email->message('<h4>Qwirk!</h4><p>Votre nouveau mot de passe: </p>' . $newPassword);
        $this->email->send();
        $this->set_response("Nouveau password", REST_Controller::HTTP_OK); // CREATED
    }

}
