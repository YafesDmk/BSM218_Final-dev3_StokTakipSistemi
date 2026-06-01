<?php
session_start();


if (isset($_SESSION['satıcı_id'])) {
    $yönlendir = "Ana_Sayfa.html";
} elseif (isset($_SESSION['tedarikci_id'])) {
    $yönlendir = "Ana_Sayfa.html";
} else {

    $yönlendir = "Ana_Sayfa.html";
}


session_unset(); 
session_destroy(); 


header("Location: " . $yönlendir);
exit();
?>