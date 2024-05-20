<?php
session_start();
require_once "database.php";
$pdo = new database();
$edit_form = false;

// Jika user belum login dan membuka ini, maka langsung diarahkan ke halaman login
if(!isset($_SESSION['email'])){
    header('Location: login.php');
}

// Jika admin login, maka langsung diarahkan ke halaman dashboard admin
// Ubah e-mailnya jika ingin mengganti akun admin
if($_SESSION['email'] == 'admin@laundryonlinemks.com'){
    header('Location: admin_dash.php');
}

// Memanggil tabel pesanan
$rows = $pdo->getPesanan($_SESSION['id']);

// Mengambil data dan menaruh di kotak edit
if(isset($_GET['edit'])){
  $data = $pdo->getEditPesanan($_GET['edit']);
  $edit_form = true;
  $name = $data['name'];
  $email = $data['email'];
  $nomor_telepon = $data['nomor_telepon'];
  $id = $data['id'];
}

// Mengupdate data
if(isset($_POST['update'])){
  $update = $pdo->updateData($_POST['nama'], $_POST['email'], $_POST['password'], $_POST['nomor_telepon'], $id);
  $_SESSION['name'] = $_POST['nama'];
  $_SESSION['email'] = $_POST['email'];
  $_SESSION['nomortelepon'] = $_POST['nomor_telepon'];
  // Cara get data dari spesifik nama_order terus dikirim kesini
  header("Location: dashboard.php#profil");
}

// Untuk tombol membatalkan edit
if(isset($_POST['cancel'])){
  header("Location: dashboard.php#profil");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard User - Laundry Online</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <!-- Custom CSS -->
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
            width: 250px; /* Adjust as needed */
        }

        .jumbotron {
            margin-top: 90px; /* Adds space of 50px on top */
        }
    </style>
</head>
<body class="fadeIn">   
    <!-- Navbar -->
    <nav id="navbar-user" class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary">
        <a class="navbar-brand" href="#">Laundry Online</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>      
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link active" href="#navbar-user">Beranda</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="order.php">Order</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#status-order">Status Order</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" href="#profil">Profil</a>
                </li>
                <li class="nav-item">
                    <a class="btn btn-outline-success active" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Isi -->
    
    <div class="container mt-5">
        <div class="jumbotron">
            <a> <img src="images/img1.png" alt="Logo" class="logo"></a>
            <h1 id="beranda" class="display-4">Selamat datang, <?php echo $_SESSION['name']; ?>!</h1>
            <p class="lead">Silahkan order atau cek status laundry Anda disini.</p>
            <a class="btn btn-success" href="order.php">ORDER DISINI</a>
        </div>

        <div class="jumbotron bg-white">
            <h1 id="status-order">Status Order</h1>
            <p>Cek status laundry Anda disini.</p>
            <table id="pagination" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Jenis Laundry</th>
                        <th>Pilihan Waktu laundry</th>
                        <th>Massa Barang</th>
                        <th>Jumlah Barang</th>
                        <th>Waktu Pengambilan</th>
                        <th>Waktu Pengantaran</th>
                        <th>Alamat</th>
                        <th>Catatan</th>
                        <th>Harga Total</th>
                        <th>Status Pemesanan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= $row['jenis_laundry'] ?></td>
                            <td><?= $row['pilihan_hari'] ?></td>
                            <td><?= $row['massa_barang'] ?></td>
                            <td><?= $row['jumlah_barang'] ?></td>
                            <td><?= $row['waktu_pengambilan'] ?></td>
                            <td><?= $row['waktu_pengantaran'] ?></td>
                            <td><?= $row['alamat'] ?></td>
                            <td><?= $row['catatan'] ?></td>
                            <td><?= $row['harga_total'] ?></td>
                            <td><?= $row['status_pemesanan'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <div class="jumbotron bg-dark text-light">
            <h1 id="profil">Informasi Profil</h1>
            <p>Informasi tentang Nama, E-mail, dan Nomor Telepon yang digunakan.</p>
            <ul>
                <li>Nama            : <?= $_SESSION['name'] ?></li>
                <li>E-mail          : <?= $_SESSION['email'] ?></li>
                <li>Nomor Telepon   : <?= $_SESSION['nomortelepon'] ?></li>
            </ul>
            <br>
            <form action="dashboard.php?edit=<?= $_SESSION['id']; ?>#profil" method="post">
                <input type="hidden" name="id" value="<?= $_SESSION['id'] ?>">
                <input type="submit" value="Edit" name="edit">
            </form>

            <?php if ($edit_form == true): ?>
                <h4>Edit profil</h4>
                <form method="post">
                    <p>Nama : <input type="text" name="nama" value="<?= $name; ?>"></p>
                    <p>E-mail : <input type="email" name="email" value="<?= $email; ?>"></p>
                    <p>Password : <input type="password" name="password" id="password" pattern=".{8,}" required title="Minimum 8 karakter" placeholder="Masukkan password"></p>
                    <p><input type="checkbox" onclick="myFunction()"> Show Password</p>
                    <p>Nomor Telepon : <input type="text" name="nomor_telepon" value="<?= $nomor_telepon; ?>" pattern=".{10,14}" required></p>
                    <input type="submit" name="update" value="Update">
                    <input type="button" onclick="location.href='dashboard.php#profil';" value="Cancel">
                </form>
            <?php endif; ?>
        </div>
    </div>

    <!-- Footer -->
    <footer class="page-footer font-small blue bg-dark text-white fixed-bottom">
    <div class="footer-copyright text-center py-3 bg-dark text-white">© 2024 Copyright:
        <a href="https://laundryonlinemks.com/"> Laundry Online</a>
    </div>
    </footer>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <!-- Custom JS -->
    <script>
        $(document).ready(function() {
            $('#pagination').DataTable();
        });

        // Memunculkan password
        function myFunction() {
            var x = document.getElementById("password");
            if (x.type === "password") {
                x.type = "text";
            } else {
                x.type = "password";
            }
        }
    </script>
</body>
</html>
