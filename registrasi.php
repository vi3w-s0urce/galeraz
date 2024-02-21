<?php 

include './koneksi.php';
include './middleware.php';

if (isset($_POST['registrasi'])) {
    $namaLengkap = htmlspecialchars($_POST['namaLengkap']);
    $username = htmlspecialchars($_POST['username']);
    $email = htmlspecialchars($_POST['email']);
    $alamat = htmlspecialchars($_POST['alamat']);
    $password = htmlspecialchars(password_hash($_POST['password'], PASSWORD_BCRYPT));
    
    $tambah_user = $conn->query("INSERT INTO user (namaLengkap, username, email, alamat, password) VALUES ('$namaLengkap', '$username', '$email', '$alamat', '$password')");

    if ($tambah_user) {
        $_SESSION['user_id'] = $conn->insert_id;
        $_SESSION['username'] = $username;
        $_SESSION['namaLengkap'] = $namaLengkap;
        $_SESSION['email'] = $email;
        $_SESSION['alamat'] = $alamat;
        header('Location: ./index.php');
    }
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/output.css">
    <title>Registrasi | GALERAZ</title>
</head>

<body>
    <div class="w-screen h-screen flex items-center justify-center">
        <div class="bg-white shadow-xl flex items-center p-8 gap-10 border-2 border-cyan-200 rounded-3xl">
            <div class="max-w-[680px] px-12 flex flex-col gap-10">
                <div>
                    <h1 class="text-2xl font-bold mb-2">✍️ Daftar dan Buat Akun<br>Baru</h1>
                    <p class="text-base text-zinc-500">Silahkan registrasi untuk membuat akun barumu.</p>
                </div>
                <form class="w-[600px]" method="POST">
                    <div class="mb-5">
                        <label for="namaLengkap">Nama Lengkap</label>
                        <input type="text" name="namaLengkap" id="namaLengkap" placeholder="Masukkan Nama Lengkap" class="bg-zinc-200 w-full py-2 px-3 rounded-lg mt-2" required>
                    </div>
                    <div class="flex gap-5 mb-5">
                        <div class="grow flex flex-col">
                            <label for="username">Username</label>
                            <input type="username" name="username" id="username" placeholder="Masukkan Username" class="bg-zinc-200 w-full py-2 px-3 rounded-lg mt-2" required>
                        </div>
                        <div class="grow flex flex-col">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" placeholder="Masukkan Email" class="bg-zinc-200 w-full py-2 px-3 rounded-lg mt-2" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="alamat">Alamat</label>
                        <textarea type="text" name="alamat" id="alamat" placeholder="Masukkan Alamat" class="bg-zinc-200 w-full py-2 px-3 rounded-lg mt-2 max-h-32" required></textarea>
                    </div>
                    <div class="mb-8">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" placeholder="Masukkan Password" class="bg-zinc-200 w-full py-2 px-3 rounded-lg mt-2" required>
                    </div>
                    <div class="w-full flex flex-col items-center gap-2">
                        <button type="submit" name="registrasi" value=true class="w-full bg-cyan-400 text-white font-bold py-2 rounded-lg hover:bg-cyan-500 transition-all">Registrasi</button>
                    </div>
                </form>
            </div>
            <div class="bg-cyan-400 h-[700px] px-20 rounded-2xl flex flex-col items-center justify-center gap-8 relative">
                <img src="./assets/images/logo.svg" alt="logo" class="bg-white p-2 rounded-xl absolute top-0 left-0 m-5">
                <img src="./assets/images/signin_illustration.svg" alt="bl" class="min-w-96">
                <h1 class="text-2xl font-bold text-white text-center">Petualangan Baru<br>dengan GALERAZ.</h1>
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