<?php
session_start();
//----------------------------------------------------------------------------------------------------------------- GÜVENLİK(GİRİŞ YAPMADAN GİRİLEMEZ!)
if (!isset($_SESSION['satıcı_id'])) {
    header("Location: Satici_giris.html");
    exit();
}

//-----------------------------------------------------------------------------------------------------------------VERİTABANIM
$conn = new mysqli("localhost", "root", "Yafesdmk10*", "stok_takip", 3306);
$conn->set_charset("utf8");

//--------------------------------------------------------------------------SATICI BİLGİLERİ
$id = $_SESSION['satıcı_id'];
$sql = "SELECT satıcı_ad, satıcı_soyad FROM Satıcılar WHERE satıcı_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$satıcı = $stmt->get_result()->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Satıcı Paneli</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f4f4f4; padding: 20px; }
        .header { display: flex; align-items: center; justify-content: space-between; padding: 20px; background: white; border-radius: 25px; box-shadow: 0 2px 5px rgba(0,0,0,0.05); margin-bottom: 20px; }
        .panel-kart { background: white; padding: 20px; border-radius: 15px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        .urun-tablosu { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .urun-tablosu th, .urun-tablosu td { padding: 15px; border-bottom: 1px solid #ddd; text-align: left; }
        .urun-tablosu th { background-color: #711a94; color: white; }
        .btn-siparis { background-color: #4CAF50; color: white; padding: 8px 15px; text-decoration: none; border-radius: 4px; font-size: 14px; }
    </style>
</head>
<body>

<!-- Header Bölümü -->
<div class="header">
    <img src="Stok_takip_Logo.png" alt="Logon" style="height: 200px;">
    <h3 style="margin:0;">Hoş geldin, <?php echo $satıcı['satıcı_ad'] . " " . $satıcı['satıcı_soyad']; ?></h3>
    
    <div>
        <!-- Yeni Sepet Butonu -->
        <a href="sepetim.php" style="background: #f39c12; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px;">Sepetim</a>
        
        <a href="Ürün_ekle.php" style="background: #711a94; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px; margin-right: 10px;">Ürün Ekle</a>
        <a href="cıkıs.php" style="background: #e74c3c; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">Çıkış Yap</a>
    </div>
</div>
</div>

<div class="panel-kart">
    <h2>Stoktaki Ürünler</h2>
    <table class="urun-tablosu">
        <thead>
            <tr>
                <th>Ürün Adı</th>
                <th>Kategori</th>
                <th>Tedarikçi</th>
                <th>Birim Fiyat</th>
                <th>Stok</th>
                <th>İşlem</th>
            </tr>
        </thead>
        <tbody>
            <?php
        $sql = "SELECT Ürünler.*, Kategoriler.kategori_ad, Tedarikciler.tedarikci_ad, Tedarikciler.tedarikci_soyad 
        FROM Ürünler 
        LEFT JOIN Kategoriler ON Ürünler.kategori_id = Kategoriler.kategori_id
        LEFT JOIN Tedarikciler ON Ürünler.tedarikci_id = Tedarikciler.tedarikci_id";
        $urunler = $conn->query($sql);
            
            while($row = $urunler->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['ürün_ad'] . "</td>";
                echo "<td>" . ($row['kategori_ad'] ?? 'Belirtilmemiş') . "</td>";
                echo "<td>" . ($row['tedarikci_ad'] ?? 'Belirtilmemiş') . " " . ($row['tedarikci_soyad'] ?? 'Belirtilmemiş') . "</td>";
                echo "<td>" . $row['birim_fiyat'] . "</td>";
                echo "<td>" . $row['stok_miktari'] . "</td>";
                echo "<td><a href='sepet_ekle.php?urun_id=".$row['ürün_id']."' class='btn-siparis'>Sepete Ekle</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>

</body>
</html>