<?php

include './koneksi.php';
include './middleware.php';

if (isset($_POST['login'])) {
    $email = htmlspecialchars($_POST['email']);
    $password = htmlspecialchars($_POST['password']);

    $user_data = $conn->query("SELECT * FROM user WHERE email = '$email'");

    if ($user_data->num_rows > 0) {
        $user_data = $user_data->fetch_assoc();
        $password_check = password_verify($password, $user_data['password']);
        if ($password_check) {
            $_SESSION['user_id'] = $user_data['userID'];
            $_SESSION['username'] = $user_data['username'];
            $_SESSION['namaLengkap'] = $user_data['namaLengkap'];

            unset($_SESSION['login_failed']);

            header('Location: ./index.php');
        } else {
            $_SESSION['login_failed'] = 'password';
        }
    } else {
        $_SESSION['login_failed'] = 'email';
    };
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/output.css">
    <title>Login | GALERAZ</title>
</head>

<body>
    <div class="w-screen h-screen flex items-center justify-center">
        <div class="bg-white shadow-xl flex items-center p-8 gap-10 border-2 border-cyan-200 rounded-3xl">
            <div class="bg-cyan-400 py-28 px-8 rounded-2xl flex flex-col items-center gap-8 relative">
                <img src="./assets/images/logo.svg" alt="logo" class="bg-white p-2 rounded-xl absolute top-0 left-0 m-5">
                <img src="./assets/images/login_illustration.svg" alt="bl" class="min-w-96">
                <h1 class="text-2xl font-bold text-white text-center">Bagikan Ceritamu <br> Bersama GALERAZ.</h1>
            </div>
            <div class="px-12 w-full flex flex-col gap-10">
                <div>
                    <h1 class="text-2xl font-bold mb-2">👋 Selamat Datang di<br>GALERAZ</h1>
                    <p class="text-base text-zinc-500">Silahkan login untuk mengakses GALERAZ.</p>
                </div>
                <form class="w-full" method="POST">
                    <div class="mb-5">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" placeholder="Masukkan Email" class="bg-zinc-200 w-full py-2 px-3 rounded-lg mt-2" required>
                    </div>
                    <div class="mb-5">
                        <label for="email">Password</label>
                        <input type="password" name="password" id="password" placeholder="Masukkan Password" class="bg-zinc-200 w-full py-2 px-3 rounded-lg mt-2" required>
                    </div>
                    <?php
                    if (isset($_SESSION['login_failed']) && $_SESSION['login_failed'] == 'email') {
                        echo '
                            <div class="flex items-center gap-2">
                                <img src="./assets/images/icon/alert.svg" alt="alert" class="w-6">
                                <p class="text-red-500 font-bold text-sm">Email yang anda masukkan tidak ditemukan</p>
                            </div>
                            ';
                    } else if (isset($_SESSION['login_failed']) && $_SESSION['login_failed'] == 'password') {
                        echo '
                            <div class="flex items-center gap-2">
                                <img src="./assets/images/icon/alert.svg" alt="alert" class="w-6">
                                <p class="text-red-500 font-bold text-sm">Password yang anda masukkan salah</p>
                            </div>
                        ';
                    }
                    ?>
                    <div class="w-full flex flex-col items-center gap-2 mt-5">
                        <button type="submit" name="login" value=true class="w-full bg-cyan-400 text-white font-bold py-2 rounded-lg hover:bg-cyan-500 transition-all">Login</button>
                        <span class=" text-sm text-zinc-500">Belum punya akun?</span>
                        <a href="./registrasi.php" type="submit" class="w-full text-cyan-400 font-bold py-2 rounded-lg text-center border-2 border-cyan-400 hover:bg-cyan-200 transition-all">Registrasi</a>
                    </div>
                </form>
            </div>
        </div>
        <div class="-z-10">
            <img src="./assets/images/BL.svg" alt="bl" class="absolute bottom-0 right-0">
            <img src="./assets/images/BR.svg" alt="br" class="absolute bottom-0 left-0">
            <img src="./assets/images/TL.png" alt="tl" class="absolute top-0 left-0">
            <img src="./assets/images/TR.svg" alt="tr" class="absolute top-0 right-0">
        </div>
    </div>
</body>

</html>