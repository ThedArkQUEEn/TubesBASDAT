<?php
session_start();
require_once "database.php";

// Memanggil dari kelas database
$pdo = new database();

// Jika user sudah pernah login maka akan diarahkan ke halaman profile
if(isset($_SESSION['email'])){
    header('Location: dashboard.php');
}

if(isset($_POST['submit'])){
    // Init
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Mengecek apakah loginnya cocok dengan database
    $login = $pdo -> login($email);

    // Mengecek apakah password cocok
    if (password_verify($password, $login['password'])){
        // Memasukkan data ke session
        $_SESSION['id'] = $login['id'];
        $_SESSION['name'] = $login['name'];
        $_SESSION['email'] = $email;
        $_SESSION['nomortelepon'] = $login['nomor_telepon'];
        setcookie('user_id', $_SESSION['id'], time()+(7 * 24 * 60 * 60), '/');
        header("Location: dashboard.php");
    }
    // Pesan error jika e-mail atau password salah
    else{
        echo '<div class="alert alert-danger" role="alert">E-mail atau password salah, silahkan ulangi kembali</div>';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Laundry Online</title>
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
            background-color: rgba(255, 255, 255, 0.5);
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
                        <h1 class="text-center mb-4">Signin Laundry Online</h1>
                        <form method="POST">
                            <div class="form-group">
                                <label for="email">E-Mail :</label>
                                <input type="email" name="email" id="email" class="form-control" required placeholder="Masukkan e-mail">
                            </div>
                            <div class="form-group">
                                <label for="password">Password :</label>
                                <input type="password" name="password" id="password" class="form-control" required placeholder="Masukkan password">
                                <div class="form-check mt-2">
                                    <input type="checkbox" class="form-check-input" id="showPasswordCheckbox" onclick="myFunction()">
                                    <label class="form-check-label" for="showPasswordCheckbox">Show Password</label>
                                </div>
                            </div>
                            <button type="submit" name="submit" class="btn btn-primary btn-block">Login</button>
                        </form>
                        <div class="mt-3">
                            <p class="text-center">Belum mendaftar? <a href="signup.php">Daftar Sekarang!</a></p>
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