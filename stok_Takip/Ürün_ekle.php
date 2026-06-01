<?php
session_start();
//------------------------------------------------------------------------------------------ GÜVENLİK: Giriş yapmayan ekleyemez
if (!isset($_SESSION['satıcı_id'])) { header("Location: Satıcı_giris.html"); exit(); }

$conn = new mysqli("localhost", "root", "Yafesdmk10*", "stok_takip", 3306);
$conn->set_charset("utf8");

//------------------------------------------------------------------------------------------ FORM GÖNDERİLDİYSE KAYDET
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $ad = $_POST['ürün_ad'];
    $fiyat = $_POST['birim_fiyat'];
    $stok = $_POST['stok_miktari'];
    $kat_id = $_POST['kategori_id'];
    $ted_id = $_POST['tedarikci_id']; //Tedarikci seçim kısmı(yeni eklenenler dahil)
    $satici_id = $_SESSION['satıcı_id'];

    $sql = "INSERT INTO Ürünler (ürün_ad, birim_fiyat, stok_miktari, kategori_id, tedarikci_id, satıcı_id) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sdiiii", $ad, $fiyat, $stok, $kat_id, $ted_id, $satici_id);

    if ($stmt->execute()) {
        echo "<script>alert('Ürün başarıyla eklendi!'); window.location.href='satıcı_panel.php';</script>";
    } else {
        echo "Hata: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Ürün Ekle</title>
    <style>
        body { font-family: 'Poppins', sans-serif; background: #f4f4f4; padding: 50px; text-align: center; }
        .form-kutusu { background: white; padding: 30px; border-radius: 20px; max-width: 450px; margin: auto; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        input, select { width: 90%; padding: 12px; margin: 10px 0; border: 1px solid #ddd; border-radius: 5px; }
        .btn { background: #711a94; color: white; padding: 12px 25px; border: none; cursor: pointer; border-radius: 5px; font-size: 16px; }
    </style>
</head>
<body>
    <div class="form-kutusu">
        <h2>Yeni Ürün Ekle</h2>
        <form method="post">
            <input type="text" name="ürün_ad" placeholder="Ürün Adı" required>
            <input type="number" step="0.01" name="birim_fiyat" placeholder="Birim Fiyat" required>
            <input type="number" name="stok_miktari" placeholder="Stok Miktarı" required>
            
            <select name="kategori_id" required>
                <option value="">Kategori Seçin</option>
                <?php
                $kat_sorgu = $conn->query("SELECT * FROM Kategoriler");
                while($kat = $kat_sorgu->fetch_assoc()) {
                    echo "<option value='".$kat['kategori_id']."'>".$kat['kategori_ad']."</option>";
                }
                ?>
            </select>

            <select name="tedarikci_id" required>
                <option value="">Tedarikçi Seçin</option>
                <?php
                $ted_sorgu = $conn->query("SELECT * FROM Tedarikciler");
                while($ted = $ted_sorgu->fetch_assoc()) {
                    echo "<option value='".$ted['tedarikci_id']."'>".$ted['tedarikci_ad']." ".$ted['tedarikci_soyad']."</option>";
                }
                ?>
            </select>
            
            <button type="submit" class="btn">Kaydet</button>
        </form>
    </div>
</body>
</html>