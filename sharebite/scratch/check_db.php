<?php
$pdo1 = new PDO('mysql:host=127.0.0.1;port=3306;dbname=sharebite_dusk', 'root', '');
$stmt1 = $pdo1->prepare("SELECT * FROM users WHERE email = ?");
$stmt1->execute(['jaki.munawaroh@bakery.com']);
$user1 = $stmt1->fetch();
echo "sharebite_dusk: " . ($user1 ? "FOUND (id: {$user1['id']})" : "NOT FOUND") . "\n";

$pdo2 = new PDO('mysql:host=127.0.0.1;port=3306;dbname=sharebite', 'root', '');
$stmt2 = $pdo2->prepare("SELECT * FROM users WHERE email = ?");
$stmt2->execute(['jaki.munawaroh@bakery.com']);
$user2 = $stmt2->fetch();
echo "sharebite: " . ($user2 ? "FOUND (id: {$user2['id']})" : "NOT FOUND") . "\n";
