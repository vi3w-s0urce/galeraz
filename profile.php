<?php

include './koneksi.php';
include './middleware.php';

$not_my_account = false;

if (isset($_GET['userID'])) {
    if ($_GET['userID'] != $_SESSION['user_id']) {
        $not_my_account = true;
    }
}

$userID = (isset($_GET['userID'])) ? $_GET['userID'] : $_SESSION['user_id'];
$favoritePage = isset($_GET['favorite']) ? $_GET['favorite'] : false;

$profile = $conn->query("SELECT * FROM user WHERE userID='$userID'")->fetch_assoc();

$fotos = $conn->query("SELECT foto.*,
                            (SELECT COUNT(likeID) FROM likefoto WHERE likefoto.fotoID=foto.fotoID) AS totalLike,
                            (SELECT COUNT(komentarID) FROM komentarfoto WHERE komentarfoto.fotoID=foto.fotoID) AS totalKomentar
                            FROM foto 
                            WHERE foto.userID='$userID'")
    ->fetch_all(MYSQLI_ASSOC);

$favoriteFotos = $conn->query("SELECT foto.*, user.username, likefoto.*,
                            (SELECT COUNT(likeID) FROM likefoto WHERE likefoto.fotoID=foto.fotoID) AS totalLike,
                            (SELECT COUNT(komentarID) FROM komentarfoto WHERE komentarfoto.fotoID=foto.fotoID) AS totalKomentar,
                            (SELECT GROUP_CONCAT(likefoto.userID SEPARATOR ', ') FROM likefoto WHERE likefoto.fotoID=foto.fotoID) AS checkLike
                            FROM foto
                            INNER JOIN likefoto ON likefoto.fotoID=foto.fotoID
                            LEFT JOIN user ON foto.userID = user.userID
                            WHERE likefoto.userID='$userID'
                            GROUP BY foto.fotoID")
    ->fetch_all(MYSQLI_ASSOC);

if (isset($_POST['delete_foto'])) {
    $fotoID = $_POST['delete_foto'];

    $lokasiFile = $conn->query("SELECT lokasiFile from foto WHERE fotoID='$fotoID' AND userID='$userID'")->fetch_column();

    if (file_exists($lokasiFile)) {
        unlink($lokasiFile);
    }

    $deleteFoto = $conn->query("DELETE FROM foto WHERE fotoID='$fotoID' AND userID='$userID'");

    if ($deleteFoto) {
        header("Refresh:0");
    }
}

$_SESSION['pageRoute'] = 'profile';
$title = 'Profile | Galeraz';
include './header.php';

?>
<main class="m-5">
    <section class="flex flex-col items-center justify-center">
        <div class="flex flex-col items-center">
            <img src="./assets/images/icon/user.svg" alt="user-icon" class="w-20">
            <p class="text-2xl font-bold"><?= $profile['namaLengkap'] ?></p>
        </div>
        <div class="flex gap-5 items-center mt-2">
            <div class="flex items-center gap-1 text-zinc-500"><img src="./assets/images/icon/at.svg" alt="at-icon" class="w-6"><?= $profile['username'] ?></div>
            <div class="flex items-center gap-1 text-zinc-500"><img src="./assets/images/icon/mail.svg" alt="mail-icon" class="w-6"><?= $profile['email'] ?></div>
            <div class="flex items-center gap-1 text-zinc-500"><img src="./assets/images/icon/map.svg" alt="map-icon" class="w-6"><?= $profile['alamat'] ?></div>
        </div>
    </section>
    <div class="flex items-center justify-center mt-5">
        <div class="border-2 rounded-full py-1 px-1 flex gap-1">
            <a href="<?= ($not_my_account) ? '#' : './profile.php' ?>" class="p-2 px-10 rounded-full font-bold <?= ($favoritePage) ? 'text-zinc-500 hover:bg-zinc-200 transition-all' : 'bg-cyan-200 text-cyan-500' ?> <?= ($not_my_account) ? ' px-20' : null ?>">Postingan</a>
            <a href="?favorite=true" class="p-2 px-10 rounded-full font-bold <?= ($favoritePage) ? 'bg-cyan-200 text-cyan-500' : 'text-zinc-500 hover:bg-zinc-200 transition-all' ?> <?= ($not_my_account) ? 'hidden' : null ?>">Favorite</a>
        </div>
    </div>
    <?php if (!$favoritePage) { ?>
        <section class="my-5 columns-4 gap-10">
            <?php foreach ($fotos as $foto) {
            ?>
                <div class="break-inside-avoid mb-6 group/item" onclick="window.location.href = './show_foto.php?<?= 'fotoID=' . $foto['fotoID'] ?>'">
                    <div class="relative overflow-hidden rounded-xl cursor-pointer shadow-xl">
                        <img src=<?= $foto['lokasiFile'] ?> class="w-full bg-gray-400 object-cover">
                        <div class="absolute -bottom-1/2 w-full p-5 bg-gradient-to-t from-zinc-800 to-transparent group-hover/item:-bottom-0 transition-all">
                            <span class="font-bold text-2xl text-white"><?= $foto['judulFoto'] ?></span>
                        </div>
                    </div>
                    <div class="flex gap-2 items-center mt-3 justify-between <?= ($not_my_account) ? 'justify-start' : null ?>">
                        <div class="flex items-center gap-2 <?= ($not_my_account) ? 'hidden' : null ?>">
                            <a href="./edit_foto.php?fotoID=<?= $foto['fotoID'] ?>"><img src="./assets/images/icon/edit.svg" alt="edit" class="w-10 cursor-pointer p-1 rounded-lg hover:bg-cyan-200 transition-all"></a>
                            <img src="./assets/images/icon/trash.svg" alt="trash" class="w-10 cursor-pointer p-1 rounded-lg hover:bg-red-200 transition-all" onclick="OpenDeleteFotoModal(event, <?= $foto['fotoID'] ?>)">
                        </div>
                        <div class="flex gap-5">
                            <div class="flex items-center gap-2">
                                <img src="./assets/images/icon/love_gray.svg" alt="like" class="w-6">
                                <p class="font-bold text-zinc-500"><?= $foto['totalLike'] ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <img src="./assets/images/icon/comment_gray.svg" alt="comment" class="w-6">
                                <p class="font-bold text-zinc-500"><?= $foto['totalKomentar'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
                <div id="modalFoto<?= $foto['fotoID'] ?>" class="fixed h-screen w-screen bg-black top-0 left-0 z-50 bg-opacity-20 backdrop-blur-sm hidden items-center justify-center">
                    <div class="bg-white flex flex-col items-center p-5 shadow-xl rounded-xl gap-3">
                        <img src="./assets/images/icon/alert.svg" alt="alert-icon" class="w-20">
                        <div class="flex flex-col items-center gap-1">
                            <h1 class="text-2xl font-bold">Hapus Foto</h1>
                            <p class="text-slate-500 text-center">Apakah anda yakin ingin menghapus<br>foto ini?</p>
                        </div>
                        <form method="POST" class="flex gap-3 w-full mt-2">
                            <button class="grow text-white bg-zinc-500 p-2 rounded-lg" onclick="CloseDeleteFotoModal(event, <?= $foto['fotoID'] ?>)">Batal</button>
                            <button type="submit" class="grow text-white bg-red-400 p-2 rounded-lg" name="delete_foto" value="<?= $foto['fotoID'] ?>">Hapus</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
        </section>
    <?php } else { ?>
        <section class="m-5 columns-4 gap-10">
            <?php foreach ($favoriteFotos as $favoriteFoto) {
            ?>
                <div class="break-inside-avoid mb-6 group/item" onclick="window.location.href = './show_foto.php?<?= 'fotoID=' . $favoriteFoto['fotoID'] ?>'">
                    <div class="relative overflow-hidden rounded-xl cursor-pointer shadow-xl">
                        <img src=<?= $favoriteFoto['lokasiFile'] ?> class="w-full bg-gray-400 object-cover">
                        <div class="absolute -bottom-1/2 w-full p-5 bg-gradient-to-t from-zinc-800 to-transparent group-hover/item:-bottom-0 transition-all">
                            <span class="font-bold text-2xl text-white"><?= $favoriteFoto['judulFoto'] ?></span>
                        </div>
                    </div>
                    <div class="flex gap-2 items-center mt-3 justify-between">
                        <div class="flex items-center gap-2">
                            <img src="./assets/images/icon/user.svg" alt="user-icon" class="w-8">
                            <p><?= $favoriteFoto['username'] ?></p>
                        </div>
                        <div class="flex gap-5">
                            <div class="flex items-center gap-2">
                                <form action="POST">

                                    <?php
                                    $checkLikes = ($favoriteFoto['checkLike'] != NULL) ? explode(',', $favoriteFoto['checkLike'],) : NULL;
                                    $is_fotoLike = false;
                                    if ($checkLikes) {
                                        foreach ($checkLikes as $checkLike) {
                                            if ($checkLike == $userID) {
                                                $is_fotoLike = true;
                                                break;
                                            }
                                        }
                                    }

                                    if ($is_fotoLike) {
                                        echo "<img src='./assets/images/icon/love_filled.svg' alt='like' class='w-6'>";
                                    } else {
                                        echo "<img src='./assets/images/icon/love.svg' alt='like' class='w-6'>";
                                    }
                                    ?>
                                </form>
                                <p class="font-bold"><?= $favoriteFoto['totalLike'] ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <img src="./assets/images/icon/comment.svg" alt="like" class="w-6">
                                <p class="font-bold"><?= $favoriteFoto['totalKomentar'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </section>
    <?php } ?>
</main>
<script>
    function OpenDeleteFotoModal(event, fotoID) {
        event.stopPropagation();
        let modal = document.getElementById('modalFoto' + fotoID);
        modal.classList.add("flex");
        modal.classList.remove("hidden");
    }

    function CloseDeleteFotoModal(event, fotoID) {
        event.stopPropagation();
        let modal = document.getElementById('modalFoto' + fotoID);
        modal.classList.add("hidden");
        modal.classList.remove("flex");
    }
</script>
</body>

</html>