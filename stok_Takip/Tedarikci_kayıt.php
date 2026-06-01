<?php
//-------------------------------------------------------------------------------------------- Veritabanı bağlantısı kendi bilgilerim
$servername = "localhost";
$username = "root";
$password = "Yafesdmk10*"; 
$dbname = "stok_Takip"; // Yeni veritabanın

$conn = new mysqli($servername, $username, $password, $dbname, 3306);

$conn->set_charset("utf8");

//-------------------------------------------------------------------------------------------- Bağlantı kontrolü
if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

$tedarikci_ad = $_POST['tedarikci_ad'];
$tedarikci_soyad = $_POST['tedarikci_soyad'];
$tedarikci_mail = $_POST['tedarikci_mail'];
$tedarikci_sifre = password_hash($_POST['tedarikci_sifre'], PASSWORD_DEFAULT);
$tedarikci_tc = $_POST['tedarikci_tc'];
$tedarikci_dogumG = $_POST['tedarikci_dogumG'];
$tedarikci_num = $_POST['tedarikci_num'];
$tedarikci_adres = $_POST['tedarikci_adres'];

//--------------------------------------------------------------------------------------------- Veri ekleme
$sql = "INSERT INTO Tedarikciler 
(tedarikci_ad, tedarikci_soyad, tedarikci_mail, tedarikci_sifre, tedarikci_tc, tedarikci_dogumG, tedarikci_num, tedarikci_adres)
VALUES (?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssssss", $tedarikci_ad, $tedarikci_soyad, $tedarikci_mail, $tedarikci_sifre, $tedarikci_tc, $tedarikci_dogumG, $tedarikci_num, $tedarikci_adres);

if ($stmt->execute()) {
    echo "<script>
            alert('Kayıt başarılı! Şimdi giriş yapabilirsiniz.');
            window.location.href = 'Tedarikci_giris.html';
          </script>";
    exit();
} else {
    echo "Hata: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>