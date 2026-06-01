<?php
// 1. Veritabanı bağlantısı - Kendi bilgilerinle kontrol et
$servername = "localhost";
$username = "root";
$password = "Yafesdmk10*"; 
$dbname = "stok_Takip"; // Yeni veritabanın

$conn = new mysqli($servername, $username, $password, $dbname, 3306);

// Karakter seti ayarı
$conn->set_charset("utf8");

// Bağlantı kontrolü
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// 2. Formdan gelen verileri alan kısım (Formdaki 'name' değerlerine göre)
$satıcı_ad = $_POST['satıcı_ad'];
$satıcı_soyad = $_POST['satıcı_soyad'];
$satıcı_mail = $_POST['satıcı_mail'];
$satıcı_sifre = password_hash($_POST['satıcı_sifre'], PASSWORD_DEFAULT);
$satıcı_tc = $_POST['satıcı_tc'];
$satıcı_dogumG = $_POST['satıcı_dogumG'];
$satıcı_num = $_POST['satıcı_num'];
$satıcı_adres = $_POST['satıcı_adres'];

// 3. SQL sorgusu (Yeni tablonun sütun isimlerine göre güncellendi)
$sql = "INSERT INTO Satıcılar 
(satıcı_ad, satıcı_soyad, satıcı_mail, satıcı_sifre, satıcı_tc, satıcı_dogumG, satıcı_num, satıcı_adres)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

// Güvenlik için Prepared Statements (SQL Injection'ı önler)
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $satıcı_ad, $satıcı_soyad, $satıcı_mail, $satıcı_sifre, $satıcı_tc, $satıcı_dogumG, $satıcı_num, $satıcı_adres);

if ($stmt->execute()) {
    echo "<script>
            alert('Kayıt başarılı! Şimdi giriş yapabilirsiniz.');
            window.location.href = 'Satıcı_giris.html';
          </script>";
    exit();
} else {
    echo "Hata: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>