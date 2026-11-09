<?php 
  require'superuser/function.php';

  $paslon = query(" SELECT * FROM paslon ");

  // total penjualan

	$total = mysqli_query($conn, " SELECT COUNT(id_matapilih) FROM mata_pilih ");

	$sudah = mysqli_query($conn, " SELECT COUNT(skor_mata) FROM mata_pilih WHERE skor_mata LIKE '1' ");

	$belum = mysqli_query($conn, " SELECT COUNT(skor_mata) FROM mata_pilih WHERE skor_mata LIKE '0' ");
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>E-Vote SMKN 3 RL</title>
	<link href="superuser/assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="superuser/assets/css/css_jquery.dataTables.min.css" rel="stylesheet">
  <script src="superuser/assets/js/jquery-3.7.0.js"></script>
</head>
<body style="background: url(images/bg2.jpg);background-repeat: no-repeat;background-size: 100%;">

	<div class="container-fluid">
			<div class="text-center fw-bold mb-5 mt-3">
				<h1 style="font-weight: bold;">Daftar Perolehan Pemilihan Osis<br> SMK Negeri 3 Rejang Lebong <br>Tahun 2024</h1>
			</div>
		<div class="row" style="margin-left: 200px;">

			<?php foreach($paslon as $calon) : ?>
			<div class="col-lg-2">
				
				<div class="card text-center">
				  <img src="superuser/foto/<?= $calon["foto"]; ?>" class="card-img-top" alt="...">
				  <div class="card-body" style="height: 160px;">
				  	<h1 class="text-center"><span class="badge bg-primary"><?= $calon["skor"]; ?></span></h1>
				    <h3 class="card-title align-middle" ><?= $calon["nama_ketua"]; ?></h1>
				   	
				    
				  </div>
				</div>

			</div>

			<?php endforeach; ?>

		</div>
	</div>

	<div class="bg-danger" style="position: absolute;bottom: 0; width: 100%;">
		<footer class="text-white fw-bold" style="font-size: 25px;">
			<marquee>
			Daftar Pemilih : 
			<?php while($rowz = mysqli_fetch_array($total)) : ?>
			 <?= $rowz['COUNT(id_matapilih)']; ?>
			<?php endwhile; ?>
			Orang
			
			##

			Sudah Memilih : 
			<?php while($rowzz = mysqli_fetch_array($sudah)) : ?>
			 <?= $rowzz['COUNT(skor_mata)']; ?>
			<?php endwhile; ?>
			Orang

			##

			Belum Memilih : 
			<?php while($rowzzz = mysqli_fetch_array($belum)) : ?>
			 <?= $rowzzz['COUNT(skor_mata)']; ?>
			<?php endwhile; ?>
			Orang



		</marquee>
		</footer>
	</div>
	

  <script src="superuser/assets/js/bootstrap.bundle.min.js"></script>
  <script src="superuser/assets/js/core/popper.min.js"></script>
  <script src="superuser/assets/js/core/bootstrap.min.js"></script>

</body>
</html>