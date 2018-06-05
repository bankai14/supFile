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

    // récupère les dossier qui se trouve dans un dossier
    function getFoldersOnFolder($id_folder)
    {
        $this->db->select(array('name', 'path'))
            ->where('locate', $id_folder)
            ->get_compiled_select('folders', FALSE);

        $data = $this->db->get()->result_array();
        return $data;
    }

    // récupère le nom du dossier grace au path
  function getFolders($id_user, $idFolder)
  {
      $this->db->select(array('name', 'path
      '))
          ->where('id_user', $id_user)
          ->where('locate', $idFolder)
          ->get_compiled_select('folders', FALSE);

      $data = $this->db->get()->result_array();
      return $data;
  }

    // récupère le nom du dossier grace au path
    function nameFolder($id_user, $path)
    {
        $this->db->select(array('name'))
            ->where('id_user', $id_user)
            ->where('path', $path)
            ->get_compiled_select('folders', FALSE);

        $data = $this->db->get()->result_array();
        return $data;
    }


    function getFiles($id_folder)
    {
        $this->db->select(array('name', 'code', 'ext', 'link', 'code'))
            ->where('id_folder', $id_folder)
            ->get_compiled_select('datafile', FALSE);

        $data = $this->db->get()->result_array();
        return $data;
    }

    function getFilesCode($code)
    {
        $this->db->select(array('name', 'ext', 'link'))
            ->where('code', $code)
            ->get_compiled_select('datafile', FALSE);

        $data = $this->db->get()->result_array();
        return $data[0]['name'] . '.' .$data[0]['ext'];
    }

    function getExt($code)
    {
        $this->db->select(array('ext'))
            ->where('code', $code)
            ->get_compiled_select('datafile', FALSE);

        $data = $this->db->get()->result_array();
        return $data[0]['ext'];
    }

  function getIdDirectory($locate, $id_user)
  {
      $request = $this->db->select(array('id_folder'))
          ->where('path', $locate)
          ->where('id_user', $id_user)
          ->get_compiled_select('folders', FALSE);

      $idLocate = $this->db->get()->result_array();

      if (empty($idLocate)) {
          return (null);
      }
      else{
          return $idLocate[0]['id_folder'];
      }

  }

  function renameFolder($locate, $rename)
  {
      $this->db->set('name', $rename);
      $this->db->where('path', $locate);
      $this->db->update('folders');

      if ($this->db->affected_rows() > 0)
          return TRUE;
      else
          return FALSE;
  }

    function renameFile($code, $rename)
    {
        $this->db->set('name', $rename);
        $this->db->where('code', $code);
        $this->db->update('datafile');

        if ($this->db->affected_rows() > 0)
            return TRUE;
        else
            return FALSE;
    }

    /*function userExist($id)
    {
        $this->db->where('username',$username);
        $this->db->where('password',sha1($pass));
        $q = $this->db->get('register');
        if($q->num_rows()>0)
        {
            return true;
        }
        return 0;
    }*/


}
?>
