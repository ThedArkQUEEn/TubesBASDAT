<?php
session_start();
require_once "database.php";
//Memanggil kelas database
$pdo = new database();

//Jika user sudah login, maka akan langsung terpindah ke dashboard user/admin
if(isset($_SESSION['email']) == 0){

}
else{
    header("Location: dashboard.php");    
}

//Memunculkan daftar harga
$rows = $pdo -> getHarga();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="Animation/index.css">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCKtmUDqFDJ8-D3F0nJM4bpiD4hAR-fzeo"></script> <!-- Ganti YOUR_API_KEY dengan API key Google Maps Anda -->
    <title>LAUNDRY ONLINE</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        /* Animasi fadeIn */
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

        .background-white {
            background-color: white; /* Tambahkan padding jika perlu */
            background-color: rgba(255, 255, 255, 0.5)
        }

        .carousel-item-img {
            opacity: 0.5; /* Memberikan opacity sebesar 50% */
        }
    </style>
</head>

<body class="fadeIn">
    <!-- Navbar -->
    <nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-primary align-items-center">
    <a class="navbar-brand" href="index.php">
        <p style="font-size:25px;margin-bottom: 0;"><b>Laundry Online</b></p>
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
        <div class="navbar-nav ml-auto">
            <a class="nav-item nav-link active nav-pills nav-fill" href="#carouselControls">Beranda</a>
            <a class="nav-item nav-link active" href="#how-to-order">Tutorial Order</a>
            <a class="nav-item nav-link active" href="#daftar-harga">Daftar Harga</a>
            <a class="nav-item nav-link active" href="#faq">FAQ</a>
            <a class="nav-item nav-link active" href="#hubungi-kami">Hubungi Kami</a>
            <a class="btn btn-outline-success active" href="login.php"><b>Login</b></a>
        </div>
    </div>
</nav>

    <!-- Carousel -->
    <div id="carouselControls" class="carousel slide" data-ride="carousel" data-interval="5000">
        <div class="carousel-inner text-center">     
            <div class="carousel-item active"> 
                <img class="d-block mx-auto carousel-item-img" src="images/laundry1.jpg">
              </div>
                <div class="carousel-caption">             
                    <h6><img src="images/img1.png" width="350px"></h6>
                    <h1><a class="display-4 text-primary btn-lg2"><b>Selamat Datang di Laundry Online</b></a></h1>
                    <br>
                    <h1><a class="text-primary btn-lg2">Solusi cuci setiap hari</a></h1>
                    <br><br>
                    <h6><a class="btn btn-primary btn-lg" href="signup.php" role="button">Daftar Sekarang</a></h6>
                    <br><br><br><br>
                </div>
            <!-- Tambahkan carousel item sesuai kebutuhan -->
            <div class="carousel-item">
                <img src="images/laundry.jpg" class="d-block mx-auto carousel-item-img">
            </div>
            <div class="carousel-item">
                <img src="images/laundry2.jpg" class="d-block mx-auto carousel-item-img">
            </div>
            <div class="carousel-item">
                <img src="images/laundry3.jpg" class="d-block mx-auto carousel-item-img">
            </div>
            <div class="carousel-item">
                <img src="images/laundry5.jpg" class="d-block mx-auto carousel-item-img">
            </div>
            <div class="carousel-item">
                <img src="images/laundry6.jpg" class="d-block mx-auto carousel-item-img">
            </div>
        </div>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function(){
        function moveHR() {
            $("#how-to-order").animate({marginLeft: '-30px'}, 1000, 'linear', function() {
                $(this).animate({marginLeft: '0'}, 1000, 'linear', moveHR);
            });
        }
        moveHR(); // Panggil fungsi untuk memulai animasi
    });
