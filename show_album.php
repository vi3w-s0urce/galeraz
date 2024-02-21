<?php

include './koneksi.php';
include './middleware.php';

if (!isset($_GET['albumID'])) {
    header('Location: ./album.php');
}

$userID = $_SESSION['user_id'];
$albumID = $_GET['albumID'];

$album = $conn->query("SELECT * FROM album WHERE albumID='$albumID' AND userID='$userID'")->fetch_assoc();
$fotos = $conn->query("SELECT foto.*,
                        (SELECT COUNT(likeID) FROM likefoto WHERE likefoto.fotoID=foto.fotoID) AS totalLike,
                        (SELECT COUNT(komentarID) FROM komentarfoto WHERE komentarfoto.fotoID=foto.fotoID) AS totalKomentar
                        FROM foto 
                        WHERE foto.albumID='$albumID' AND foto.userID='$userID'
                        GROUP BY foto.fotoID")
    ->fetch_all(MYSQLI_ASSOC);;

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

$_SESSION['pageRoute'] = 'album';
$title = 'Album | Galeraz';
include './header.php';

?>
    <main class="m-5">
        <section class="text-center flex flex-col items-center justify-center gap-2">
            <h1 class="font-bold text-4xl relative"><?= $album['namaAlbum'] ?> <span class="bg-zinc-200 text-zinc-500 px-2 text-xl rounded-lg"><?= count($fotos) ?></span></h1>
            <h1 class="text-zinc-500"><?= $album['deskripsi'] ?></h1>
        </section>
        <section class="m-5 columns-4 gap-10">
            <a href="./tambah_foto.php" class="break-inside-avoid shadow-xl rounded-xl min-h-72 p-5 mb-6 flex flex-col items-center justify-center border-2 border-cyan-400 cursor-pointer">
                <img src="./assets/images/icon/plus_circle.svg" alt="plus_circle_icon" class="w-16">
                <p class="font-bold text-xl text-cyan-400 mt-5">Tambah Foto</p>
            </a>
            <?php foreach ($fotos as $foto) { ?>
                <div class="break-inside-avoid mb-6 group/item" onclick="window.location.href = './show_foto.php?fotoID=<?= $foto['fotoID'] ?>'">
                    <div class="relative overflow-hidden rounded-xl cursor-pointer shadow-xl">
                        <img src=<?= $foto['lokasiFile'] ?> class="w-full bg-gray-400 object-cover">
                        <div class="absolute -bottom-1/2 w-full p-5 bg-gradient-to-t from-zinc-800 to-transparent group-hover/item:-bottom-0 transition-all">
                            <span class="font-bold text-2xl text-white"><?= $foto['judulFoto'] ?></span>
                        </div>
                    </div>
                    <div class="flex gap-2 items-center mt-3 justify-between">
                        <div class="flex items-center gap-2">
                            <a href="./edit_foto.php?fotoID=<?=$foto['fotoID']?>"><img src="./assets/images/icon/edit.svg" alt="edit" class="w-10 cursor-pointer p-1 rounded-lg hover:bg-cyan-200 transition-all"></a>
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
                </div>
            <?php } ?>
        </section>
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