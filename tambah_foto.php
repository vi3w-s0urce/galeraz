<?php

include './koneksi.php';
include './middleware.php';

$userID = $_SESSION['user_id'];
$get_data_album = $conn->query("SELECT * FROM album WHERE userID='$userID'");
$data_album = $get_data_album->fetch_all(MYSQLI_ASSOC);


if (isset($_POST['submit_form'])) {
    $filename = $_FILES['foto']['name'];
    $filetmp = $_FILES['foto']['tmp_name'];
    $filesize = $_FILES['foto']['size'];
    $fileextention = pathinfo($filename, PATHINFO_EXTENSION);

    $judulFoto = $_POST['judulFoto'];
    $deskripsiFoto = $_POST['deskripsiFoto'];
    $tanggalUnggah = date('Y-m-d');
    $lokasiFile = './storage/' . uniqid() . '.' . $fileextention;
    $albumID = isset($_POST['albumID']) ? $_POST['albumID'] : 'null';
    $userID = $_SESSION['user_id'];

    if ($fileextention != 'jpg' && $fileextention != 'png' && $fileextention != 'jpeg' && $fileextention != 'heic') {
        $_SESSION['submit_error'] = 'extention_file';
        header('Location: ./tambah_foto.php');
        exit();
    }

    if ($filesize > 50000000) {
        $_SESSION['submit_error'] = 'size_foto';
        header('Location: ./tambah_foto.php');
        exit();
    }

    $createdFoto = $conn->query("INSERT INTO foto (judulFoto, deskripsiFoto, tanggalUnggah, lokasiFile, albumID, userID) VALUES ('$judulFoto', '$deskripsiFoto', '$tanggalUnggah', '$lokasiFile', $albumID, $userID)");

    if ($createdFoto) {
        move_uploaded_file($filetmp, $lokasiFile);
        header('Location: ./profile.php');
        unset($_SESSION['submit_error']);
    }
}

$_SESSION['pageRoute'] = 'addFoto';
$title = 'Tambah Foto | Galeraz';
include './header.php';

?>
    <main class="m-5 flex justify-center">
        <div class="shadow-xl bg-white rounded-xl p-6 max-w-[1200px] w-full border-2">
            <div class="text-2xl font-bold text-center mb-5">📷 Posting Foto Baru</div>
            <form method="POST" enctype="multipart/form-data">
                <div class="flex w-full gap-10">
                    <div class="grow">
                        <div class="flex gap-5 items-center">
                            <div class="mb-5 grow">
                                <label for="judulFoto">Judul Foto</label>
                                <input type="text" name="judulFoto" id="judulFoto" placeholder="Masukkan Judul Foto" class="bg-zinc-200 w-full py-2 px-3 rounded-lg mt-2" required>
                            </div>
                            <div class="mb-5 grow">
                                <label for="albumID">Album</label>
                                <select name="albumID" id="albumID" class="bg-zinc-200 w-full py-2 px-3 rounded-lg mt-2">
                                    <option value="null" class="text-zinc-500" selected disabled hidden>Pilih Album (Opsional)</option>
                                    <option value="null" class="text-zinc-500">Tanpa Album</option>
                                    <?php foreach ($data_album as $album) { ?>
                                        <option value="<?= $album["albumID"] ?>"><?= $album["namaAlbum"] ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>
                        <div class="mb-5">
                            <label for="deskripsiFoto">Deskripsi</label>
                            <textarea type="text" name="deskripsiFoto" id="deskripsiFoto" placeholder="Masukkan Deskripsi Foto" class="bg-zinc-200 w-full py-2 px-3 rounded-lg mt-2 max-h-32" required></textarea>
                        </div>
                    </div>
                    <div class="basis-1/2">
                        <div class="flex flex-col">
                            <label for="foto">Foto</label>
                            <div class="bg-zinc-200 w-full rounded-lg mt-2 min-h-96 flex items-center justify-center">
                                <label for="foto" id="file-ip-label" class="flex flex-col cursor-pointer">
                                    <img id="file-ip-1-preview" class=" max-h-96 object-cover ">
                                    <div class="flex flex-col items-center justify-center" id="label-icon">
                                        <img src="./assets/images/icon/add_image.svg" alt="image" class="w-20">
                                        <p class="text-zinc-500 text-xl font-bold mt-2">Upload Foto</p>
                                    </div>
                                </label>
                                <input type="file" name="foto" id="foto" accept="image/*" class="w-full h-full hidden" onchange="showPreview(event);" required />
                            </div>
                        </div>
                        <?php
                        if (isset($_SESSION['submit_error'])) {
                            if ($_SESSION['submit_error'] == 'extention_file') {
                                echo '
                                <div class="flex mt-2 gap-1 items-center">
                                    <img src="./assets/images/icon/alert.svg" alt="alert-icon" class="w-6">
                                    <span class="text-red-500">File yang anda unggah bukan berupa gambar!</span>
                                </div>
                                ';
                            }
                            if ($_SESSION['submit_error'] == 'size_foto') {
                                echo '
                                <div class="flex mt-2 gap-1 items-center">
                                    <img src="./assets/images/icon/alert.svg" alt="alert-icon" class="w-6">
                                    <span class="text-red-500">Ukuran file foto terlalu besar!</span>
                                </div>
                                ';
                            }
                        } ?>
                    </div>
                </div>
                <div class="flex gap-5 mt-5">
                    <a href="./index.php" class="w-full bg-red-400 text-white font-bold py-2 rounded-lg text-center">Batal</a>
                    <button type="submit" name="submit_form" class="w-full bg-cyan-400 text-white font-bold py-2 rounded-lg">Posting</button>
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