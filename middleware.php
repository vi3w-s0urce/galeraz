<?php
    session_start();
    if (isset($_SESSION['user_id'])) {
        if (strstr($_SERVER['REQUEST_URI'], 'login.php') || strstr($_SERVER['REQUEST_URI'], 'registrasi.php')) {
            if (isset($_SERVER['HTTP_REFERER'])) {
                header('Location:' . $_SERVER['HTTP_REFERER']);
            } else {
                header('Location: ./index.php');
            }
        } else {
            return true;
        }
    } else {
        if (strstr($_SERVER['REQUEST_URI'], 'login.php') || strstr($_SERVER['REQUEST_URI'], 'registrasi.php')) {
            return true;
        } else {
            if (isset($_SERVER['HTTP_REFERER'])) {
                header('Location:' . $_SERVER['HTTP_REFERER']);
            } else {
                header('Location: ./login.php');
            }
        }
    }