<?php
session_start();
require_once "database.php";
//Memanggil kelas database
$pdo = new database();
$edit_form = false;
$view_order = false;

//Init
$garisLintang = "";
$garisBujur = "";

//Jika user belum login dan membuka ini, maka langsung diarahkan ke halaman login
if(isset($_SESSION['email']) == 0){
    exit("<h1>Access Denied</h1>");
}

//Akses selain e-mail admin akan ditolak
//Ubah e-mailnya jika ingin mengganti akun admin
if($_SESSION['email'] != 'admin@laundryonlinemks.com'){
    exit("<h1>Access Denied</h1>");
}

//Memunculkan data customers
$rows = $pdo -> showData();

//Memunculkan data order
$orders = $pdo -> showPesanan();

//Menghapus data
if (isset($_POST['delete'])){
    //Jika tekan hapus admin
    //Ubah nomor jika id admin berubah
    if($_POST['id'] == 1){
        echo('<div class="alert alert-danger" role="alert">');
        echo('Tidak bisa hapus administrator');
        echo('</div>');
    }
    else{
        $pdo -> deleteData($_POST['id']);
        header("Location: admin_dash.php#customers");
    }
}

//Mengambil data dan menaruh di kotak edit customer
if(isset($_GET['edit'])){
    $data = $pdo -> getData($_GET['edit']);
    $edit_form = true;
    $name = $data['name'];
    $email = $data['email'];
    $nomor_telepon = $data['nomor_telepon'];
    $id = $data['id'];
}

//Mengambil data dan menaruh di kotak view order
if(isset($_GET['view'])){
    $pemesanan = $pdo -> getOrder($_GET['view']);
    $view_order = true;
    $userId = $pemesanan['id_user'];
    $jenisLaundry = $pemesanan['jenis_laundry'];
    $pilihanhari = $pemesanan['pilihan_hari'];
    $massaBarang = $pemesanan['massa_barang'];
    $jumlahBarang = $pemesanan['jumlah_barang'];
    $waktuPengambilan = $pemesanan['waktu_pengambilan'];
    $waktuPengantaran = $pemesanan['waktu_pengantaran'];
    $alamat = $pemesanan['alamat'];
    $catatan = $pemesanan['catatan'];
    $garisLintang = "".$pemesanan['garis_lintang'].", ";
    $garisBujur = $pemesanan['garis_bujur'];
    $hargaTotal = $pemesanan['harga_total'];
    $statusPemesanan = $pemesanan['status_pemesanan'];
    $orderId = $pemesanan['id'];
    $listSatuan = $pemesanan['list_satuan'];
}

//Mengupdate data customers
if(isset($_POST['update'])){
    $update = $pdo -> updateData($_POST['nama'], $_POST['email'], $_POST['password'], $_POST['nomor_telepon'], $id);
    header("Location: admin_dash.php#customers");
}

//Mengupdate data order
if(isset($_POST['update_order'])){
    $statusPemesanan = $_POST['status_pemesanan'];
    $catatan = $_POST['catatan'];
    $update = $pdo -> updateOrderData($catatan, $statusPemesanan, $id);
    header("Location: admin_dash.php#pesanan");
}

if (isset($_POST['update_order'])) {
    // Ambil data dari form
    $radio_status = isset($_POST['status']) ? $_POST['status'] : '';
    $catatan = $_POST['catatan'];

    // Cek jika checkbox 'Dalam Perjalanan' dipilih
    if (isset($_POST['status']) && $_POST['status'] == 'Dalam Perjalanan') {
        $radio_status = 'Dalam Perjalanan';
    }

    // Pastikan $radio_status tidak kosong sebelum update
    if (!empty($radio_status)) {
        // Panggil fungsi untuk memperbarui data di database
        $update = $pdo->updateOrderData($radio_status, $catatan, $orderId);
        
        // Alihkan pengguna ke halaman dashboard admin
        header("Location: admin_dash.php#pesanan");
    } else {
        // Tampilkan pesan error jika status tidak dipilih
        echo "Harap pilih status pemesanan.";
    }
}

//Mengupdate data list satuan, jumlah barang, 



//Untuk tombol membatalkan edit
if(isset($_POST['cancel'])){
    header("Location: admin_dash.php#customers");
}

