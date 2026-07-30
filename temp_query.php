<?php
$db = new PDO('sqlite:database/database.sqlite');
$stmt = $db->query("SELECT id, name, email, rsbsa_number, role, location FROM users WHERE role='Farmer' AND COALESCE(rsbsa_number, '') != '' ORDER BY id DESC LIMIT 20");
foreach ($stmt as $row) {
    echo implode('|', $row) . PHP_EOL;
}
