<?php

include './koneksi.php';
include './middleware.php';

if (!isset($_GET['fotoID'])) {
    header('Location: ./profile.php');
    exit();
}

$fotoID = $_GET['fotoID'];
$userID = $_SESSION['user_id'];
$data_album = $conn->query("SELECT * FROM album WHERE userID='$userID'")->fetch_all(MYSQLI_ASSOC);
$old_data = $conn->query("SELECT * FROM foto WHERE fotoID='$fotoID' AND userID='$userID'")->fetch_assoc();

if ($old_data['userID'] != $userID) {
    header('Location: ./profile.php');
    exit();
}

if (isset($_POST['submit_form'])) {
    $judulFoto = htmlspecialchars($_POST['judulFoto']);
    $deskripsiFoto = htmlspecialchars($_POST['deskripsiFoto']);
    $albumID = isset($_POST['albumID']) ? $_POST['albumID'] : 'NULL';
    $userID = $_SESSION['user_id'];

    $editedFoto = $conn->query("UPDATE foto SET judulFoto='$judulFoto', deskripsiFoto='$deskripsiFoto', albumID=$albumID WHERE fotoID='$fotoID' AND userID='$userID'");

    if ($editedFoto) {
        header('Location: ./profile.php');
        unset($_SESSION['submit_error']);
    }
}

$_SESSION['pageRoute'] = 'profile';
$title = 'Edit Foto | Galeraz';
include './header.php';
?>

    <main class="m-5">
        <div class="shadow-xl bg-white rounded-xl p-5">
            <div class="text-2xl font-bold text-center mb-5">📷 Posting Foto Baru</div>
            <form method="POST" enctype="multipart/form-data">
                <div class="flex w-full gap-10">
                    <div class="grow">
                        <div class="flex gap-5 items-center">
                            <div class="mb-5 grow">
                                <label for="judulFoto">Judul Foto</label>
                                <input type="text" name="judulFoto" id="judulFoto" placeholder="Masukkan Judul Foto" class="bg-zinc-200 w-full py-2 px-3 rounded-lg mt-2" value="<?= $old_data['judulFoto'] ?>" required>
                            </div>
                            <div class="mb-5 grow">
                                <label for="albumID">Album</label>
                                <select name="albumID" id="albumID" class="bg-zinc-200 w-full py-2 px-3 rounded-lg mt-2">
                                    <option value="null" class="text-zinc-500">Tanpa Album</option>
                                    <?php foreach ($data_album as $album) {
                                        if ($old_data['albumID'] == $album['albumID']) {
                                    ?>
                                            <option value='<?= $album['albumID'] ?>' selected><?= $album['namaAlbum'] ?></option>
                                        <?php } else { ?>
                                            <option value="<?= $album["albumID"] ?>"><?= $album["namaAlbum"] ?></option>
                                        <?php } ?>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label for="deskripsiFoto">Deskripsi</label>
                            <textarea type="text" name="deskripsiFoto" id="deskripsiFoto" placeholder="Masukkan Deskripsi Foto" class="bg-zinc-200 w-full py-2 px-3 rounded-lg mt-2 max-h-32" required><?= $old_data['deskripsiFoto'] ?></textarea>
                        </div>
                    </div>
                    <div class="basis-1/2">
                        <div class="flex flex-col">
                            <label for="foto">Foto</label>
                            <div class="bg-zinc-200 w-full rounded-lg mt-2 min-h-96 flex items-center justify-center">
                                <div id="file-ip-label" class="flex flex-col">
                                    <img src="<?= $old_data['lokasiFile'] ?>" id="file-ip-1-preview" class="max-h-96 object-cover">
                                </div>
                            </div>
                        </div>
                        <div class="flex mt-2 gap-1 items-center">
                            <img src="./assets/images/icon/alert_gray.svg" alt="alert-icon" class="w-6">
                            <span class="text-zinc-500">Foto tidak dapat diubah</span>
                        </div>
                    </div>
                </div>
                <div class="flex gap-5 mt-5">
                    <a href="./index.php" class="w-full bg-red-400 text-white font-bold py-2 rounded-lg text-center">Batal</a>
                    <button type="submit" name="submit_form" class="w-full bg-cyan-400 text-white font-bold py-2 rounded-lg">Edit</button>
                </div>
            </form>
        </div>
    </main>
    <script>
        function showPreview(event) {
            if (event.target.files.length > 0) {
                let src = URL.createObjectURL(event.target.files[0]);
                let preview = document.getElementById("file-ip-1-preview");
                let label_icon = document.getElementById("label-icon");
                preview.src = src;
                preview.style.display = "block";
                label_icon.classList = "hidden";
            }
        }
    </script>
</body>

</html>