<?php
session_start();
require_once "database.php";

// Memanggil dari kelas database
$pdo = new database();

// Jika sudah terlogin maka akan langsung dipindahkan ke profile
if(isset($_SESSION['email'])){
    header('Location: dashboard.php');
}

if (isset($_POST['submit'])){
    // Mencegah ada e-mail yang sama dalam database
    $email = $_POST['email'];
    $check_data = $pdo->check_data($email);

    // Mencegah data kosong masuk database
    if(empty($_POST['name']) || empty($_POST['email']) || empty($_POST['password']) || empty($_POST['nomorTelepon'])) {
        echo '<div class="alert alert-danger" role="alert">Field tidak boleh kosong!</div>';
    }

    // Pesan yang muncul jika sudah ada e-mail dalam database
    else if($check_data > 0){
        echo '<div class="alert alert-danger" role="alert">E-mail sudah ada, silahkan gunakan e-mail yang berbeda!</div>';
    }

    // Input Data
    else{
        $_SESSION['message'] = 'Data sudah masuk, silahkan kembali <a href="login.php">Login</a>';
        echo '<div class="alert alert-success" role="alert">' . $_SESSION['message'] . '</div>';
        unset($_SESSION['message']);

        // Masukkan data ke database
        $pdo->tambah_data($_POST['name'], $_POST['email'], $_POST['password'], $_POST['nomorTelepon']);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar - Laundry Online</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Custom CSS -->
    <style>
        .background-image {
            background-image: url('images/laundry7.png');
            background-size: 100%;
            background-repeat: no-repeat;
            background-position: center;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .login-card {
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 10px;
        }

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

        .logo {
            position: absolute;
            top: 20px;
            left: 50px;
            width: 250px; /* Adjust as needed */
        }
    </style>
</head>

<body class="fadeIn">
  <a href="index.php"> <img src="images/img1.png" alt="Logo" class="logo" href="index.php"></a>
    <div class="container">
    <div class="row justify-content-center background-image">
            <div class="col-md-6">
                <div class="card mt-5">
                    <div class="card-body border border-dark"> 
                        <h1 class="text-center mb-4">Signup Laundry Online</h1>
                        <form method="POST">
                            <div class="form-group">
                                <label for="name">Nama :</label>
                                <input type="text" name="name" id="name" class="form-control" placeholder="Masukkan nama">
                            </div>
                            <div class="form-group">
                                <label for="email">E-Mail :</label>
                                <input type="email" name="email" id="email" class="form-control" placeholder="Masukkan e-mail">
                            </div>
                            <div class="form-group">
                                <label for="nomorTelepon">Nomor Telepon :</label>
                                <input type="tel" name="nomorTelepon" id="nomorTelepon" class="form-control" placeholder="081234567890" pattern=".{10,14}" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password :</label>
                                <input type="password" name="password" id="password" class="form-control" pattern=".{8,}" required title="Minimum 8 karakter" placeholder="Masukkan password">
                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input" id="showPasswordCheckbox" onclick="myFunction()">
                                    <label class="form-check-label" for="showPasswordCheckbox">Show Password</label>
                                </div>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Signup</button>
                        </form>
                        <div class="mt-3">
                            <p class="text-center">Sudah mendaftar? <a href="login.php">Silahkan Login</a></p>
                            <p class="text-center"><a href="index.php">&lt;-- Back to Home</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script type="application/javascript">
    // Untuk memunculkan password di form
    function myFunction(){
        var x = document.getElementById("password");
        if (x.type === "password"){
            x.type = "text";
        } 
        else{
            x.type = "password";
        }
    }
</script>
</html>