//Untuk tombol membatalkan edit
if(isset($_POST['cancel_update'])){
    header("Location: admin_dash.php#pesanan");
}

//Mengambil jumlah data customers
$banyakdata = $pdo -> banyak_data();

//Mengambil jumlah data pesanan
$banyakpesanan = $pdo -> banyak_pesanan();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Laundry OnLine</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
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

        html {
            scroll-behavior: smooth;
        }

        .logo {
            position: absolute;
            top: 60px;
            left: 50px;
            width: 250px;
        }

        .jumbotron {
            margin-top: 90px; /* Adds space of 50px on top */
        }
    </style>
</head>
<body class="fadeIn">
    <!-- Navbar -->
    <nav id="navbar-admin" class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="/admin_dash.php"><b>Laundry Online</b></a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#beranda">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#statistik">Statistik</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#pesanan">Pesanan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#customers">Profil Customers</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-success active" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>
    <!-- Isi -->
    <div data-spy="scroll" data-target="#navbar-admin" data-offset="0">
        <!-- Beranda -->
        <div id="beranda" class="jumbotron jumbotron-fluid bg-light">
            <div class="container">
                <h1 class="display-4">Selamat datang, <?php echo $_SESSION['name']; ?>!</h1>
                <p class="lead">Anda berada di ruang admin, cek pesanan dan profil pelanggan disini.</p>
            </div>
        </div>
        <!-- Statistik -->
        <div id="statistik" class="jumbotron jumbotron-fluid bg-white">
            <div class="container text-center">
                <h1 class="display-4">Statistik</h1>
                <p class="lead">Melihat berapa banyak pesanan dan pelanggan yang terdaftar.</p>
                <div class="row">
                    <div class="col-md-6">
                        <h3>Pesanan</h3>
                        <img src="images/pesanan.png" width="100px">
                        <h5><?php echo $banyakpesanan ?> pesanan</h5>
                    </div>
                    <div class="col-md-6">
                        <h3>Pelanggan</h3>
                        <img src="images/users.png" width="100px">
                        <h5><?php echo $banyakdata-1; ?> pelanggan</h5>
                    </div>
                </div>
            </div>
        </div>
        <!-- Pesanan -->
        <div id="pesanan" class="jumbotron jumbotron-fluid bg-light">
            <div class="container">
                <h1 class="display-4 text-center">Pesanan</h1>
                <p class="lead text-center">Daftar pesanan dari pelanggan.</p>
                <div class="table-responsive">
                    <table id="pagination" class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>ID User</th>
                                <th>Jenis Laundry</th>
                                <th>Pilihan Waktu laundry</th>
                                <th>List Satuan</th>
                                <th>Massa Barang</th>
                                <th>Jumlah Barang</th>
                                <th>Harga Total</th>
                                <th>Status Pemesanan</th>
                                <th>Catatan</th>
                                <th>Action</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <tr>
                                    <td><?=$order['id_user'] ?></td>
                                    <td><?=$order['jenis_laundry'] ?></td>
                                    <td><?=$order['pilihan_hari'] ?></td>
                                    <td><?=$order['list_satuan'] ?></td>
                                    <td><?=$order['massa_barang'] ?></td>
                                    <td><?=$order['jumlah_barang'] ?></td>
                                    <td><?=$order['harga_total'] ?></td>
                                    <td><?=$order['status_pemesanan'] ?></td>
                                    <td><?=$order['catatan'] ?></td>
                                    <td>
                                        <form action="admin_dash.php?view=<?php echo $order['id']; ?>#pesanan" method="post">
                                            <input type="hidden" name="id" value="<?=$order['id']?>">
                                            <input type="submit" value="View" name="view" class="btn btn-primary btn-sm">
                                        </form>
                                        <td>
                                          <form method="post">
                                             <input type="hidden" name="id" value="<?=$row['id']?>">
                                             <input type="submit" value="Delete" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
                                          </form>
                                       </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <!-- View Data -->
                <?php if ($view_order): ?>
                <div class="container">
                    <h4 class="display-4 text-center">View Data</h4>
                    <form method="post" class="text-center">
                        <div class="form-group row">
                            <label for="userId" class="col-sm-4 col-form-label">ID User :</label>
                            <div class="col-sm-12">
                                <span id="userId"><?php echo $userId; ?></span>
                            </div>
                        </div>
                        <form method="POST">
                                <div class="form-group row">
                                    <label for="status" class="col-sm-4 col-form-label">Status Pemesanan :</label>
                                    <div class="col-sm-12">
                                        <input type="radio" id="tunggu_konfirmasi" name="status" value="Tunggu Konfirmasi" <?php if ($order['status_pemesanan'] == "Tunggu Konfirmasi") echo "checked"; ?>>
                                        <label for="tunggu_konfirmasi">Tunggu Konfirmasi</label>
                                        <input type="radio" id="pengambilan" name="status" value="Dalam Pengambilan" <?php if ($order['status_pemesanan'] == "Dalam Pengambilan") echo "checked"; ?>>
                                        <label for="pengambilan">Dalam Pengambilan</label>
                                        <br>
                                        <input type="radio" id="dalam_proses_pengecekan" name="status" value="Dalam Proses Pengecekan" <?php if ($order['status_pemesanan'] == "Dalam Proses Pengecekan") echo "checked"; ?>>
                                        <label for="dalam_proses_pengecekan">Dalam Proses Pengecekan</label>
                                        <input type="radio" id="dalam_proses_pencucian" name="status" value="Dalam Proses Pencucian" <?php if ($order['status_pemesanan'] == "Dalam Proses Pencucian") echo "checked"; ?>>
                                        <label for="dalam_proses_pencucian">Dalam Proses Pencucian</label>
                                        <br>
                                        <input type="radio" id="dalam_proses_pengiriman" name="status" value="Dalam Proses Pengiriman ke Alamat Tujuan" <?php if ($order['status_pemesanan'] == "Dalam Proses Pengiriman ke Alamat Tujuan") echo "checked"; ?>>
                                        <label for="dalam_proses_pengiriman">Dalam Proses Pengiriman ke Alamat Tujuan</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="catatan" class="col-sm-4 col-form-label">Catatan :</label>
                                    <div class="col-sm-12">
                                        <textarea id="catatan" name="catatan" rows="4" cols="50"><?php echo $order['catatan']; ?></textarea>
                                    </div>
                                </div>
                                <!-- Tombol Aksi -->
                                <div class="form-group row">
                                    <div class="col-sm-12">
                                        <input type="submit" name="update_order" value="Update" class="btn btn-warning">
                                        <input type="submit" name="cancel_update" value="Cancel" class="btn btn-secondary">
                                    </div>
                                </div>
                            </form>

                            </div>
                        </div>
                    </form>
                </div>
                <?php endif; ?>

                </div>
            </div>
        </div>
    </div>
