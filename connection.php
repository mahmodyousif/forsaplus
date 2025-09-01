<?php


$host = 'localhost';
$name = 'forsaplus';
$user = 'root';
$password = ''; 
$con = "mysql:host={$host};dbname={$name};";

$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION, 
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,      
    PDO::ATTR_EMULATE_PREPARES   => false,                
];

try {
    $con = new PDO($con, $user, $password, $options);
} catch (PDOException $e) {
    exit('Database connection failed: ' . $e->getMessage());
}
