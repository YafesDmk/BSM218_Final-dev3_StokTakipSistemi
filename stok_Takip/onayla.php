<?php
session_start();
$conn = new mysqli("localhost", "root", "Yafesdmk10*", "stok_takip", 3306);
$conn->set_charset("utf8");

if(isset($_GET['id'])) {
    $siparis_id = $_GET['id'];


    $detaylar = $conn->query("SELECT ürün_id, miktar FROM Siparis_Detay WHERE siparis_id = $siparis_id");

    while($item = $detaylar->fetch_assoc()) {
        $urun_id = $item['ürün_id'];
        $miktar = $item['miktar'];


        $conn->query("UPDATE Ürünler SET stok_miktari = stok_miktari + $miktar WHERE ürün_id = $urun_id");
    }

    $conn->query("UPDATE Siparişler SET durum = 'Onaylandı' WHERE sipariş_id = $siparis_id");
    
    echo "<script>alert('Sipariş onaylandı, stoklarınız güncellendi!'); window.location.href='tedarikci_panel.php';</script>";
}
?>