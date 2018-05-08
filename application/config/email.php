<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$config['protocol'] = 'sendmail';
$config['mailpath'] = '/usr/sbin/sendmail';
$config['smtp_host'] = 'SSL0.OVH.NET';
$config['smtp_port'] = '587';
$config['smtp_user'] = 'email';
$config['smtp_pass'] = 'pass';
$config['mailtype'] = 'html';
$config['charset'] = 'utf-8';
$config['wordwrap'] = TRUE;
$config['newline'] = "\r\n";
$config['send_multipart'] = FALSE;

?>
