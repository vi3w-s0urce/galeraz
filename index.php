<?php

include './koneksi.php';
include './middleware.php';

$userID = $_SESSION['user_id'];
$data_foto = $conn->query("SELECT foto.*, user.username,
                            (SELECT COUNT(likeID) FROM likefoto WHERE likefoto.fotoID=foto.fotoID) AS totalLike,
                            (SELECT COUNT(komentarID) FROM komentarfoto WHERE komentarfoto.fotoID=foto.fotoID) AS totalKomentar,
                            (SELECT GROUP_CONCAT(likefoto.userID SEPARATOR ', ') FROM likefoto WHERE likefoto.fotoID=foto.fotoID) AS checkLike
                            FROM foto 
                            LEFT JOIN user ON foto.userID = user.userID
                            GROUP BY foto.fotoID")
    ->fetch_all(MYSQLI_ASSOC);

$_SESSION['pageRoute'] = 'home';
$title = 'Beranda | Galeraz';
include './header.php';
?>
    <main class="mt-5">
        <section class="text-center flex flex-col items-center justify-center gap-2">
            <h1 class="font-bold text-4xl relative">GALERAZ</h1>
            <h1 class="text-zinc-500">Beranda</h1>
        </section>
        <section class="m-5 columns-4 gap-10">
            <?php foreach ($data_foto as $foto) {
            ?>
                <div class="break-inside-avoid mb-6 group/item" onclick="window.location.href = './show_foto.php?<?= 'fotoID=' . $foto['fotoID'] ?>'">
                    <div class="relative overflow-hidden rounded-xl cursor-pointer shadow-xl">
                        <img src=<?= $foto['lokasiFile'] ?> class="w-full bg-gray-400 object-cover">
                        <div class="absolute -bottom-1/2 w-full p-5 bg-gradient-to-t from-zinc-800 to-transparent group-hover/item:-bottom-0 transition-all">
                            <span class="font-bold text-2xl text-white"><?= $foto['judulFoto'] ?></span>
                        </div>
                    </div>
                    <div class="flex gap-2 items-center mt-3 justify-between">
                        <div class="flex items-center gap-2">
                            <img src="./assets/images/icon/user.svg" alt="user-icon" class="w-8">
                            <p><?= $foto['username'] ?></p>
                        </div>
                        <div class="flex gap-5">
                            <div class="flex items-center gap-2">
                                <form action="POST">

                                    <?php 
                                    $checkLikes = ($foto['checkLike'] != NULL) ? explode(',' , $foto['checkLike'],) : NULL;
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
                                <p class="font-bold"><?= $foto['totalLike'] ?></p>
                            </div>
                            <div class="flex items-center gap-2">
                                <img src="./assets/images/icon/comment.svg" alt="like" class="w-6">
                                <p class="font-bold"><?= $foto['totalKomentar'] ?></p>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>
        </section>
    </main>
</body>

</html>