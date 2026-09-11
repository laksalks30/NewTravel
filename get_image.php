<?php
include "koneksi.php";

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: images/travel%20indo.jpg");
    exit;
}

$id = (int)$_GET['id'];

// Set browser cache headers (cache 7 days)
header("Cache-Control: public, max-age=604800");
header("Expires: " . gmdate("D, d M Y H:i:s", time() + 604800) . " GMT");

$stmt = $conn->prepare("SELECT gambar_destinasi FROM destinasi WHERE id_destinasi = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->store_result();
$stmt->bind_result($gambar);
$stmt->fetch();

if ($stmt->num_rows > 0 && !empty($gambar)) {
    $mime = 'image/jpeg';
    if (class_exists('finfo')) {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $detected = $finfo->buffer($gambar);
        if ($detected) {
            $mime = $detected;
        }
    }
    header("Content-Type: " . $mime);
    header("Content-Length: " . strlen($gambar));
    echo $gambar;
} else {
    header("Content-Type: image/jpeg");
    if (file_exists("images/travel indo.jpg")) {
        readfile("images/travel indo.jpg");
    }
}
$stmt->close();
exit;
