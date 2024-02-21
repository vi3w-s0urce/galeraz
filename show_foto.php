<?php

include './koneksi.php';
include './middleware.php';

if (!isset($_GET['fotoID'])) {
    header('Location: ./index.php');
}

$fotoID = $_GET['fotoID'];
$userID = $_SESSION['user_id'];
$is_fotoLike = false;

$foto = $conn->query("SELECT foto.*, user.username,
                        (SELECT COUNT(likeID) FROM likefoto WHERE fotoID='$fotoID') AS totalLike,
                        (SELECT COUNT(komentarID) FROM komentarfoto WHERE fotoID='$fotoID') AS totalKomentar
                        FROM foto
                        LEFT JOIN user ON foto.userID = user.userID 
                        WHERE foto.fotoID='$fotoID' 
                        GROUP BY foto.fotoID")
    ->fetch_assoc();

$like = $conn->query("SELECT likefoto.userID
                        FROM likefoto
                        WHERE fotoID='$fotoID'")
    ->fetch_all(MYSQLI_ASSOC);

$komentars = $conn->query("SELECT komentarfoto.* , user.username
                            FROM komentarfoto 
                            LEFT JOIN user ON komentarfoto.userID = user.userID
                            WHERE fotoID='$fotoID'")
    ->fetch_all(MYSQLI_ASSOC);

foreach ($like as $checklike) {
    if ($checklike['userID'] == $userID) {
        $is_fotoLike = true;
        break;
    }
}

if (isset($_POST['submit_like'])) {
    if (!$is_fotoLike) {
        $tanggalLike = date("Y-m-d");
        $conn->query("INSERT INTO likefoto (fotoID, userID, tanggalLike) VALUES ('$fotoID', '$userID', '$tanggalLike')");
        header("Refresh:0");
    } else {
        $conn->query("DELETE FROM likefoto WHERE userID='$userID' AND fotoID='$fotoID'");
        header("Refresh:0");
    }
}

if (isset($_POST['submit_komentar'])) {
    $isiKomentar = $_POST['komentar'];
    $tanggalKomentar = date("Y-m-d");

    $createKomentar = $conn->query("INSERT INTO komentarfoto (fotoID, userID, isiKomentar, tanggalKomentar) VALUES ('$fotoID', '$userID', '$isiKomentar', '$tanggalKomentar')");

    if ($createKomentar) {
        header('Refresh:0');
    }
}

if (isset($_POST['delete_komentar'])) {
    $komentarID = $_POST['delete_komentar'];
    $deleteKomentar = $conn->query("DELETE FROM komentarfoto WHERE komentarID='$komentarID' AND userID='$userID'");

    if ($deleteKomentar) {
        header('Refresh:0');
    }
}

$_SESSION['pageRoute'] = null;
$title = 'Foto | Galeraz';
include './header.php';

?>
    <main class="mt-5 mb-10">
        <section class="px-96 mx-10">
            <div class="flex justify-between items-center mb-5">
                <div>
                    <h1 class="text-2xl font-bold mb-2"><?= $foto['judulFoto'] ?></h1>
                    <div class="flex gap-5 items-center">
                        <a href="./profile.php?userID=<?= $foto['userID'] ?>" class="flex gap-2 items-center">
                            <img src="./assets/images/icon/user_gray.svg" alt="user-icon" class="w-6"><span class="text-zinc-500"><?= $foto['username'] ?></span>
                        </a>
                        <div class="flex gap-2 items-center">
                            <img src="./assets/images/icon/calender.svg" alt="user-icon" class="w-6"><span class="text-zinc-500"><?= date('d F Y', strtotime($foto['tanggalUnggah'])) ?></span>
                        </div>
                    </div>
                </div>
                <form method="POST" class="h-fit">
                    <button type="submit" name="submit_like" class="<?= $is_fotoLike ? 'hover:bg-zinc-200 rounded-full p-2 transition-all' : 'hover:bg-red-200 rounded-full p-2 transition-all' ?>"><img src="<?= $is_fotoLike ? './assets/images/icon/love_filled.svg' : './assets/images/icon/love.svg' ?>" alt="love-icon"></button>
                </form>
            </div>
            <div class="flex justify-center">
                <img src="<?= $foto['lokasiFile'] ?>" class="max-h-screen w-full object-cover rounded-xl shadow-xl">
            </div>
            <div class="mt-5 w-full">
                <p><?= $foto['deskripsiFoto'] ?></p>
                <div class="flex flex-row-reverse w-full gap-5">
                    <div class="flex items-center gap-2">
                        <img src="./assets/images/icon/comment_gray.svg" alt="comment-icon" class="w-6">
                        <p class="text-zinc-500"><?= $foto['totalKomentar'] ?></p>
                    </div>
                    <div class="flex items-center gap-2">
                        <img src="./assets/images/icon/love_gray.svg" alt="love-icon" class="w-6">
                        <p class="text-zinc-500"><?= $foto['totalLike'] ?></p>
                    </div>
                </div>
            </div>
            <section class="w-full mt-2">
                <div class="flex items-center gap-2">
                    <img src="./assets/images/icon/comment.svg" alt="comment-icon" class="w-8">
                    <p class="text-xl font-bold">Comments</p>
                </div>
                <div class="flex flex-col mt-3 gap-2">
                    <form method="POST" class="flex gap-2">
                        <input type="text" name="komentar" class="grow bg-zinc-200 rounded-xl p-3" placeholder="Berikan comment di postingan ini">
                        <button type="submit" name="submit_komentar" class="bg-cyan-400 rounded-xl"><img src="./assets/images/icon/arrow_right.svg" class="w-14 p-3"></button>
                    </form>
                    <?php foreach ($komentars as $komentar) { ?>
                        <div class="border-2 w-full p-3 rounded-xl <?= ($komentar['userID'] == $userID) ? 'border-cyan-200' : null ?>">
                            <div class="flex justify-between items-center">
                                <div class="flex items-center gap-2">
                                    <img src="./assets/images/icon/user.svg" alt="user-icon" class="w-12">
                                    <div class="flex flex-col">
                                        <p class="font-bold"><?= $komentar['username'] ?></p>
                                        <p class="text-sm text-zinc-500"><?= date('d F Y', strtotime($komentar['tanggalKomentar'])) ?></p>
                                    </div>
                                </div>
                                <img src="./assets/images/icon/trash.svg" alt="trash-icon" class="w-10 <?= ($komentar['userID'] != $userID) ? 'hidden' : null ?> hover:bg-red-200 rounded-xl p-1 transition-all cursor-pointer" onclick="OpenDeleteKomentarModal(event, <?= $komentar['komentarID'] ?>)"></button>
                            </div>
                            <div class="mx-14 mt-1"><?= $komentar['IsiKomentar'] ?></div>
                        </div>
                        <div id="modalDeleteKomentar<?= $komentar['komentarID'] ?>" class="fixed h-screen w-screen bg-black top-0 left-0 z-50 bg-opacity-20 backdrop-blur-sm hidden items-center justify-center">
                            <div class="bg-white flex flex-col items-center p-5 shadow-xl rounded-xl gap-3">
                                <img src="./assets/images/icon/alert.svg" alt="alert-icon" class="w-20">
                                <div class="flex flex-col items-center gap-1">
                                    <h1 class="text-2xl font-bold">Hapus Komentar</h1>
                                    <p class="text-slate-500 text-center">Apakah anda yakin untuk menghapus <br> komentar ini? </p>
                                </div>
                                <form method="POST" class="flex gap-3 w-full mt-2">
                                    <button class="grow text-white bg-zinc-500 p-2 rounded-lg" onclick="CloseDeleteKomentarModal(event, <?= $komnetar['komentarID'] ?>)">Batal</button>
                                    <button type="submit" class="grow text-white bg-red-400 p-2 rounded-lg" name="delete_komentar" value="<?= $komentar['komentarID'] ?>">Hapus</button>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            </section>
        </section>
    </main>
    <script>
        function OpenDeleteKomentarModal(event, albumID) {
            event.stopPropagation();
            let modal = document.getElementById('modalDeleteKomentar' + albumID);
            modal.classList.add("flex");
            modal.classList.remove("hidden");
        }

        function CloseDeleteKomentarModal(event, albumID) {
            event.stopPropagation();
            let modal = document.getElementById('modalDeleteKomentar' + albumID);
            modal.classList.add("hidden");
            modal.classList.remove("flex");
        }
    </script>
</body>

</html>