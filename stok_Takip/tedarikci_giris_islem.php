<?php
session_start();

//------------------------------------------------------------------------------------------- Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "Yafesdmk10*";
$dbname = "stok_takip";

$conn = new mysqli($servername, $username, $password, $dbname, 3306);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

//------------------------------------------------------------------------------------------Formdan gelen verileri alan kısım 
$tedarikci_mail = $_POST['tedarikci_mail'];
$tedarikci_sifre = $_POST['tedarikci_sifre'];


$sql = "SELECT tedarikci_id, tedarikci_sifre FROM tedarikciler WHERE tedarikci_mail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $tedarikci_mail);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    //-------------------------------------------------------------------------------------- Şifre doğrulama
    if (password_verify($tedarikci_sifre, $row['tedarikci_sifre'])) {
        
    
        $_SESSION['tedarikci_id'] = $row['tedarikci_id']; 
        
        echo "<script>
                alert('Tedarikçi girişi başarılı! Hoş geldiniz.');
                window.location.href = 'tedarikci_panel.php'; 
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Şifre yanlış! Tekrar deneyin.');
                window.location.href = 'Tedarikci_giris.html';
              </script>";
        exit();
    }
} else {
    echo "<script>
            alert('Bu e-posta ile kayıtlı tedarikçi bulunamadı.');
            window.location.href = 'Tedarikci_giris.html';
          </script>";
    exit();
}

$stmt->close();
$conn->close();
?>