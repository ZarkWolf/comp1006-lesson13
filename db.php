<?php
$conn = new PDO('mysql:host=sql.;dbname=gc', 'gc', '');
$conn -> setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>