</script>

   <hr id="how-to-order">
   <div class="jumbotron jumbotron-fluid bg-white" id="how-to-order">
   <div class="container">
      <h1 class="display-4">Tutorial Order</h1>
      <br>
      <p style="text-align:left;" class="lead">Pengen order? Yuk simak cara dibawah ini</p>
      <hr class="my-4">
      <!-- Di row ini isinya merupakan tutorial beserta gambar dengan bentuk kotak ya -->
      <div class="row">
         <div class="col-sm">
               <h6><img src="images/img3.png" width="135px"> </h6>
               <h5>Daftar</h5>
               <p>Pelanggan mendaftarkan dirinya pada website dan melakukan pemesanan</p>                   
         </div>
         <div class="col-sm">
               <h6><img src="images/img4.png" width="100px"> </h6>
               <h5>Pengambilan</h5>
               <p>Pihak kami akan mengambil barang yang akan dilaundry</p> 
         </div>
         <div class="col-sm">
               <h6><img src="images/img5.png" width="108px"> </h6>
               <h5>Pencucian</h5>
               <p>Pencucian baju sesuai pemesanan</p>  
         </div>
         <div class="col-sm">
               <h6><img src="images/img6.png" width="100px"> </h6>
               <h5>Pengantaran</h5>
               <p>Pihak kami akan mengantarkan barang yang telah dilaundry ke alamat rumah Anda</p>               
         </div>
         <div class="col-sm">
               <h6><img src="images/img7.png" width="160px"> </h6><br>
               <h5>Pembayaran</h5>
               <p>Pembayaran pemesanan dapat dilakukan secara Cash on Delivery (COD)</p> 
         </div>
         <!-- Tambahkan tutorial order sesuai kebutuhan -->
      </div>
   </div>
   </div>


    <hr id="daftar-harga">
    <div class="jumbotron jumbotron-fluid" id="daftar-harga">
        <div class="container">
            <h1 class="display-4">Daftar Harga Laundry Satuan*</h1>
            <br>
            <p style="text-align:left;" class="lead">Berikut ini merupakan daftar harga yang tersedia, murah!</p>
            <hr class="my-4">
            <br>
            <table id="pagination" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th scope="col">No.</th>
                        <th scope="col">Nama Barang</th>
                        <th scope="col">Harga/pcs</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($rows as $row): ?>
                        <tr>
                            <td><?= $row['id'] ?></td>
                            <td><?= $row['nama_barang'] ?></td>
                            <td><?= $row['harga'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p style="text-align:left;" class="lead">*Hanya tersedia pilihan 2 hari</p>
        </div>
    </div>

    <hr id="faq">
    <div class="jumbotron jumbotron-fluid bg-white" id="faq">
        <div class="container">
        <h1 class="display-4">Frequently Asked Questions (FAQ)</h1>
            <br>
            <p style="text-align:left;" class="lead">Pertanyaan yang sering ditanyakan pelanggan</p>
            <hr class="my-4">
            <h3>Bagaimana saya dapat menggunakan layanan Laundry Online?</h3>
            <p> Dapat digunakan melalui website resmi Laundry Online yang dapat akses di www.LaundryOnline.com.</p>
                <br>
            <h3> Bagaimana Cara Order Laundry Online?</h3>
            <p>Melalui website Laundry Online, Kamu dapat membuat order dengan memilih lokasi kamu untuk
             penjemputan dan pengembalian. Setelah itu, kamu pilih layanan yang kamu inginkan, buat order, dan menyelesaikan transaksi pembayaran. Kemudian laundry akan diantarkan ke alamatmu.</p>
                <br>
            <h3> Apa saja layanan yang disediakan oleh Laundry Online?</h3>
            <p>Terdapat 2 jenis layanan untuk laundry, yaitu cuci kiloan dan satuan.</p>
         
            <!-- Tambahkan FAQ sesuai kebutuhan -->
        </div>
    </div>

      <hr id="hubungi-kami">
      <div class="jumbotron jumbotron-fluid bg-dark text-white" id="hubungi-kami">
         <div class="container">
               <h1 class="display-4">Hubungi Kami!</h1>
               <br>
               <p style="text-align:left;" class="lead">Jika ada keraguan, kami akan selalu tersedia untuk Anda.</p>
               <hr class="my-4 bg-white">

               <!-- Di row ini isinya merupakan informasi kontak dan peta lokasi -->
               <div class="row">
                  <div class="col-sm">
                     <br>
                     <h4>
                           <b>Alamat Kami:</b>
                     </h4>
                     <p>Jl. Sukabirus No.65, Sukapura, Kec. Dayeuhkolot, Kota Bandung, Jawa Barat 40257</p>
                     <br>
                     <h4>
                           <b>Nomor Telepon:</b>
                     </h4>
                     <p><a href="https://wa/087796308899">0877-9630-8899</a></p>
                     <br>
                     <h4>
                     <h4>
                           <b>Follow Us on (Admin):</b>
                     </h4>
                     <div class="col-sm d-flex flex-column align-items-start">
                     
                     <div class="mt-2" style="margin-left: -20px;">
                        <img src="images/img11.png" width="40px"><span><a style="color: white;" href="https://instagram.com/14junelol">@14junelol</a></span>
                     </div>
                  </div>  
                </div>
                <br>
                <br>
                <div class="col-sm">
                    <div id="googleMaps" style="width:100%;height:380px;"></div>
                </div>
                <!-- Tambahkan informasi kontak dan peta lokasi -->
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="page-footer font-small bg-white fadeIn">
        <div class="container text-center py-3">
            Copyright ©
            <script>
                document.write(new Date().getFullYear());
            </script>
            <a href="https://laundryonline.com/"> Laundry Online</a>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <!-- DataTables JS -->
    <script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap4.min.js"></script>
    <!-- Custom JS -->
   <script>
      // fungsi initialize untuk mempersiapkan peta
      function initialize() {
         var propertiPeta = {
               center: new google.maps.LatLng(-6.976021703481181, 107.633031175035889),
               zoom: 15,
               mapTypeId: google.maps.MapTypeId.ROADMAP
         };

         var peta = new google.maps.Map(document.getElementById("googleMaps"), propertiPeta);

         // membuat Marker untuk lokasi laundry
         var marker = new google.maps.Marker({
               position: new google.maps.LatLng(-6.976021703481181, 107.63303117503588),
               map: peta,
               animation: google.maps.Animation.BOUNCE
         });
      }
      // event jendela di-load  
      google.maps.event.addDomListener(window, 'load', initialize);

      //Menggunakan DataTables
      $(document).ready(function() {
         $('#pagination').DataTable();
      });
   </script>
</body>
</html>