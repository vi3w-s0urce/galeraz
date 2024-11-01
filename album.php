<?php

include './koneksi.php';
include './middleware.php';

$userID = $_SESSION['user_id'];

$albums = $conn->query("SELECT album.*, 
                        GROUP_CONCAT(foto.lokasiFile SEPARATOR ', ') AS fotos,
                        (SELECT COUNT(fotoID) FROM foto WHERE foto.albumID=album.albumID) AS totalFoto
                        FROM album
                        LEFT JOIN foto ON album.albumID = foto.albumID
                        WHERE album.userID='$userID'
                        GROUP BY album.albumID
                        ")
    ->fetch_all(MYSQLI_ASSOC);

if (isset($_POST['delete_album'])) {
    $albumID = $_POST['delete_album'];
    $delete_album = $conn->query("DELETE FROM album WHERE albumID='$albumID' AND userID='$userID'");
    if ($delete_album) {
        header('Refresh:0');
    }
}

$_SESSION['pageRoute'] = 'album';
$title = 'Album | Galeraz';
include './header.php';

?>
    <main class="mt-5">
        <section class="text-center flex flex-col items-center justify-center gap-2">
            <h1 class="font-bold text-4xl relative">Album</h1>
            <h1 class="text-zinc-500">Semua album yang anda buat</h1>
        </section>
        <section class="m-5 grid grid-cols-4 gap-10">
            <a href="./tambah_album.php" class="shadow-xl rounded-xl min-h-96 p-5 flex flex-col items-center justify-center border-2 border-cyan-400 cursor-pointer">
                <img src="./assets/images/icon/plus_circle.svg" alt="plus_circle_icon" class="w-16">
                <p class="font-bold text-xl text-cyan-400 mt-5">Tambah Album Baru</p>
            </a>
            <?php foreach ($albums as $album) { ?>
                <div class="shadow-xl rounded-xl p-5 flex flex-col gap-4 cursor-pointer" onclick="window.location.href = './show_album.php?albumID=<?= $album['albumID'] ?>'">
                    <?php
                    if (isset($album['fotos'])) {
                        echo "<div class='grid grid-cols-2 grid-rows-2 rounded-lg overflow-hidden min-h-72'>";
                        $foto = explode(',', $album['fotos']);
                        if (count($foto) >= 4) {
                            echo "<img src='$foto[0]' class='h-full w-full object-cover'>";
                            echo "<img src='$foto[1]' class='h-full w-full object-cover'>";
                            echo "<img src='$foto[2]' class='h-full w-full object-cover'>";
                            echo "<img src='$foto[3]' class='h-full w-full object-cover'>";
                        } else {
                            echo "<img src='$foto[0]' class='h-full w-full object-cover col-span-2 row-span-2'>";
                        }
                        echo "</div>";
                    } else {
                        echo '
                                    <div class="min-h-72 flex flex-col items-center gap-2 justify-center rounded-lg overflow-hidden bg-zinc-200">
                                        <img src="./assets/images/icon/image.svg" alt="image-icon" class="w-20">
                                        <p class="text-zinc-500 font-bold">Belum ada foto</p>
                                    </div>
                                    ';
                    }
                    ?>
                    <div>
                        <div class="flex items-center gap-2">
                            <h1 class="font-bold text-2xl mb-1"><?= $album['namaAlbum'] ?></h1>
                            <div class="w-fit h-fit bg-zinc-200 text-zinc-500 px-2 text-base rounded-lg font-bold"><?= $album['totalFoto'] ?></div>
                        </div>
                        <p class="text-zinc-500"><?= $album['deskripsi'] ?></p>
                    </div>
                    <div class="flex gap-2">
                        <a href="./edit_album.php?albumID=<?=$album['albumID']?>"><img src="./assets/images/icon/edit.svg" alt="edit" class="w-10 cursor-pointer p-1 rounded-lg hover:bg-cyan-200 transition-all"></a>
                        <img src="./assets/images/icon/trash.svg" alt="trash" class="w-10 cursor-pointer p-1 rounded-lg hover:bg-red-200 transition-all" onclick="OpenDeleteAlbumModal(event, <?= $album['albumID'] ?>)">
                    </div>
                </div>
                <div id="modalDelete<?= $album['albumID'] ?>" class="fixed h-screen w-screen bg-black top-0 left-0 z-50 bg-opacity-20 backdrop-blur-sm hidden items-center justify-center">
                    <div class="bg-white flex flex-col items-center p-5 shadow-xl rounded-xl gap-3">
                        <img src="./assets/images/icon/alert.svg" alt="alert-icon" class="w-20">
                        <div class="flex flex-col items-center gap-1">
                            <h1 class="text-2xl font-bold">Hapus Album</h1>
                            <p class="text-slate-500 text-center">Menghapus album juga menghapus fotonya.<br>Apakah anda yakin?</p>
                        </div>
                        <form method="POST" class="flex gap-3 w-full mt-2">
                            <button class="grow text-white bg-zinc-500 p-2 rounded-lg" onclick="CloseDeleteAlbumModal(event, <?= $album['albumID'] ?>)">Batal</button>
                            <button type="submit" class="grow text-white bg-red-400 p-2 rounded-lg" name="delete_album" value="<?= $album['albumID'] ?>">Hapus</button>
                        </form>
                    </div>
                </div>
            <?php } ?>
            <script>
                function OpenDeleteAlbumModal(event, albumID) {
                    event.stopPropagation();
                    let modal = document.getElementById('modalDelete' + albumID);
                    modal.classList.add("flex");
                    modal.classList.remove("hidden");
                }

                function CloseDeleteAlbumModal(event, albumID) {
                    event.stopPropagation();
                    let modal = document.getElementById('modalDelete' + albumID);
                    modal.classList.add("hidden");
                    modal.classList.remove("flex");
                }
            </script>
        </section>
    </main>
</body>

</html>