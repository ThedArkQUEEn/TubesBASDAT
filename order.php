<?php
session_start();
require_once "database.php";
$pdo = new database();

// Jika user belum login dan membuka halaman order, maka langsung diarahkan ke halaman login
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

// Jika admin login, maka langsung diarahkan ke halaman dashboard admin
if ($_SESSION['email'] == 'admin@laundryonlinemks.com') {
    header('Location: admin_dash.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Mencegah data pesanan kosong
    if (empty($_POST['jenis_laundry']) || empty($_POST['tanggalPengambilan']) || 
        empty($_POST['alamat']) || empty($_POST['lat']) || empty($_POST['lng'])) {
        $message = "Harap Mengisi Semua Data!";
        echo "<script type='text/javascript'>alert('$message');</script>";
    } else {
        // Menampilkan pesan dan kembali ke halaman dashboard pengguna
        echo "<script>alert('Pesanan Berhasil Ditambahkan!, Silahkan Kembali Ke Halaman Sebelumnya'); window.location.href='index.php'; </script>";

        $status = "Tunggu Konfirmasi";
        // Memasukkan data pesanan ke database
        $pdo->tambah_pesanan($_POST['jenis_laundry'], $_POST['PilihanHari'], $_POST['beratBarang'], $_POST['jumlahBarang'], $_POST['tanggalPengambilan'], $_POST['tanggalPengantaran'], 
        $_POST['alamat'], $_POST['catatan'], $_POST['lat'], $_POST['lng'], $_POST['hargaTotal'], $status, $_SESSION['id'], $_POST['list_satuan']);
    }
}
?>

<!DOCTYPE html>
<html lang="id">
	<head>
		<title>Pemesanan Laundry</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
		<!-- DataTables CSS -->
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
		<link rel="stylesheet" type="text/css" href="css/order.css"/>
		<link rel="stylesheet" type="text/css" href="css/roboto-font.css">
		<link rel="stylesheet" type="text/css" href="fonts/material-design-iconic-font/css/material-design-iconic-font.min.css">
		<script src="js/jquery-3.3.1.min.js"></script>
		<script src="js/jquery.steps.js"></script>
		<script src="js/jquery-ui.min.js"></script>
		<script src="js/order.js"></script>
		<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKtmUDqFDJ8-D3F0nJM4bpiD4hAR-fzeo"></script>
		<style>
        	@keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
			}

			.fadeIn {
					animation-name: fadeIn;
					animation-duration: 1.5s;
			}

			.harga-total {
				size: 100%;
				margin-top: 10px;
			}

			.hidden {
				display: none;
			}
		</style>
	</head>
	<body class="fadeIn">
	<nav id="navbar-user" class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand btn btn-outline-success active" href="dashboard.php">Beranda</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            </ul>
        </div>
    </nav>
		<div class="page-content" style="background-image: url('images/laundry2.jpg')">
			<div class="pemesanan">
				<div class="order-form">
					<div class="order-header">
						<h3 class="heading">Laundry Online</h3>
						<p>Harap Mengisi Semua Data Yang Dibutuhkan</p>
					</div>
					<form class="form-order" action="" method="post" name="form-order" id="form-order">
						<h2>
							<span class="step-icon"><i class="zmdi zmdi-shopping-cart"></i></span>
							<span class="step-text">Pemesanan</span>
						</h2>
						<section>
							<div class="inner">
								<h3>Silahkan Isi Form Pemesanan Anda</h3>
								<div class="form-group" id="radio">
									<label>Pilih Jenis Laundry :</label>
									<label>
										<input type="radio" class="jenis_laundry" name="jenis_laundry" id="kiloanCheck" value="Kiloan" checked>
									</label> Kiloan
									<label>
										<input type="radio" class="jenis_laundry" name="jenis_laundry" id="satuanCheck" value="Satuan" >
									</label> Satuan
								</div>
								<div class="form-row" id="kiloan_checked" name="kiloan_checked">
									<div class="form-holder form-holder-1">
										<h5> Deskripsi Jasa:</h5> 
										<ol>
											<li>Pilih berapa hari diperlukan untuk proses laundry.</li>
											<li>Berat untuk pakaian atau barang akan ditimbang oleh Pegawai/Admin.</li>
											<li>Jenis barang yang dilaundry seperti jaket, jas, tas, sajadah, bantal/guling, sprei single, selimut, bed cover dan keset akan dihitung dalam satuan</li>
											<li>Total biaya per kg akan diinfokan ke status pelanggan setelah dikonfirmasi.</li>
										</ol>
										<label class="form-row-inner">Pilih Waktu Laundry:
											<select id="jumlahHari" name="PilihanHari">
												<option value="6 Jam (Rp 15000/kg) + Free Setrika">6 Jam (Rp 15000/kg) + Free Setrika</option>
												<option value="1 Hari (Rp 9000/kg) + Free Setrika">1 Hari (Rp 9000/kg) + Free Setrika</option>
												<option value="2 Hari (Rp 7500/kg) + Free Setrika">2 Hari (Rp 7500/kg) + Free Setrika</option>
												<option value="3 Hari (Rp 6000/kg) + Free Setrika">3 Hari (Rp 6000/kg) + Free Setrika</option>
												<!-- Tambahkan opsi lain jika diperlukan -->
											</select>
										</label>
										<input type="button" class="btn btn-primary btn-block" onClick="updatePilihanWaktuKiloan()" Value="Submit" />
									
									</div>
									
								</div>
								<label class="form-row-inner">Pilihan Waktu Laundry: <span id="pilihanWaktuKiloan"></span></label>
								<div class="form-row" id="satuan_checked" name="satuan_checked" style="display:none">
									<div class="form-holder form-holder-2">
										<h5> Deskripsi Jasa:</h5> 
										<ol>
											<li>Perhitungan biaya berdasarkan satuan material yang di laundry</li>
											<li>Pilihan laundry hanya untuk 2 hari</li>
											<li>Free setrika</li>
										</ol>
									</div>
									<div class="form-group" id="checkbox">
										<table class="table">
										<tbody>
											<tr>
											<td>
												<label>Kaos/T-Shirt - Rp. 500</label>
												<input type="number" class="item-qty" data-price="500" value="0" min="0">
											</td>
											<td>
												<label>Kemeja - Rp. 800</label>
												<input type="number" class="item-qty" data-price="800" value="0" min="0">
											</td>
											<td>
												<label>Kemeja Batik - Rp. 800</label>
												<input type="number" class="item-qty" data-price="800" value="0" min="0">
											</td>
											</tr>
											<tr>
											<td>
												<label>Baju Muslim - Rp. 700</label>
												<input type="number" class="item-qty" data-price="700" value="0" min="0">
											</td>
											<td>
												<label>Kebaya - Rp. 2000</label>
												<input type="number" class="item-qty" data-price="2000" value="0" min="0">
											</td>
											<td>
												<label>Gaun Panjang - Rp. 3500</label>
												<input type="number" class="item-qty" data-price="3500" value="0" min="0">
											</td>
										</tr>
										<tr>
											<td>
												<label>Rok - Rp. 1500</label>
												<input type="number" class="item-qty" data-price="1500" value="0" min="0">
											</td>
											<td>
												<label>Sweater - Rp. 2500</label>
												<input type="number" class="item-qty" data-price="2500" value="0" min="0">
											</td>
											<td>
												<label>Jaket - Rp. 4000</label>
												<input type="number" class="item-qty" data-price="4000" value="0" min="0">
											</td>
										</tr>
										<tr>
											<td>
												<label>Jas - Rp. 3000</label>
												<input type="number" class="item-qty" data-price="3000" value="0" min="0">
											</td>
											<td>
												<label>Celana Pendek - Rp. 1000</label>
												<input type="number" class="item-qty" data-price="1000" value="0" min="0">
											</td>
											<td>
												<label>Celana Panjang - Rp. 1200</label>
												<input type="number" class="item-qty" data-price="1200" value="0" min="0">
											</td>
										</tr>
										<tr>
											<td>
												<label>Sarung - Rp. 1000</label>
												<input type="number" class="item-qty" data-price="1000" value="0" min="0">
											</td>
											<td>
												<label>Tas\Ransel - Rp. 4000</label>
												<input type="number" class="item-qty" data-price="4000" value="0" min="0">
											</td>
											<td>
												<label>Selendang/Kerudung - Rp. 900</label>
												<input type="number" class="item-qty" data-price="900" value="0" min="0">
											</td>
										</tr>
										<tr>
											<td>
												<label>Blouse - Rp. 1000</label>
												<input type="number" class="item-qty" data-price="1000" value="0" min="0">
											</td>
											<td>
												<label>Mukena - Rp. 2000</label>
												<input type="number" class="item-qty" data-price="2000" value="0" min="0">
											</td>
											<td>
												<label>Sajadah - Rp. 2500</label>
												<input type="number" class="item-qty" data-price="2500" value="0" min="0">
											</td>
										</tr>
										<tr>
											<td>
												<label>Topi - Rp. 1000</label>
												<input type="number" class="item-qty" data-price="1000" value="0" min="0">
											</td>
											<td>
												<label>Handuk Mandi - Rp. 1500</label>
												<input type="number" class="item-qty" data-price="1500" value="0" min="0">
											</td>
											<td>
												<label>Bantal/Guling - Rp. 2000</label>
												<input type="number" class="item-qty" data-price="2000" value="0" min="0">
											</td>
										</tr>
										<tr>
											<td>
												<label>Sarung Bantal/Guling - Rp. 850</label>
												<input type="number" class="item-qty" data-price="850" value="0" min="0">
											</td>
											<td>
												<label>Sprei Single - Rp. 3000</label>
												<input type="number" class="item-qty" data-price="3000" value="0" min="0">
											</td>
											<td>
												<label>Selimut - Rp. 5000</label>
												<input type="number" class="item-qty" data-price="5000" value="0" min="0">
											</td>
										</tr>
										<tr>
											<td>
												<label>Bed Cover - Rp.7000</label>
												<input type="number" class="item-qty" data-price="7000" value="0" min="0">
											</td>
											<td>
												<label>Keset - Rp. 3000</label>
												<input type="number" class="item-qty" data-price="3000" value="0" min="0">
											</td>
											</tr>
										<tbody>
										</table><br>
										<label>Jumlah Barang: <input type="text" id="jumlahBarang" name="jumlahBarang" class="jumlahBarang font-weight-bold" value="0" readonly="readonly"/></label>
										<label>Harga Total Rp <input type="text" id="hargaTotal" name="hargaTotal" class="jumlahBarang font-weight-bold" value="0" readonly="readonly" /></label>
									</div>
									<input type="button" class="btn btn-primary btn-block" onClick="calculateTotal()" Value="Cek Harga" />
								</div>
								<div>
									<p class="harga-total" data-toggle-group="duplication">
									</p>
								</div>
								<div class="form-row">
									<div class="form-holder form-holder-1">
										<label class="form-row-inner" for="tanggalPengambilan">Pilih Tanggal Pengambilan :</label>
										<div class="form-holder form-holder-1">
											<input type="date" id="tanggalPengambilan" name="tanggalPengambilan" required>
										</div>
									</div>
								</div>
								<div class="form-row">
									<div class="form-holder form-holder-2">
										<label class="form-row-inner">
											<input type="text" class="form-control" id="catatan" name="catatan">
											<span class="label">Tambahkan Catatan</span>
											<span class="border"></span>
										</label>
									</div>
								</div>
								<input type="hidden" id="harga-sementara" name="harga-sementara" value="0">
								<input type="hidden" id="list_satuan" name="list_satuan" value="">
							</div>
						</section>
						<!-- Pilihan 2 -->
						<h2>
							<span class="step-icon"><i class="zmdi zmdi-home"></i></span>
							<span class="step-text">Alamat</span>
						</h2>
						<section>
							<div class="inner">
								<h3>Harap Masukkan Alamat Anda</h3>
								<div class="form-row">
									<div class="form-holder form-holder-2">
										<label class="form-row-inner">
											<input type="text" class="form-control" id="alamat" name="alamat" required>
											<span class="label">Alamat Lengkap</span>
											<span class="border"></span>
										</label>
									</div>
								</div>
								<span>Tentukan titik lokasi anda :</span>
								<p></p>
								<div id="googleMaps" style="width:100%; height:380px; border:solid black 1px;"></div>
								<input type="hidden" onClick="updateAddress" id="lat" name="lat" value="">
								<input type="hidden" onClick="updateAddress" id="lng" name="lng" value="">
							</div>
						</section>
						<!-- Pilihan 3 -->
						<h2>
							<span class="step-icon"><i class="zmdi zmdi-card"></i></span>
							<span class="step-text">Pembayaran</span>
						</h2>
						<section>
							<div class="inner">
								<h3>Metode Pembayaran:</h3>
								<div class="form-row table-responsive">
									<table class="table">
										<tbody>
											<tr class="space-row">
												<input type="radio" class="pay" name="pay" id="cod" value="COD" checked>
												<span class="label">Cash On Delivery</span>
												<th class="space-row">
													<img src="images/cod192.png" alt="pay-1">
												</th>
											</tr>
											<tr class="space-row">
												<th>
													<span class="label">Pembayaran dengan metode lain masih dalam tahap pengembangan dan pembelajaran</span>
												</th>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</section>
						<!-- Pilihan 4 -->
						<h2>
							<span class="step-icon"><i class="zmdi zmdi-receipt"></i></span>
							<span class="step-text">Konfirmasi</span>
						</h2>
						<section>
							<div class="inner">
								<h3>Detail Konfirmasi :</h3>
								<div class="form-row table-responsive">
									<table>
									<tbody>
											<tr class="space-row">
												<th>Jenis Laundry </th>
												<td id="jenis_laundry-val"></td>
											</tr>
											<tr class="space-row">
												<th id="PilihanWaktuKiloanText" style="display:none">Pilihan Waktu Laundry </th>
												<td id="pilihanWaktuKiloan" style="display:none"></td>
											</tr>
											<tr class="space-row">
												<th id="beratBarangText" style="display:none">Berat Barang </th>
												<td id="berat_barang-val" style="display:none"></td>
											</tr>
											<tr class="space-row">
												<th id="jumlahBarangText" style="display:none">Jumlah Barang </th>
												<td id="jumlah_barang-val" style="display:none"></td>
											</tr>
											<tr class="space-row">
												<th style="display:none">Catatan Tambahan </th>
												<td id="catatan-val" style="display:none"></td>
											</tr>
											<tr class="space-row">
												<th>Waktu Penjemputan </th>
												<td id="waktu_pengambilan-val"></td>
											</tr>
											<tr class="space-row">
												<th>Waktu Pengantaran </th>
												<td id="waktu_pengantaran-val"></td>
											</tr>
											<tr class="space-row">
												<th>Alamat </th>
												<td id="alamat-val"></td>
											</tr>
											<tr class="space-row">
												<th>
													<input type="checkbox" id="tampilkanPeta" name="tampilkanPeta" value="">
													<label for="checkbox">Tampilkan Peta</label>
												</th>
												<td>
													<div id="googleMapsK" style="width:100%; height:200px; display:none;"></div>
												</td>
											</tr>
											<tr class="space-row">
												<th style="display:none">Harga Total </th>
												<td id="harga-val" style="display:none"></td>
											</tr>
											<tr class="space-row">
												<th>Metode Pembayaran </th>
												<td id="pay-val"></td>
											</tr>
										</tbody>
									</table>
								</div>
							</div>
						</section>
					</form>
				</div>
			</div>
		</div>
	</body>
