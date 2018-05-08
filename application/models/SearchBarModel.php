<?php

class SearchBarModel extends CI_Model
{

  function __construct()
  {
    parent::__construct();
  }

 function getFriends($firstLetter)
 {
     $this->db->select(array('*'))
         ->where('firstname LIKE', $firstLetter.'%')
         ->or_where('lastname LIKE', $firstLetter.'%')
         ->get_compiled_select('users', FALSE);

     $data = $this->db->get()->result_array();
     return $data;
 }
}
?>
