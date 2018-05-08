<?php

class Signup_qwirk extends CI_Model
{
  
  function __construct()
  {
    parent::__construct();
  }
  

  /*FONCTION POUR ENVOYER A LA BO LES INFOS DE L INSCRIPTION */

  function signup($data)
  {
	   $this->db->insert('register', $data); 
  }

  /* ******************************************* */

      function get_id_register($username)
      {
        $query = $this->db->query('SELECT `id_register` FROM register WHERE `username`= "'.$username.'";');
        $row = $query->row_array();
        return $row;
      }
  /* */

  function isExist($target, $data)
  {
    $query_user = $this->db->query('SELECT '. $target.' FROM register WHERE `'.$target.'`="'.$data.'"');
    $row_user = $query_user->row_array();
    return ($row_user);
  }

  /* ******************************************* */

  /*FONCTION POUR CHECK SI EMAIL ET LE MDP EST CORRECT */

  function checkLogin($username,$pass)
  {
    $this->db->where('username',$username);
    $this->db->where('password',sha1($pass));
    $q = $this->db->get('register');
    if($q->num_rows()>0)
    {
      return true;
    }
    return 0;
  }

  /* ******************************************* */

  /*FONCTION POUR RECUPER L ID D'UN USER POUR PAS L'avoir sous forme de tableu => unset */

 function getUserByUsername($username)
  {
    $query = $this->db->query('SELECT id_register, email, firstname, lastname, username FROM register WHERE username = "'.$username.'"');
    $row = $query->row_array();
    return $row;
  }

  /* ******************************************* */

  function getUserById($id)
  {
    $sql = $this->db->select(array('id_register','firstname', 'lastname', 'username'))
                                ->where('id_register', $id)
                                ->get_compiled_select('register', FALSE);
  
    $data = $this->db->get()->result_array();
    return $data[0];
  }

  function changePassword($username, $mdp)
  {
    $this->db->set('password', sha1($mdp));
    $this->db->where('username', $username);
    $this->db->update('register');
  }

  function addFriend($obj, $id_friend)
  {
    $data = array(
        'id_user' => $obj->id,
        'id_friend' => $id_friend['id_register'],
        'show' => 0,
        'accept' => 0);

    $data_two = array(
        'id_user' => $id_friend['id_register'],
        'id_friend' => $obj->id,
        'show' => 0,
        'accept' => 1);

    $query = $this->db->query('SELECT `id_user`,`id_friend` FROM `friend` WHERE `id_user`= '.$obj->id.' AND `id_friend`= '.$id_friend['id_register'].'');
    $row = $query->row_array();
    if (!empty($row))
    {
      http_response_code(403);
      return null;
    }
    $this->db->insert('friend', $data);
    $this->db->insert('friend', $data_two);
  }

  /* DATA de l'ami ajouter */
  function getAddFriend($id_friend)
  {
    $sql = $this->db->select(array('id_register','firstname', 'lastname', 'username'))
                                ->where('id_register', $id_friend['id_register'])
                                ->get_compiled_select('register', FALSE);
  
    $data = $this->db->get()->result_array();
    return $data;
  }

  /* On récupère les amis qui n'ont pas encore accepter*/
  function getDemandsFriend($id_recepteur)
  {

    $sql = $this->db->select(array('id_user'))
                                ->where('accept', '0')
                                ->where('id_friend', $id_recepteur)
                                ->get_compiled_select('friend', FALSE);
    
    $data = $this->db->get()->result_array();
    return ($data);
  }

  function getUserId($id)
  {
    $sql = $this->db->select(array('id_register','firstname', 'lastname', 'username', 'id_chat'))
                                ->join('friend', 'friend.id_user = '.$id.'')
                                ->where('id_register', $id)
                                ->get_compiled_select('register', FALSE);
  
    $data = $this->db->get()->result_array();
    return $data[0];
  }

  function getUserIdGroup($id)
  {
    $sql = $this->db->select(array('id_register','firstname', 'lastname', 'username', 'id_chat'))
                                ->join('friend', 'friend.id_user = '.$id.'')
                                ->where('id_register', $id)
                                ->get_compiled_select('register', FALSE);
  
    $data = $this->db->get()->result_array();
    return $data;
  }

