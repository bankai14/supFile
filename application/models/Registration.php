<?php

class Registration extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

  // Pour inscrire l'user dans la base
  function pushUser($user)
  {
      $this->db->insert('users', $user);
      $userId = $this->db->insert_id();
      return ($userId);
  }

    // Pour inscrire l'user dans la base
    function homeFolder($data)
    {
        $this->db->insert('folders', $data);
    }

  //dire que l'user est co
  function isConnected($userId, $connected)
  {
      $elements = array(
          'id_user'=>$userId,
          'connected'=>$connected);
      $this->db->insert('connected', $elements);
  }

  function connexion($user)
  {
      $this->db->where('mail',$user['mail']);
      $this->db->where('password', $user['password']);
      $q = $this->db->get('users');
      if($q->num_rows()>0)
      {
          $userInfo = $this->getInfoUser($user['mail']);
          $token = $this->getToken($userInfo['id_user']);
          $resp = array(
              'exist'=> true,
              'token' => $token['key'],
              'info'=> $userInfo);
          return $resp;
      }
      else{
          $resp = array(
              'exist'=> false);
          return $resp;
      }
  }

    function getInfoUser($mail)
    {
        $sql = $this->db->select(array('id_user','firstname', 'lastname', 'mail'))
            ->where('mail', $mail)
            ->get_compiled_select('users', FALSE);

        $data = $this->db->get()->result_array();
        return $data[0];
    }

    function getToken($id)
    {
        $sql = $this->db->select(array('key'))
            ->where('id_user', $id)
            ->get_compiled_select('keys', FALSE);

        $data = $this->db->get()->result_array();
        return $data[0];
    }
}
?>