</body>
</html>
<!-- Profil Pelanggan -->
<div id="customers" class="jumbotron jumbotron-fluid bg-white">
    <div class="container">
        <h1 class="display-4 text-center">Profil Pelanggan</h1>
        <p class="lead text-center">Daftar pelanggan yang terdaftar.</p>
        <div class="table-responsive">
            <table id="pagination2" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID User</th>
                        <th>Nama</th>
                        <th>E-mail</th>
                        <th>Nomor Telepon</th>
                        <th>Edit</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?=$row['id']?></td>
                            <td><?=$row['name'] ?></td>
                            <td><?=$row['email'] ?></td>
                            <td><?=$row['nomor_telepon']?></td>
                            <td>
                                <form action="admin_dash.php?edit=<?php echo $row['id']; ?>#customers" method="post">
                                    <input type="hidden" name="id" value="<?=$row['id']?>">
                                    <input type="submit" value="Edit" name="edit" class="btn btn-primary btn-sm">
                                </form>
                            </td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="id" value="<?=$row['id']?>">
                                    <input type="submit" value="Delete" name="delete" class="btn btn-danger btn-sm" onclick="return confirm('Apakah Anda yakin ingin menghapus pelanggan ini?')">
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <!-- Edit Customer -->
    <?php if ($edit_form): ?>
    <div class="container">
        <h4 class="text-center">Edit customer</h4>
        <form method="post" class="text-center">
            <!-- Input Nama -->
            <div class="form-group row">
                <label for="nama" class="col-sm-4 col-form-label">Nama:</label>
                <div class="col-sm-8">
                    <input type="text" name="nama" value="<?php echo $name; ?>" class="form-control" id="nama">
                </div>
            </div>
            <!-- Input Email -->
            <div class="form-group row">
                <label for="email" class="col-sm-4 col-form-label">E-mail:</label>
                <div class="col-sm-8">
                    <input type="email" name="email" value="<?php echo $email; ?>" class="form-control" id="email">
                </div>
            </div>
            <!-- Input Password -->
            <div class="form-group row">
                <label for="password" class="col-sm-4 col-form-label">Password:</label>
                <div class="col-sm-8">
                    <input type="password" name="password" id="password" class="form-control">
                </div>
            </div>
            <!-- Input Password Confirm -->
            <div class="form-group row">
                <label for="password_confirm" class="col-sm-4 col-form-label">Confirm Password:</label>
                <div class="col-sm-8">
                    <input type="password" name="password_confirm" id="password_confirm" class="form-control">
                </div>
            </div>
            <!-- Checkbox Show Password -->
            <div class="form-group row">
                <div class="col-sm-8 offset-sm-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" onclick="togglePassword()" id="showPasswordCheck">
                        <label class="form-check-label" for="showPasswordCheck">Show Password</label>
                    </div>
                </div>
            </div>
            <!-- Input Nomor Telepon -->
            <div class="form-group row">
                <label for="nomor_telepon" class="col-sm-4 col-form-label">Nomor Telepon:</label>
                <div class="col-sm-8">
                    <input type="text" name="nomor_telepon" value="<?php echo $nomor_telepon; ?>" class="form-control" id="nomor_telepon">
                </div>
            </div>
            <!-- Tombol Aksi -->
            <div class="form-group row">
                <div class="col-sm-8 offset-sm-4">
                    <input type="submit" name="update" value="Update" class="btn btn-warning">
                    <input type="submit" name="cancel" value="Cancel" class="btn btn-secondary">
                </div>
            </div>
        </form>
    </div>
