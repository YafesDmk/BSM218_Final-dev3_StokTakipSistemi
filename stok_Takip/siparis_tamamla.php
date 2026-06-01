<?php
session_start();
$conn = new mysqli("localhost", "root", "Yafesdmk10*", "stok_takip", 3306);
$conn->set_charset("utf8");

$satici_id = $_SESSION['satıcı_id'];

//----------------------------------------------------------------------------------------------------------------- Toplam tutar
$sepet_sorgu = $conn->query("SELECT SUM(Ürünler.birim_fiyat * Sepet.miktar) as toplam FROM Sepet JOIN Ürünler ON Sepet.ürün_id = Ürünler.ürün_id WHERE Sepet.satıcı_id = $satici_id");
$row = $sepet_sorgu->fetch_assoc();
$toplam = $row['toplam'] ?? 0;

//----------------------------------------------------------------------------------------------------------------- Siparişi oluştur
$conn->query("INSERT INTO Siparişler (satıcı_id, toplamTutar, sipariş_tarih) VALUES ($satici_id, $toplam, NOW())");
$siparis_id = $conn->insert_id;

$sql_detay = "INSERT INTO Siparis_Detay (siparis_id, ürün_id, miktar, birim_fiyat) 
              SELECT $siparis_id, Sepet.ürün_id, Sepet.miktar, Ürünler.birim_fiyat 
              FROM Sepet 
              JOIN Ürünler ON Sepet.ürün_id = Ürünler.ürün_id 
              WHERE Sepet.satıcı_id = $satici_id";

if (!$conn->query($sql_detay)) {
    die("Sorgu Hatası (Detay): " . $conn->error);
}

/*sepeti temizleyen kısım*/
$conn->query("DELETE FROM Sepet WHERE satıcı_id = $satici_id");

echo "<script>alert('Siparişiniz başarıyla oluşturuldu!'); window.location.href='satıcı_panel.php';</script>";
?>