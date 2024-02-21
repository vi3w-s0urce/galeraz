<?php 

$conn = new mysqli('localhost', 'root', '', 'galeraz');

if ($conn->connect_error) {
    die('Koneksi gagal ' . $conn->connect_error);
}
