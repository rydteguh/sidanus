<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class keranjangcontroller extends CI_Controller {

	public function __construct() {        
		parent::__construct();
	}
	
	
	public function tampilkankeranjang()
	{
		$temp = base_url();
		session_start();
		if(isset($_SESSION['sid']))
		{
			$this->load->model('keranjangpembelianmodel');
		
			$data['id'] = "keranjang";
			$data['keranjang'] = $this->keranjangpembelianmodel->showbarangkeranjang();
			$data['keranjang2'] = $this->keranjangpembelianmodel->gettotal();
			
			$this->load->view('keranjangUI', $data);
		}else{
			echo"<script>alert('keranjang anda kosong');window.location.href='$temp';</script>";
		}
		
	}
	
	public function ubahJumlahItem()
	{
		$this->keranjangpembelianmodel->ubahJumlahItemnya();
	}
	public function checkout()
	{
		
		$this->load->view('datapengiriman');
	}

	public function choosepaymentmethods()
	{
		
		$this->load->view('pilihpembayaran');
	}
	
	public function checkoutsuccess()
	{
		
		$this->load->view('struk');
	}

	public function keranjangnya()
	{
		$this->keranjangpembelianmodel->showbarangkeranjang();
	}
	
	public function deleteproduk()
	{
		$this->keranjangpembelianmodel->deleteproduknya();
	}
	
	public function konfirmasi()
	{
		$this->load->view('konfirmasi');;
	}
	
	function echoa()
	{
		session_start();
		$id = $_SESSION['sid'];
		return $id;
	}
	
	public function submit2()
	{
		$temp = base_url();
		session_start();
		if(isset($_SESSION['sid']))
		{
			$id = $_SESSION['sid'];
		
			
			$serverdb = "localhost";
			$usernamedb = "k6519841_ppl";
			$passworddb = "testing12345";
	
			$id_mysql = mysql_connect($serverdb ,$usernamedb,$passworddb);
			$database = mysql_select_db("k6519841_ppl",$id_mysql);
			
			$sql = "SELECT k.idPembelian,p.email FROM keranjangpembelian k,pengunjung p WHERE sid='$id' AND k.idPembelian=p.idPembelian";
			$hasil = mysql_query($sql);
			$baris = mysql_fetch_array($hasil);
			
			$temp = $baris[0];
			$temp2 = $baris[1];
			
			$this->load->model('datatransaksimodel');

			$data['data_transaksi2'] = $this->datatransaksimodel->getidpembelian2($temp);
			$data['data_transaksi'] = $this->datatransaksimodel->gettransaksi($temp,$temp2);
			
			$this->emailDetilPembayaran($data,$temp,$temp2);
						
			$this->load->view('struk', $data);
			session_destroy();
		
		}else
		{
			
			session_start();
			$id = $_SESSION['sid'];
			
			if(empty($id))
			{
				echo"<script>window.location.href='$temp';</script>";
			
			}
		
			
			$serverdb = "localhost";
			$usernamedb = "k6519841_ppl";
			$passworddb = "testing12345";
	
			$id_mysql = mysql_connect($serverdb ,$usernamedb,$passworddb);
			$database = mysql_select_db("k6519841_ppl",$id_mysql);
			
			$sql = "SELECT k.idPembelian,p.email FROM keranjangpembelian k,pengunjung p WHERE sid='$id' AND k.idPembelian=p.idPembelian";
			$hasil = mysql_query($sql);
			$baris = mysql_fetch_array($hasil);
			
			$temp = $baris[0];
			$temp2 = $baris[1];
			
			$this->load->model('datatransaksimodel');
			
			$data['data_transaksi2'] = $this->datatransaksimodel->getidpembelian2($temp);
			$data['data_transaksi'] = $this->datatransaksimodel->gettransaksi($temp,$temp2);
			
			$this->emailDetilPembayaran($data,$temp,$temp2);
			
			$this->load->view('struk', $data);
			session_destroy();
		
		}
	
	
	
	}
	public function emailDetilPembayaran($x, $t, $t2)
	{
		$total = 0;
		$detilpesanan = "";		
		foreach($x['data_transaksi'] as $transaksi){
			$detilpesanan .= $transaksi->namaProduk . "		" . $transaksi->jumlahItem . "		" . $transaksi->subtotal . "
			";
			$total = $total + $transaksi->subtotal;
		}
		
		$subject = 'Struk pembelian SiDanus'; 			$message = '

			Terimakasih telah melakukan pembelian di SiDanus. :)
			
			Nomor pesanan Anda: '.$t.'
			Detil pesanan:
			Nama Produk		Jumlah		Harga
			'.$detilpesanan.'
			Total = 				'.$total.'
			
			Silakan lakukan pembayaran terlebih dahulu ke salah satu dari rekening berikut:
			BNI	: 0344-270-793 a.n. Sidanus
			BCA	: 8000-453-979 a.n. Sidanus 
			Mandiri	: 9000-010-523-802 a.n. Sidanus
			
			Jangan lupa untuk melakukan konfirmasi pembayaran di halaman beranda SiDanus → Konfirmasi Pembayaran. Maksimal konfirmasi dilakukan 3 hari setelah melakukan pembelian.
			
			-Admin SiDanus-
			
			 
			';
		$from = 'From: SiDanus <noreply@SiDanus.com>';
		mail($t2,$subject,$message,$from);
	}
	
	public function v()
	{
		session_start();
		$temp = base_url();
		$bank = $_POST['bank'];
		$namarekening = $_POST['namarekening'];
		$email = $_SESSION['email'];
		$sql = "UPDATE pengunjung SET bank='$bank', atasNama='$namarekening' WHERE email='$email'";
		$result = mysql_query($sql);
		
		
		$this->ss();
	}
	
	public function submit()
	{
		
		if(!isset($_POST['email']) || !isset($_POST['nama']) || !isset($_POST['alamat']) || !isset($_POST['nohp']) || !isset($_POST['pilihanpengiriman']) ){
				echo "<script>
				alert('mohon lengkapi data pengiriman');
				window.location.href='checkout';
				</script>";
				die();
			}

			if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){ 
				echo "<script>
				alert('format email salah');
				window.location.href='checkout';
				</script>";
				die();
			}
			if (!is_numeric($_POST['nohp'])){
				echo "<script>
				alert('format nomor hp salah');
				window.location.href='checkout';
				</script>";
				die();
			}
		
		
		date_default_timezone_set('Asia/Jakarta');
		$waktu = date("[H:i:s a]",time());
		$waktulengkap = date("d:m:Y - H:i:s a",time());
		
		session_start();
		$id = $_SESSION['sid'];
		$email = $_POST['email'];
		$nama = $_POST['nama'];
		$alamat = $_POST['alamat'];
		$provinsi = $_POST['provinsi'];
		$kota = $_POST['kota'];
		$kecamatan = $_POST['kecamatan'];
		$pengiriman = $_POST['pilihanpengiriman'];
		
		$temp = base_url();
		
		$hp = $_POST['nohp'];
		
		$sql = "SELECT idPembelian from keranjangpembelian WHERE sid= '$id'";
		$result = mysql_query($sql);
		$baris = mysql_Fetch_array($result);
				  
		$hashnya= md5($baris[0]);
		
		$order = "insert into pengunjung (namaPembeli,email,noHP,alamat,kota,kecamatan,propinsi,idPembelian) values ('$nama','$email','$hp','$kota','$provinsi','$kecamatan','$alamat','$baris[0]')";
		$this->db->query($order);
		
		$order = "insert into transaksi (idPembelian,statusPesanan) values ('$baris[0]','belum selesai')";
		$this->db->query($order);
		
		$order = "insert into pembelian (idPembelian,tanggalPembelian,tanggalDikirim,pilihanPengiriman,metodeBayar,statusBayar,hashnya,komen) values ('$baris[0]','$waktulengkap','','$pengiriman','-','belum bayar','$hashnya','0')";
		$this->db->query($order);
		
		$_SESSION['email'] = $email;
		header("location:$temp/index.php/keranjangcontroller/choosepaymentmethods");
	}
}
	?>