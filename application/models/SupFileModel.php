<?php

class SupFileModel extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  // Pour inscrire l'user dans la base
  function addFolder($id_user,$name, $path, $locate)
  {
      $data = array(
          'id_user'=> $id_user,
          'name'=> $name,
          'path'=> $path,
          'locate' => $locate);

      $this->db->insert('folders', $data);
  }

    function addFile($data)
    {
        $this->db->insert('datafile', $data);
    }

  function getFolders($path)
  {
      $this->db->select(array('name'))
          ->where('path', $path)
          ->get_compiled_select('folders', FALSE);

      $data = $this->db->get()->result_array();
      return $data;
  }

  function getIdDirectory($locate)
  {
      $this->db->select(array('id_folder'))
          ->where('path', $locate)
          ->get_compiled_select('folders', FALSE);

      $idLocate = $this->db->get()->result_array();
      return $idLocate[0]['id_folder'];
  }


}
?>
