<?php

require "../config/config.php";
$stat = $pdo->prepare("SELECT * FROM posts WHERE id=".$_GET['id']);
$stat->execute();
$result = $stat->fetch(PDO::FETCH_ASSOC);
$image = $result['image'];
unlink("images/".$image);

$stat = $pdo->prepare("DELETE FROM posts WHERE id=".$_GET['id']);
$stat->execute();
header("location:index.php");