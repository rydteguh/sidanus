<?php
	
	if(!defined('BASEPATH')) exit('no direct script access allowed');
	
	class topsellermodel extends CI_Model {
	
		function gettopseller()
		{
			$sql = $this->db->query("SELECT idToko , namaToko , userId FROM jumlahTransaksiToko order by jumlahPenjualan limit 3");
			return $sql->result_array();			
		}
		
		
	}
	
	  ?>