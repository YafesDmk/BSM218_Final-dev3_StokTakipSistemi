<?php
session_start();
if (!isset($_SESSION['satıcı_id'])) { header("Location: Satici_giris.html"); exit(); }

$conn = new mysqli("localhost", "root", "Yafesdmk10*", "stok_takip", 3306);
$conn->set_charset("utf8");

$satici_id = $_SESSION['satıcı_id'];

$sql = "SELECT Ürünler.ürün_ad, Ürünler.birim_fiyat, SUM(Sepet.miktar) as toplam_miktar 
        FROM Sepet 
        JOIN Ürünler ON Sepet.ürün_id = Ürünler.ürün_id 
        WHERE Sepet.satıcı_id = ? 
        GROUP BY Ürünler.ürün_id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $satici_id);
$stmt->execute();
$sepet_urunleri = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Sepetim | Modern Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Poppins', sans-serif; background-color: #f8f9fa; padding: 40px; }
        .container { max-width: 800px; margin: auto; background: white; padding: 30px; border-radius: 20px; box-shadow: 0 10px 25px rgba(0,0,0,0.05); }
        h2 { color: #333; margin-bottom: 25px; border-bottom: 2px solid #f4f4f4; padding-bottom: 10px; }
        .tablo { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        .tablo th { background: #fcfcfc; text-align: left; padding: 15px; color: #777; font-weight: 600; }
        .tablo td { padding: 15px; border-top: 1px solid #f4f4f4; color: #444; }
        .btn-siparis { background: #711a94; color: white; border: none; padding: 15px 30px; border-radius: 10px; cursor: pointer; font-size: 16px; font-weight: 600; width: 100%; transition: 0.3s; }
        .btn-siparis:hover { background: #5c1478; }
        .toplam-alan { text-align: right; font-size: 1.2em; font-weight: 600; color: #333; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    <h2>Sepetimdeki Ürünler</h2>
    <form action="siparis_tamamla.php" method="post">
        <table class="tablo">
            <thead>
                <tr>
                    <th>Ürün Adı</th>
                    <th>Adet</th>
                    <th>Birim Fiyat</th>
                </tr>
            </thead>
            <tbody>
    <?php 
    $genel_toplam = 0;
    while($row = $sepet_urunleri->fetch_assoc()): 
        $genel_toplam += ($row['birim_fiyat'] * $row['toplam_miktar']); // Değişiklik burada
    ?>
                 <tr>
                     <td><?php echo $row['ürün_ad']; ?></td>
                     <td><?php echo $row['toplam_miktar']; ?></td> <!-- Değişiklik burada -->
                     <td><?php echo number_format($row['birim_fiyat'], 2); ?> TL</td>
                 </tr>
             <?php endwhile; ?>
            </tbody>
        </table>

        <div class="toplam-alan">
            Toplam: <?php echo number_format($genel_toplam, 2); ?> TL
        </div>

        <button type="submit" class="btn-siparis">Siparişi Tamamla ve Onayla</button>
    </form>
</div>

</body>
</html>