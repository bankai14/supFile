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
 * @link            Supfile.fr
 */
class SupFile extends REST_Controller {

    private $filePath;

    function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->filePath = 'file:///C:/wamp64/www/supFile/application/dataClients/';
        $this->load->database();
        $this->load->model("SupFileModel");
        $this->load->helper(array('form', 'url', 'download'));
        $this->load->library('zip');
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

    public function test_post()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, OPTIONS");
        header("Access-Control-Allow-Headers: Content-Type, Content-Length, Accept-Encoding");

        if ( "OPTIONS" === $_SERVER['REQUEST_METHOD'] ) {
            die();
        }

        $data = $_FILES;

        $this->set_response($data, REST_Controller::HTTP_ACCEPTED);
    }

    /**
     * Créer un fichier
     *
     * @access public
     * @post path ( ex: 19/ ), locate = (home/), id_user (19)
     * @return void
     */

    //VERIFIER QUAND LE DOSSIER EXISTE DEJA
    public function createFile_post()
    {
        $path = $this->post("path");
        $id_user = $this->post("id_user");
        $locate = $this->post("locate");

        $data = $_FILES;


        $file = $data['userfile']['tmp_name'];

        if (!isset($file))
        {
            echo "Please select an image";
        }
        else
        {
            if(!is_dir(APPPATH . '/dataClients/' . $path))
            {
                print_r("lol");
                $this->set_response("Dossier existe pas", REST_Controller::HTTP_UNAUTHORIZED);
            }
            else {

                $id_folder = $this->SupFileModel->getIdDirectory($locate, $id_user);

                if ($id_folder != NULL) {
                    $code = $this->_generate_key();
                    $image = file_get_contents($data['userfile']['tmp_name']); // il fallait pas mettre de addslashes
                    $image_name = addslashes($data['userfile']['name']);
                    //$image_size = getimagesize($data['userfile']['tmp_name']);
                    $array = explode('.', $data['userfile']['name']); // pour trouver l'extension
                    $extension = end($array);

                    $exist = $this->SupFileModel->checkfileExist($array[0]);

                    if ($exist == true)
                    {
                        $this->response('Existe déja', REST_Controller::HTTP_UNAUTHORIZED);
                    }
                    /* NE PAS OUBLIER DE GERER LE FAIT D AVOIR LE MEME NOM */
                    $myfile = fopen("file:///C:/wamp64/www/supFile/application/dataClients/" . $id_user . "/files/" .
                        $code . '.' . $extension, "wb") or die("Unable to open file!");
                    $txt = $image;
                    fwrite($myfile, $txt);
                    fclose($myfile);

                    $data = array(
                        'id_folder' => $id_folder,
                        'name' => $array[0],
                        'link' => "http://localhost/supFile/application/dataClients/".$id_user."/files/" . $code .'.' . $extension,
                        'code' => $code,
                        'ext' => $extension);

                    $this->SupFileModel->addFile($data);

                    //$this->getDirectoryPath($this->post("path"));
                    //$link = "http://localhost/smartcart/assets/img/logo/" . $image_name;
                    //print_r("id_folder = " . $id_folder . " name = " . $array[0] . " path = " . $path . " ext = " . $extension);
                    $this->set_response("Fichier créer", REST_Controller::HTTP_ACCEPTED);
                }
                else{
                    print_r("lol");
                    $this->set_response("Dossier existe pas", REST_Controller::HTTP_UNAUTHORIZED);
                }
            }

        }

    }

    /**
     * Créer un dossier
     *
     * @access public
     * @post path ( ex: 19/yas/toto ), locate = (08okkgk4w480wocgwogc4wkcssgsocg0s8cg488o), id_user
     * @indication ( il faut pas de slash a la fin du path)
     * @return void
     */

    //VERIFIER QUAND LE DOSSIER EXISTE DEJA
    public function createFolder_post()
    {
        $path = $this->post("path");
        $locate = $this->post("locate");
        $id_user = $this->post("id_user");
        $pathLink = $this->_generate_key();
        $idLocate = $this->SupFileModel->getIdDirectory($locate, $id_user);
        if(!is_dir(APPPATH . '/dataClients/' . $path) && $idLocate != NULL) {
            mkdir(APPPATH . '/dataClients/' . $path, 0700);
            $nameFolder = $this->getNameFolderPath($path);
            $this->SupFileModel->addFolder($id_user, $nameFolder, $pathLink, $idLocate);

            $data = array(
                'status' => TRUE,
                'id_user' => $id_user,
                'nameFolder' => $nameFolder,
                'pathLink' => $pathLink,
                'idLocate' => $idLocate);

            $this->set_response($data, REST_Controller::HTTP_ACCEPTED);
        }
        else{
            $this->set_response("Dossier existe ou destination incorect", REST_Controller::HTTP_UNAUTHORIZED);
        }
    }

    /**
     * Récupération des dossiers dans le repertoire courant
     *
     * @access public
     * @post path ( 08okkgk4w480wocgwogc4wkcssgsocg0s8cg488o ) id_user (19)
     * @return void
     */

    public function getFolders_post()
    {
        $idFolder = $this->SupFileModel->getIdDirectory($this->post("path"), $this->post("id_user"));
        $folders = $this->SupFileModel->getFolders($this->post("id_user"), $idFolder);
        $this->set_response($folders, REST_Controller::HTTP_ACCEPTED);
    }

    /**
     * Récupération des fichiers dans le repertoire courant
     *
     * @access public
     * @post path ( 08okkgk4w480wocgwogc4wkcssgsocg0s8cg488o )
     * @return void
     */
    /* RECUPERE LES FICHIER EN FONCTION DU PATH */

    public function getFiles_post()
    {
        $locate = $this->post("path");
        $id_user = $this->post("id_user");

        $id_folder = $this->SupFileModel->getIdDirectory($locate,$id_user);

        $files = $this->SupFileModel->getFiles($id_folder);
        $this->set_response($files, REST_Controller::HTTP_ACCEPTED);
    }

    /**
     * Renomer un dossier
     *
     * @access private
     * @post locate ( 08okkgk4w480wocgwogc4wkcssgsocg0s8cg488o ), path(19/), rename(benzema), id_user (19)
     * @return void
     */

    public function renameFolder_post()
    {
        $locate = $this->post("locate");
        $path = $this->post("path");
        $rename = $this->post("rename");
        $id_user = $this->post("id_user");

        $exist = $this->SupFileModel->checkfolderExist($rename);


       if ($exist == true)
        {
            $this->response('Existe déja', REST_Controller::HTTP_UNAUTHORIZED);
        }
        else {
            $fileName = $this->SupFileModel->nameFolder($id_user, $locate);

            $root = getcwd() . DIRECTORY_SEPARATOR . "application\dataClients\\";

            rename($root . str_replace('/', '\\', $path) . $fileName[0]['name'],
                $root . str_replace('/', '\\', $path) . $rename);

            $request = $this->SupFileModel->renameFolder($locate, $rename);

            if ($request == true) {
                $this->response([
                    'status' => TRUE,
                    'message' => 'Dossier renommer'
                ], REST_Controller::HTTP_ACCEPTED);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Erreur'
                ], REST_Controller::HTTP_UNAUTHORIZED);
            }
        }
    }

    /**
     * Renomer un fichier
     *
     * @access private
     * @post locate ( g4kkwoko4o84w80404cgkcwgw8w48owgg8c808cs ), path(19/files/), rename(benzema)
     * @return void
     */

    public function renameFile_post()
    {
        $locate = $this->post("locate");
        $path = $this->post("path");
        $rename = $this->post("rename");

        $exist = $this->SupFileModel->checkfileExist($rename);


        if ($exist == true)
        {
            $this->response('Existe déja', REST_Controller::HTTP_UNAUTHORIZED);
        }
        else {
            $request = $this->SupFileModel->renameFile($locate, $rename);
            if ($request == true) {
                $this->response([
                    'status' => TRUE,
                    'message' => 'Fichier renomer'
                ], REST_Controller::HTTP_ACCEPTED);
            } else {
                $this->response([
                    'status' => FALSE,
                    'message' => 'Erreur'
                ], REST_Controller::HTTP_UNAUTHORIZED);
            }
        }
    }

    /**
     * Télécharger un fichier
     *
     * @access private
     * @post locate ( g4kkwoko4o84w80404cgkcwgw8w48owgg8c808cs ), path(19/files/)
     * @return void
     */

    public function downloadFile_post()
    {
        $path = $this->post("path");
        $locate = $this->post("locate");

        $extFile = $this->SupFileModel->getExt($locate);


        $fileDownload = getcwd().DIRECTORY_SEPARATOR . "application\dataClients\\" .
            str_replace('/','\\',$path) .
        $locate . '.' . $extFile;

        $link = $this->SupFileModel->getLinkImage($locate);

        //$data = force_download($fileDownload, NULL);
        $blob = file_get_contents($fileDownload);
       // $blob = base64_encode($blob);

        print_r($blob);
    }

    /**
     * Télécharger un dossier
     *
     * @access private
     * @post locate ( g4kkwoko4o84w80404cgkcwgw8w48owgg8c808cs ), path(19/files/)
     * @return void
     */

    public function downloadFolder_post()
    {
        $path = $this->post("path");
        $locate = $this->post("locate");

        $fileName = $this->SupFileModel->getFolders($locate);
        $id_folder = $this->SupFileModel->getIdDirectory($locate);

        $files = $this->SupFileModel->getFiles($id_folder);
        $folders = $this->SupFileModel->getFoldersOnFolder($id_folder);


        $data = array(
            'files' => $files,
            'folders' => $folders);

        //mkdir(APPPATH . '/dataClients/' . $path, 0700);

        foreach ($data["folders"] as $dir)
        {
            $this->generateDirectory($path, $dir["name"]);
        }

        var_dump($data);
        /*$fileDownload = getcwd().DIRECTORY_SEPARATOR . "application\dataClients\\" .
            str_replace('/','\\',$path) . 'files\\' .
            $locate . '.' . $extFile;

        force_download($fileDownload, NULL);*/
    }

    public function shareFolder_post()
    {

    }

    public function deleteFile_post()
    {
        $code = $this->post("code");
        $extFile = $this->SupFileModel->getExt($code);
        $this->SupFileModel->deleteFile($code);
        unlink(APPPATH . '/dataClients/' . $this->post("path") . '.' .$extFile);
        $this->response([
            'status' => TRUE,
            'message' => 'Fichier supprimer'
        ], REST_Controller::HTTP_ACCEPTED);
    }

    private function generateDirectory($path, $dir)
    {
        mkdir(APPPATH . '/dataClients/' . $path . "download/" .$dir, 0700);
    }


    /**
     * Récupération du nom du dossier à partir d'un chemin
     *
     * @access private
     * @indacation on parse les slash en récupérant le dernier
     * @return void
     */

    private function getNameFolderPath($path)
    {
        $array = explode('/', $path); // pour trouver l'extension
        $nameFolder = end($array);
        return($nameFolder);
    }



    /* Helper Methods */

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
