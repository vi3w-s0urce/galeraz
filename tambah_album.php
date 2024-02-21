<?php

include './koneksi.php';
include './middleware.php';

if (isset($_POST['submit_form'])) {
    $namaAlbum = $_POST['namaAlbum'];
    $deskripsi = $_POST['deskripsi'];
    $tanggalDibuat = date('Y-m-d');
    $userID = $_SESSION['user_id'];

    $createdAlbum = $conn->query("INSERT INTO album (namaAlbum, deskripsi, tanggalDibuat, userID) VALUES ('$namaAlbum', '$deskripsi', '$tanggalDibuat', '$userID')");

    if ($createdAlbum) {
        header('Location: ./album.php');
    } else {
        header('Location: ./tambah_album.php');
    }
}

$_SESSION['pageRoute'] = 'album';
$title = 'Tambah Album | Galeraz';
include './header.php';

?>
    <main class="m-5">
        <div class="shadow-xl bg-white rounded-xl p-5">
            <div class="text-2xl font-bold text-center mb-5">🖼️ Tambah Album Baru</div>
            <form method="POST">
                <div class="mb-5 grow">
                    <label for="namaAlbum">Nama Album</label>
                    <input type="text" name="namaAlbum" id="namaAlbum" placeholder="Masukkan Nama Album" class="bg-zinc-200 w-full py-2 px-3 rounded-lg mt-2" required>
                </div>
                <div class="mb-5">
                    <label for="deskripsi">Deskripsi</label>
                    <textarea type="text" name="deskripsi" id="deskripsi" placeholder="Masukkan Deskripsi Album" class="bg-zinc-200 w-full py-2 px-3 rounded-lg mt-2 max-h-32" required></textarea>
                </div>
                <div class="flex gap-5">
                    <a href="./album.php" class="w-full bg-red-400 text-white font-bold py-2 rounded-lg text-center">Batal</a>
                    <button type="submit" name="submit_form" class="w-full bg-cyan-400 text-white font-bold py-2 rounded-lg">Posting</button>
                </div>
            </form>
        </div>
    </main>
</body>

</html>