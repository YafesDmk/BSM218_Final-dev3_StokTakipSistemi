<?php
session_start();
$conn = new mysqli("localhost", "root", "Yafesdmk10*", "stok_takip", 3306);

$urun_id = $_GET['urun_id'];
$satici_id = $_SESSION['satıcı_id'];

//--------------------------------------------------------------------------------------------------------------------Sepete ekle 
$sql = "INSERT INTO Sepet (satıcı_id, ürün_id, miktar) VALUES (?, ?, 1)  ON DUPLICATE KEY UPDATE miktar = miktar + 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $satici_id, $urun_id);
$stmt->execute();

echo "<script>alert('Ürün sepete eklendi!'); window.location.href='satıcı_panel.php';</script>";
?>