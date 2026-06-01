<?php
session_start();

// 1. Veritabanı bağlantısı
$servername = "localhost";
$username = "root";
$password = "Yafesdmk10*";
$dbname = "stok_takip"; // Küçük harfle güncelledik

$conn = new mysqli($servername, $username, $password, $dbname, 3306);
$conn->set_charset("utf8");

if ($conn->connect_error) {
    die("Bağlantı hatası: " . $conn->connect_error);
}

// 2. Formdan gelen verileri al (Satıcı formundaki name'ler ile aynı)
$satıcı_mail = $_POST['satıcı_mail'];
$satıcı_sifre = $_POST['satıcı_sifre'];

// 3. SQL sorgusu (Prepared Statement kullanarak güvenlik sağlandı)
$sql = "SELECT satıcı_id, satıcı_sifre FROM satıcılar WHERE satıcı_mail = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $satıcı_mail);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // 4. Şifre doğrulama
    if (password_verify($satıcı_sifre, $row['satıcı_sifre'])) {
        
        // Giriş başarılı: Session başlat ve yönlendir
        $_SESSION['satıcı_id'] = $row['satıcı_id']; 
        
        echo "<script>
                alert('Giriş başarılı! Hoş geldiniz.');
                window.location.href = 'satıcı_panel.php'; 
              </script>";
        exit();
    } else {
        echo "<script>
                alert('Şifre yanlış! Tekrar deneyin.');
                window.location.href = 'Satıcı_giris.html';
              </script>";
        exit();
    }
} else {
    echo "<script>
            alert('Bu e-posta ile kayıtlı satıcı bulunamadı.');
            window.location.href = 'Satıcı_giris.html';
          </script>";
    exit();
}

$stmt->close();
$conn->close();
?>