  function getUserMessage($id_friend, $id)
  {
     $sql = $this->db->select(array('id_register','firstname', 'lastname', 'username', 'id_chat'))
                                ->join('friend', 'friend.id_user = register.id_register')
                                ->where('id_friend', $id)
                                ->where('id_user', $id_friend)
                                ->get_compiled_select('register', FALSE);
    $data = $this->db->get()->result_array();
    return $data[0];
  }

  function deleteFriend($id_user, $id_target)
  {
    $this->db->where('id_user', $id_target);
    $this->db->where('id_friend', $id_user);
    $this->db->delete('friend');

    $this->db->where('id_user', $id_user);
    $this->db->where('id_friend', $id_target);
    $this->db->delete('friend');
  }

  function validateFriend($id_user, $id_target)
  {
    $this->db->set('accept', '2');
    $this->db->where('id_user', $id_target);
    $this->db->where('id_friend', $id_user);
    $this->db->update('friend');

    $this->db->set('accept', '2');
    $this->db->where('id_user', $id_user);
    $this->db->where('id_friend', $id_target);
    $this->db->update('friend');
    //print_r("id user => " . $id_user);
    //print_r("id_target => " . $id_target);
  }

  // On récupère les amis donc uniquement les 2
  function getAllIdFriends($id)
  {
    $sql = $this->db->select(array('id_friend'))
                                ->where('id_friend !=', $id)
                                ->where('id_user =', $id)
                                ->where('accept', 2)
                                ->get_compiled_select('friend', FALSE);
    $data = $this->db->get()->result_array();
    return ($data);
  }

  // On récupère uniquement les demandes donc tous les 1
  function getAllDemand($id)
  {
    $demandeAmis = $this->db->select(array('id_user'))
                                ->where('id_friend =', $id)
                                ->where('accept', 1)
                                ->get_compiled_select('friend', FALSE);
    $data = $this->db->get()->result_array();
    return ($data);
  }

  function createChat($id_one, $id_two)
  {

    /* J'ajoute un chat*/
    $data = array(
        'id_chat' => 1,
        'messages' => 'Tu peux envoyer un message');

    $this->db->insert('chat', $data);


    /* Je donne un id unique au chat */
    $last_id = $this->db->insert_id();

    $this->db->set('id_chat', $last_id);
    $this->db->where('id', $last_id);
    $this->db->update('chat');

    /* Je donne les droits d'accès a deux amis */

    $this->db->set('id_chat', $last_id);
    $this->db->where('id_user', $id_one);
    $this->db->where('id_friend', $id_two);
    $this->db->update('friend');

    $this->db->set('id_chat', $last_id);
    $this->db->where('id_user', $id_two);
    $this->db->where('id_friend', $id_one);
  
    $this->db->update('friend');
    return ($last_id);
  }

  function add_message($obj)
  {

    /* J'ajoute un chat*/
    $data = array(
        'id_chat' => $obj->id_chat,
        'id_user' => $obj->id_user,
        'messages' => $obj->message);

    $this->db->insert('chat', $data);
  }

  function getAllMessage($id)
  {
     $sql = $this->db->select(array('chat.messages', 'chat.id_user'))
                                ->where('id_chat', $id)
                                ->order_by("id", "asc")
                                ->get_compiled_select('chat', FALSE);
  
    $data = $this->db->get()->result_array();
    return ($data);
  }

  function checkAcceptFriend($username)
  {
    $sql = $this->db->select(array('friend.id_chat'))
                                ->where('accept', 2)
                                ->where('id_friend', $username)
                                ->get_compiled_select('friend', FALSE);  
    $data = $this->db->get()->result_array();
    return ($data[0]);
  }

  function createGroup($groupName)
  {
     $data = array(
        'name' => $groupName);

    $this->db->insert('groups', $data);
    $last_id = $this->db->insert_id();
    return ($last_id);
  }

  function createRelationship($id_group, $id, $is_admin)
  {
    $data = array(
        'id_group' => $id_group,
        'id_user' => $id,
        'is_admin' => $is_admin);

    $this->db->insert('grouprelationship', $data);
  }

