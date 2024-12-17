<?php
require 'phpqrcode/qrlib.php';

$url = isset($_GET['url']) ? $_GET['url'] : '';

// Set the size and error correction level
$size = 10; // Increase this value to make the QR code larger
$level = 'H'; // Error correction level: L, M, Q, H

QRcode::png($url, false, $level, $size, 2);
?>
