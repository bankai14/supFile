<?php

class SupFileModel extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  // Pour inscrire l'user dans la base
  function addFolder($id_user,$name, $path)
  {
      $data = array(
          'id_user'=> $id_user,
          'name'=> $name,
          'path' => $path);

      $this->db->insert('folders', $data);
  }

  function getFolders($path)
  {
      $this->db->select(array('name'))
          ->where('path', $path)
          ->get_compiled_select('folders', FALSE);

      $data = $this->db->get()->result_array();
      return $data;
  }

}
?>
