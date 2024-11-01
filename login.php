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
    <link rel="stylesheet" href="./assets/css/style.css">
    <title>Login | GALERAZ</title>
</head>

<body>
    <!-- MODAL DEMO -->
    <div class="h-[100dvh] w-full fixed z-[9999] flex justify-center items-center backdrop-blur-[3px] px-4" id="demo-modal">
        <div class='bg-white border-[#999999] border-2 rounded-[16px] p-6 w-fit max-w-[800px] flex flex-col xl:flex-row gap-6 shadow-2xl'>
            <div class='flex flex-col w-full xl:items-start xl:w-[50%]'>
                <div class='flex items-center gap-3'>
                    <h1 class='text-[46px]'>👋</h1>
                    <div class='flex flex-col'>
                        <h1 class='font-mondwest text-[#333] text-2xl'>Galeraz</h1>
                        <a href="https://galeraz.viewsource.work" class='text-[#777] font-mondwest'>
                            https://galeraz.viewsource.work/
                        </a>
                    </div>
                </div>
                <p class='text-[#333] text-xs font-hack font-bold mt-3 indent-4'>
                    Hello Visitor! Welcome to one of my dummy projects. Dummy project is a project that i made to train my skills or just for my school task. I’ve hosted it to provide a preview for you and to showcase it in my portfolio. Enjoy!
                </p>
                <div class='flex gap-6 mt-6'>
                    <div class='flex flex-col gap-2'>
                        <p class='text-[#333] text-base font-mondwest'>Made With</p>
                        <div class='flex items-center gap-3'>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-php">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M12 12m-10 0a10 9 0 1 0 20 0a10 9 0 1 0 -20 0" />
                                <path d="M5.5 15l.395 -1.974l.605 -3.026h1.32a1 1 0 0 1 .986 1.164l-.167 1a1 1 0 0 1 -.986 .836h-1.653" />
                                <path d="M15.5 15l.395 -1.974l.605 -3.026h1.32a1 1 0 0 1 .986 1.164l-.167 1a1 1 0 0 1 -.986 .836h-1.653" />
                                <path d="M12 7.5l-1 5.5" />
                                <path d="M11.6 10h2.4l-.5 3" />
                            </svg>
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-brand-tailwind">
                                <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                                <path d="M11.667 6c-2.49 0 -4.044 1.222 -4.667 3.667c.933 -1.223 2.023 -1.68 3.267 -1.375c.71 .174 1.217 .68 1.778 1.24c.916 .912 2 1.968 4.288 1.968c2.49 0 4.044 -1.222 4.667 -3.667c-.933 1.223 -2.023 1.68 -3.267 1.375c-.71 -.174 -1.217 -.68 -1.778 -1.24c-.916 -.912 -1.975 -1.968 -4.288 -1.968zm-4 6.5c-2.49 0 -4.044 1.222 -4.667 3.667c.933 -1.223 2.023 -1.68 3.267 -1.375c.71 .174 1.217 .68 1.778 1.24c.916 .912 1.975 1.968 4.288 1.968c2.49 0 4.044 -1.222 4.667 -3.667c-.933 1.223 -2.023 1.68 -3.267 1.375c-.71 -.174 -1.217 -.68 -1.778 -1.24c-.916 -.912 -1.975 -1.968 -4.288 -1.968z" />
                            </svg>
                        </div>
                    </div>
                    <div class='flex flex-col gap-2'>
                        <p class='text-[#333] text-base font-mondwest'>By viewsource</p>
                        <div class='flex flex-col gap-3'>
                            <div class='flex items-center gap-2 group cursor-pointer' onclick="window.open('https://github.com/vi3w-s0urce')">
                                <svg
                                    stroke="currentColor"
                                    fill="currentColor"
                                    stroke-width="0"
                                    viewBox="0 0 24 24"
                                    class="text-[#333] text-[24px]"
                                    height="1em"
                                    width="1em"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12.001 2C6.47598 2 2.00098 6.475 2.00098 12C2.00098 16.425 4.86348 20.1625 8.83848 21.4875C9.33848 21.575 9.52598 21.275 9.52598 21.0125C9.52598 20.775 9.51348 19.9875 9.51348 19.15C7.00098 19.6125 6.35098 18.5375 6.15098 17.975C6.03848 17.6875 5.55098 16.8 5.12598 16.5625C4.77598 16.375 4.27598 15.9125 5.11348 15.9C5.90098 15.8875 6.46348 16.625 6.65098 16.925C7.55098 18.4375 8.98848 18.0125 9.56348 17.75C9.65098 17.1 9.91348 16.6625 10.201 16.4125C7.97598 16.1625 5.65098 15.3 5.65098 11.475C5.65098 10.3875 6.03848 9.4875 6.67598 8.7875C6.57598 8.5375 6.22598 7.5125 6.77598 6.1375C6.77598 6.1375 7.61348 5.875 9.52598 7.1625C10.326 6.9375 11.176 6.825 12.026 6.825C12.876 6.825 13.726 6.9375 14.526 7.1625C16.4385 5.8625 17.276 6.1375 17.276 6.1375C17.826 7.5125 17.476 8.5375 17.376 8.7875C18.0135 9.4875 18.401 10.375 18.401 11.475C18.401 15.3125 16.0635 16.1625 13.8385 16.4125C14.201 16.725 14.5135 17.325 14.5135 18.2625C14.5135 19.6 14.501 20.675 14.501 21.0125C14.501 21.275 14.6885 21.5875 15.1885 21.4875C19.259 20.1133 21.9999 16.2963 22.001 12C22.001 6.475 17.526 2 12.001 2Z"></path>
                                </svg>
                                <p class='text-[#333] text-[10px] sm:text-xs font-hack group-hover:text-[#0000ff]'>github.com/vi3w-s0urce</p>
                            </div>
                            <div class='flex items-center gap-2 group cursor-pointer' onclick="window.open('https://linkedin.com/in/vi3w-s0urce')">
                                <svg
                                    stroke="currentColor"
                                    fill="currentColor"
                                    stroke-width="0"
                                    viewBox="0 0 448 512"
                                    class="text-[#333] text-[24px]"
                                    height="1em"
                                    width="1em"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M416 32H31.9C14.3 32 0 46.5 0 64.3v383.4C0 465.5 14.3 480 31.9 480H416c17.6 0 32-14.5 32-32.3V64.3c0-17.8-14.4-32.3-32-32.3zM135.4 416H69V202.2h66.5V416zm-33.2-243c-21.3 0-38.5-17.3-38.5-38.5S80.9 96 102.2 96c21.2 0 38.5 17.3 38.5 38.5 0 21.3-17.2 38.5-38.5 38.5zm282.1 243h-66.4V312c0-24.8-.5-56.7-34.5-56.7-34.6 0-39.9 27-39.9 54.9V416h-66.4V202.2h63.7v29.2h.9c8.9-16.8 30.6-34.5 62.9-34.5 67.2 0 79.7 44.3 79.7 101.9V416z"></path>
                                </svg>
                                <p class='text-[#333] text-[10px] sm:text-xs font-hack group-hover:text-[#0000ff]'>linkedin.com/in/vi3w-s0urce</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class='w-full xl:w-[50%] flex flex-col gap-2'>
                <p class='font-mondwest text-[#333] text-base'>How to use this website:</p>
                <div class='py-3 px-4 flex flex-col gap-2 bg-[#e0e0e0]'>
                    <p class="text-xs font-hack">
                        You can register (sign up) in this page:
                        <br>
                        <br>
                        <a href="./registrasi.php" class="text-[#0000ff] font-bold">
                            galeraz.viewsource.work/registrasi.php
                        </a>
                        <br>
                        <br>
                        <br>
                        or you can login as visitor with this credentials:
                        <br>
                        <br>
                        email: visitor@example.com
                        <br>
                        password: visitor
                    </p>
                </div>
                <Button class='min-w-0 w-full h-fit px-6 py-3 text-white font-hack font-bold mt-1 rounded-none bg-[#00FF00] hover:bg-[#00aa00] text-base' onclick="closeDemoModal()">
                    ENJOY!
                </Button>
            </div>
        </div>
    </div>

    <div class="w-screen h-screen flex items-center justify-center">
        <div class="shadow-xl flex items-center p-8 gap-10 border-2 border-cyan-200 rounded-3xl bg-white max-w-[992px] w-full mx-12">
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

    <script>
        const demoModal = document.getElementById("demo-modal");
        
        function closeDemoModal() {
            demoModal.remove()
        }
    </script>
</body>

</html>