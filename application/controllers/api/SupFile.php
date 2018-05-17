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
        $this->load->model("SupFileModel");
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

           // print_r($extension);

            /* NE PAS OUBLIER DE GERER LE FAIT D AVOIR LE MEME NOM */
            $myfile = fopen("file:///C:/wamp64/www/supFile/application/dataClients/20/". $image_name, "wb") or die("Unable to open file!");
            $txt = $image;
            fwrite($myfile, $txt);
            fclose($myfile);
            //$this->getDirectoryPath($this->post("path"));
            //$link = "http://localhost/smartcart/assets/img/logo/" . $image_name;
        }
    }

    //VERIFIER QUAND LE DOSSIER EXISTE DEJA
    public function createFolder_post()
    {
        $path = $this->post("path");
        if(!is_dir(APPPATH . '/dataClients/' . $path)) {
            mkdir(APPPATH . '/dataClients/' . $path, 0700);
            $nameFolder = $this->getNameFolderPath($path);
            $this->SupFileModel->addFolder($nameFolder, $path);
            $this->set_response("Dossier créer", REST_Controller::HTTP_ACCEPTED);
        }
        else{
            $this->set_response("Dossier existe", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /* RECUPERE LES DOSSIERS EN FONCTION DU PATH */

    public function getFolders_post()
    {
        //print_r($this->post("path"));
        $folders = $this->SupFileModel->getFolders($this->post("path"));
        print_r($folders);
    }

    private function getNameFolderPath($path)
    {
        $array = explode('/', $path); // pour trouver l'extension
        $nameFolder = end($array);
        return($nameFolder);
    }


}
