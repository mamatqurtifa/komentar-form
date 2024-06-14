<?php
 
// set atau include file db.php
include 'db.php';
 
// Cek jika request nya itu post
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // ambil data name form komentar
    $nama = stripslashes(strip_tags(htmlspecialchars($_POST["nama"], ENT_QUOTES)));
    $komentar = stripslashes(strip_tags(htmlspecialchars($_POST["komentar"], ENT_QUOTES)));
    $parent_id = stripslashes(strip_tags(htmlspecialchars($_POST["parent_id"], ENT_QUOTES)));
    
    // set timezone indonesia
    $tz = 'Asia/Jakarta';
    $dt = new DateTime("now", new DateTimeZone($tz));
    $date = $dt->format('Y-m-d H:i:s');
 
    // lakukan insert
    $query = "INSERT INTO tbl_komentar (nama, komentar, parent_id, tanggal_komen) VALUES (?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("ssis", $nama, $komentar, $parent_id,  $date);
    $stmt->execute();
    
 // redirect HTTP/1.1 404 Not Found ketika pengguna mencoba mengakses file data.php
} else {
    header("HTTP/1.1 404 Not Found");
}
?>