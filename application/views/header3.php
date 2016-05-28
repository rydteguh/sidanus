<?php
	function showKategoriDrop()
  	{
  		include("connectdb.php");

	  
		$sql = "SELECT namaKategori FROM kategori";
	  	$hasil = mysql_query($sql);
	  	echo "<option value=\"all\">Semua Kategori</option>";
	  	while($baris = mysql_fetch_array($hasil))
	  	{
	  	 	echo "<option value=\"$baris[0]\">$baris[0]</option>";
	  	}
  	}
  	
  	function getcart()
	{
		if(isset($_SESSION['sid']))
		{
		
			$id = $_SESSION['sid'];
			$serverdb = "localhost";
			$usernamedb = "k6519841_ppl";
			$passworddb = "testing12345";
	
			$id_mysql = mysql_connect($serverdb ,$usernamedb,$passworddb);
			$database = mysql_select_db("k6519841_ppl",$id_mysql);
			
			$sql = "SELECT sum(jumlahItem) as total FROM keranjangpembelian WHERE stat='not fix' AND sid='$id'";
			$result = mysql_query($sql);
			$baris = mysql_fetch_array($result);
			if($baris[0] == null)
			{
				echo 0;
			}else
			{
				echo $baris[0];
			}
		}else
		{
			echo 0;
		}
		
		
		
	}
?>

<header id="header"><!--header-->
	<div class="navbar navbar-default navbar-fixed-top">
	  <div class="container">
	    <div class="navbar-header">
	      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	        <span class="icon-bar"></span>
	      </button>
	      <a class="navbar-brand" href="<?php echo base_url(); ?>"><img src="<?php echo base_url().'/assets/images/home/logo2.png'; ?>" alt="Beranda" height="50px"/></a>

	    </div>
	    <div class="navbar-collapse collapse" id="searchbar">
	     
	      <ul class="nav navbar-nav navbar-right">
	        <li>
	        	<a href="<?php echo base_url().'index.php/admincontroller/viewadmin'; ?>"">Admin</a>
	        </li>
	        <li>
	          <a href="<?php echo base_url().'index.php/akuncontroller/logout'; ?>">Logout</a>
			</li>
	      </ul>
	      <ul class="nav navbar-nav navbar-right">
	        <li>
	          	<a href="<?php echo base_url().'index.php/keranjangcontroller/tampilkankeranjang'; ?>">
	            	<span class="glyphicon glyphicon-shopping-cart" aria-hidden="true"></span>&ensp;<span class="badge" id="count"><?php getcart(); ?></span>		          
	          	</a>
	        </li>
	      </ul>
	     
	     
	     
	     <form class="navbar-form" method="post" action="<?php echo base_url().'index.php/produkcontroller/searchByKeyword'; ?>">
	        <div class="form-group" style="display:inline;">
	          <div class="input-group" style="display:table;">
	            
	            <input class="form-control" name="querynya" placeholder="Cari Barang..." autocomplete="off" autofocus="autofocus" type="text">
	            <span class="input-group-addon" style="width:25%;">
	              <select class="form-control" name="kategorinya" style="width:100%;">
	                <?php showKategoriDrop(); ?>
	              </select>
	            </span>
				
	            <span class="input-group-addon" style="width:1%;">
	              <button type="submit" class="btn btn-search form-control">
	                <span class="glyphicon glyphicon-search" aria-hidden="true"></span>
	              </button>
	            </span>
	          </div>
	        </div>
	      </form>

	    </div><!--/.nav-collapse -->
	  </div>
	</div>
</header>