</html>
<script>
			$(document).ready(function() {
			$('#kiloanCheck').click(function() {
				$('#kiloan_checked').show();
				$('#satuan_checked').hide();
				resetFields();
				updatePilihanWaktuKiloan();
			});
			$('#satuanCheck').click(function() {
				$('#kiloan_checked').hide();
				$('#satuan_checked').show();
				resetFields();
			});

			$('#jumlahHari').change(function() {
				updateTanggalPengantaran();
			});

			$('#tanggalPengambilan').change(function() {
				updateTanggalPengantaran();
			});
		});
        document.querySelectorAll('.item-qty').forEach(function(input) {
            input.addEventListener('input', calculateTotal);
        });

        function calculateTotal() {
            let val = 0;
            let jml = 0;

            document.querySelectorAll('.item-qty').forEach(function(input) {
                const quantity = parseInt(input.value) || 0;
                const price = parseInt(input.dataset.price);

                val += quantity * price;
                jml += quantity;
            });

            document.getElementById("harga-sementara").value = val;
				document.getElementById("jumlahBarang").value = jml;
				document.getElementById("hargaTotal").value = val;


        }

			function updateTotal() {
			var selectElement = document.getElementById("jumlahHari");
			var selectedOption = selectElement.options[selectElement.selectedIndex];
			var price = selectedOption.value; // Get the value attribute of the selected option
			var totalPriceInput = document.getElementById("hargaTotal");
			totalPriceInput.value = price; // Set the value of the total price input
				}

			// Listen for changes in the select element
			document.getElementById("jumlahHari").addEventListener("change", updateTotal);

			// Initially calculate and display the total
			updateTotal();

			function updatePilihanWaktuKiloan() {
				const selectedOption = $('#jumlahHari').find(':selected');
				const pilihanWaktu = selectedOption.text();
				$('#pilihanWaktuKiloan').text(pilihanWaktu);
			}

			function updateTanggalPengantaran() {
				const tanggalPengambilan = $('#tanggalPengambilan').val();
				const selectedOption = $('#jumlahHari').find(':selected');
				const tambahanHari = parseFloat(selectedOption.data('hari'));

				if (tanggalPengambilan && !isNaN(tambahanHari)) {
					let tanggalPengambilanDate = new Date(tanggalPengambilan);
					tanggalPengambilanDate.setDate(tanggalPengambilanDate.getDate() + tambahanHari);
					const tanggalPengantaran = tanggalPengambilanDate.toISOString().split('T')[0];
					$('#tanggalPengantaran').val(tanggalPengantaran);
				} else {
					$('#tanggalPengantaran').val('');
				}
			}
			function initMap() {
			var propertiPeta = {
				center: new google.maps.LatLng(-5.147842, 119.432448),
				zoom: 14,
				mapTypeId: google.maps.MapTypeId.ROADMAP
			};

			var peta = new google.maps.Map(document.getElementById("googleMaps"), propertiPeta);
			var penanda;

			google.maps.event.addListener(peta, 'click', function(event) {
				taruhPenanda(this, event.latLng);
				updateAddress(event.latLng);
			});

			function taruhPenanda(peta, posisiTitik) {
				if (penanda) {
						penanda.setPosition(posisiTitik);
				} else {
						penanda = new google.maps.Marker({
							position: posisiTitik,
							map: peta
						});
				}

				var lat = posisiTitik.lat();
				var lng = posisiTitik.lng();

				$('#lat').val(lat.toFixed(6));
				$('#lng').val(lng.toFixed(6));
			}

			function updateAddress(latLng) {
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode({ 'location': latLng }, function(results, status) {
						if (status === 'OK') {
							if (results[0]) {
								var address = results[0].formatted_address;
								$('#alamat').val(address);
							}
						} else {
							console.log('Geocoder failed due to: ' + status);
						}
				});
			}
		}

    </script>