<?php endif; ?>

<script>
    function togglePassword() {
        var passwordField = document.getElementById("password");
        var confirmPasswordField = document.getElementById("password_confirm");
        var checkBox = document.getElementById("showPasswordCheck");

        if (checkBox.checked) {
            passwordField.type = "text";
            confirmPasswordField.type = "text";
        } else {
            passwordField.type = "password";
            confirmPasswordField.type = "password";
        }
    }
</script>



<!-- Footer -->
<footer class="page-footer font-small blue bg-dark text-white fixed-bottom">
  <div class="footer-copyright text-center py-3 bg-dark text-white">© 2024 Copyright:
    <a href="https://laundryonlinemks.com/"> Laundry Online</a>
  </div>
</footer>

<script>
    //Menggunakan library DataTables
    $(document).ready(function() {
        $('#pagination').DataTable();
    } );

    $(document).ready(function() {
        $('#pagination2').DataTable();
    } );

//Memunculkan password
function myFunction(){
        var x = document.getElementById("password");
        if (x.type === "password"){
            x.type = "text";
        } 
        else{
            x.type = "password";
        }
    }

// fungsi initialize untuk mempersiapkan peta
function initMap() {

        // Menentukan koordinat awal peta, perbesaran, serta jenis peta
        var propertiPeta = {
            center:new google.maps.LatLng(<?php echo $garisLintang; ?> <?php echo $garisBujur; ?>),
            zoom:15,
            mapTypeId:google.maps.MapTypeId.ROADMAP
        };

        // Inisiasi peta sesuai dengan Id yang telah ditentukan
        var peta = new google.maps.Map(document.getElementById("googleMaps"), propertiPeta);
        
        // Menambahkan penanda pada peta
        var marker=new google.maps.Marker({
            position: new google.maps.LatLng(<?php echo $garisLintang; ?> <?php echo $garisBujur; ?>),
            map: peta,
            animation: google.maps.Animation.BOUNCE
        });
    }
    // event jendela dimuat  
    google.maps.event.addDomListener(window, 'load', initMap);
</script>