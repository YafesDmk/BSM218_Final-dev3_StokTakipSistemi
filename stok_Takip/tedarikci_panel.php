<?php
session_start();
//------------------------------------------------------------------------------------- GÜVENLİK
if (!isset($_SESSION['tedarikci_id'])) {
    header("Location: Tedarikci_giris.html");
    exit();
}

//------------------------------------------------------------------------------------ VERİTABANI
$conn = new mysqli("localhost", "root", "Yafesdmk10*", "stok_takip", 3306);
$conn->set_charset("utf8");

//------------------------------------------------------------------------------------ TEDARİKÇİ BİLGİLERİ
$id = $_SESSION['tedarikci_id'];
$sql = "SELECT tedarikci_ad, tedarikci_soyad FROM Tedarikciler WHERE tedarikci_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$tedarikci = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Tedarikçi Paneli</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f4f4; padding: 20px; }
        .header { display: flex; align-items: center; justify-content: space-between; padding: 20px; background: white; border-radius: 25px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .panel-kart { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .siparis-tablosu { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .siparis-tablosu th, .siparis-tablosu td { padding: 15px; border-bottom: 1px solid #ddd; text-align: left; }
        .siparis-tablosu th { background-color: #af4c4c; color: white; }
        .siparis-tablosu tr:hover { background-color: #f1f1f1; }
    </style>
</head>
<body>

<div class="header">
    <img src="Stok_takip_Logo.png" alt="Logon" style="height: 200px;">
    
    <h3 style="margin:0;">Hoş geldin, <?php echo $tedarikci['tedarikci_ad'] . " " . $tedarikci['tedarikci_soyad']; ?></h3>
    
    <div>
        <a href="cıkıs.php" style="background: #e74c3c; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; font-weight: bold;">Çıkış Yap</a>
    </div>
</div>

<div class="panel-kart">
    <h2>Gelen Siparişler</h2>
    <table class="siparis-tablosu">
        <thead>
            <tr>
            <th>Ürün Adı</th>
            <th>Miktar</th>
            <th>Toplam Tutar</th>
            <th>Sipariş Tarihi</th>
            <th>Durum</th> <th>İşlem</th> </tr>
            </thead>
        <tbody>
            <?php



$sql = "SELECT 
            S.sipariş_id, 
            S.sipariş_tarih, 
            S.durum, 
            GROUP_CONCAT(U.ürün_ad SEPARATOR ', ') as urunler, 
            SUM(SD.miktar) as toplam_miktar,
            -- Sadece bu tedarikçinin ürünlerinin toplamını hesapla:
            SUM(SD.miktar * SD.birim_fiyat) as tedarikci_ozel_tutar 
        FROM Siparişler S
        JOIN Siparis_Detay SD ON S.sipariş_id = SD.siparis_id
        JOIN Ürünler U ON SD.ürün_id = U.ürün_id
        WHERE U.tedarikci_id = ? 
        GROUP BY S.sipariş_id
        ORDER BY S.sipariş_tarih DESC";
$stmt = $conn->prepare($sql);

if (!$stmt) {
    die("Sorgu Hazırlanamadı: " . $conn->error);
}

$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['urunler'] . "</td>"; 
        echo "<td>" . $row['toplam_miktar'] . "</td>";
        echo "<td>" . number_format($row['tedarikci_ozel_tutar'], 2) . " TL</td>";
        echo "<td>" . $row['sipariş_tarih'] . "</td>";
        echo "<td>" . $row['durum'] . "</td>";
        echo "<td><a href='onayla.php?id=" . $row['sipariş_id'] . "' style='background: #27ae60; color: white; padding: 5px 10px; text-decoration: none; border-radius: 5px;'>Onayla</a></td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' style='text-align:center;'>Henüz yeni siparişiniz yok.</td></tr>";
}
?>
        </tbody>
    </table>
</div>

</body>
</html>