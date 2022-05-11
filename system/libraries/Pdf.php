<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
require_once dirname(__FILE__) . '/dompdf/autoload.inc.php';

use Dompdf\Dompdf;

class CI_Pdf extends Dompdf
{
 public function __construct()
 {
   parent::__construct();
 } 
}

?>