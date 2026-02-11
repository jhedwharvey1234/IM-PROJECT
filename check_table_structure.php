<?php
// Quick script to check table structure
require_once 'app/Config/Database.php';

$db = \Config\Database::connect();

echo "=== UNITS TABLE ===\n";
$result = $db->query("SHOW CREATE TABLE units");
$row = $result->getRowArray();
echo $row['Create Table'] . "\n\n";

echo "=== ASSETS TABLE ===\n";
$result = $db->query("SHOW CREATE TABLE assets");
$row = $result->getRowArray();
echo $row['Create Table'] . "\n\n";

echo "=== PERIPHERALS TABLE ===\n";
$result = $db->query("SHOW CREATE TABLE peripherals");
$row = $result->getRowArray();
echo $row['Create Table'] . "\n\n";