  function messageGroup($id_group)
  {
     $data = array(
        'id_group' => $id_group,
        'id_user' => 0,
        'message' => "ça marche mon gars");
      $this->db->insert('groupmessage', $data);
  }

  function getAllGroups($id)
  {
    $allGroups = $this->db->select(array('id_group'))
                                ->where('id_user', $id)
                                ->get_compiled_select('grouprelationship', FALSE);
    $data = $this->db->get()->result_array();
    return ($data);
  }

  function getNamesGroups($id)
  {
    $allGroups = $this->db->select(array('groups.id', 'groups.name', 'COUNT("grouprelationship.id")'))
                                ->join('grouprelationship', 'grouprelationship.id_group = '.$id.'')
                                ->where('groups.id', $id)
                                ->get_compiled_select('groups', FALSE);
    $data = $this->db->get()->result_array();
    //SELECT COUNT(`id`) FROM grouprelationship WHERE `id_group` = 10
    return ($data);
  }

  function id_members($id)
  {
     $allGroups = $this->db->select(array('id_user'))
                                ->where('id_group', $id)
                                ->get_compiled_select('grouprelationship', FALSE);
    $data = $this->db->get()->result_array();
    return ($data);
  }

  function addUserGroup($id_group, $id_user)
  {
     $data = array(
        'id_group' => $id_group,
        'id_user' => $id_user,
        'is_admin' => 0);

    $this->db->insert('grouprelationship', $data); 
  }

  function checkIsOnTheGroup($id_user, $id_group)
  {
    $this->db->where('id_user',$id_user);
    $this->db->where('id_group',$id_group);
    $q = $this->db->get('grouprelationship');
    if($q->num_rows()>0)
    {
      return true;
    }
    return 0;
  }

  function addMessageGroup($id_group, $id_user, $message)
  {
    $data = array(
        'id_group' => $id_group,
        'id_user' => $id_user,
        'message' => $message);
    $this->db->insert('groupmessage', $data); 
  }

  function getAllMessagesGroup($id_group)
  {
      $getAllMessagesGroup = $this->db->select(array('message', 'id_user'))
                                ->where('id_group', $id_group)
                                ->get_compiled_select('groupmessage', FALSE);
    $data = $this->db->get()->result_array();
    return ($data);   
  }

  function giveAdmin($idTarget, $idGroup)
  {
    $this->db->set('is_admin', 1);
    $this->db->where('id_group', $idGroup);
    $this->db->where('id_user', $idTarget);
    $this->db->update('grouprelationship');
  }

  function isAdmin($id, $idGroup)
  {
    $this->db->where('id_user', $id);
    $this->db->where('id_group', $idGroup);
    $this->db->where('is_admin', 1);
    $q = $this->db->get('grouprelationship');
    if($q->num_rows()>0)
    {
      return true;
    }
    return 0;
  }

  function kickUser($idSup, $id_group)
  {
    $this->db->where('id_user', $idSup);
    $this->db->where('id_group', $id_group);
    $this->db->delete('grouprelationship');
  }

  function removeGroup($id_group)
  {
    $this->db->where('id', $id_group);
    $this->db->delete('groups');
  }

  function removeGrouprelationship($id_group)
  {
    $this->db->where_in('id_group', $id_group);
    $this->db->delete('grouprelationship');
  }

  function removeGroupmessage($id_group)
  {
    $this->db->where_in('id_group', $id_group);
    $this->db->delete('groupmessage');
  }

  function changeFirstname($newFirstname, $id)
  {
    $this->db->set('firstname', $newFirstname);
    $this->db->where('id_register', $id);
    $this->db->update('register');
  }

  function changeLastname($newLastname, $id)
  {
    $this->db->set('lastname', $newLastname);
    $this->db->where('id_register', $id);
    $this->db->update('register');
  }

  function changeUsername($newUsername, $id)
  {
    $this->db->set('username', $newUsername);
    $this->db->where('id_register', $id);
    $this->db->update('register');
  }

  function updatePassword($email, $newPassword)
  {
    $this->db->set('password', sha1($newPassword));
    $this->db->where('email', $email);
    $this->db->update('register');
  }
}
?>
