<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./assets/css/style.css">
    <title><?= $title ?></title>
</head>

<body>
    <header class="m-5 mb-10 p-5 border-2 border-cyan-200 rounded-xl flex justify-between sticky top-5 z-50 backdrop-blur-lg bg-opacity-80 bg-white">
        <img src="./assets/images/logo.svg" alt="logo" class="w-32">
        <div class="absolute flex gap-8 items-center left-1/2 -translate-x-1/2">
            <a href="./index.php">
                <img src="./assets/images/icon/home<?= ($_SESSION['pageRoute'] == 'home') ? '_blue' : null ?>.svg" alt="home-icon" class="<?= ($_SESSION['pageRoute'] == 'home') ? 'bg-cyan-200 p-1 rounded-xl' : 'p-1 hover:bg-zinc-200 rounded-xl transition-all' ?>">
            </a>
            <a href="./tambah_foto.php"><img src="./assets/images/icon/plus<?= ($_SESSION['pageRoute'] == 'addFoto') ? '_white' : null ?>.svg" alt="plus-icon" class="<?= ($_SESSION['pageRoute'] == 'addFoto') ? 'p-1 bg-cyan-400 rounded-xl' : 'p-1 border-2 border-cyan-400 rounded-xl hover:bg-zinc-200 transition-all' ?>"></a>
            <a href="./album.php"><img src="./assets/images/icon/album<?= ($_SESSION['pageRoute'] == 'album') ? '_blue' : null ?>.svg" alt="album-icon" class="<?= ($_SESSION['pageRoute'] == 'album') ? 'bg-cyan-200 p-1 rounded-xl' : 'p-1 hover:bg-zinc-200 rounded-xl transition-all' ?>"></a>
        </div>
        <div class="flex gap-2 items-center">
            <a href="./profile.php" class="flex items-center gap-2 pr-5 pl-2 rounded-xl <?= ($_SESSION['pageRoute'] == 'profile') ? 'bg-cyan-200' : 'hover:bg-zinc-200 transition-all' ?>">
                <img src="./assets/images/icon/user<?= ($_SESSION['pageRoute'] == 'profile') ? '_blue' : null ?>.svg" alt="user-icon" class="w-14">
                <div class="flex flex-col <?= ($_SESSION['pageRoute'] == 'profile') ? 'text-cyan-500' : null ?>">
                    <p class="font-bold"><?= $_SESSION['namaLengkap'] ?></p>
                    <p class="text-sm text-zinc-500"><?= $_SESSION['username'] ?></p>
                </div>
            </a>
            <a href="./logout.php" class="hover:bg-red-200 rounded-xl px-2 py-1 transition-all"><img src="./assets/images/icon/logout.svg" alt="logout-icon"></a>
        </div>
    </header>