<?php

header('Content-Type: application/json'); // Pastikan response JSON



// Mengambil parameter dari query string

$usernameOrEmail = isset($_GET['username']) ? trim($_GET['username']) : '';

$password = isset($_GET['password']) ? trim($_GET['password']) : '';



// Validasi input

if (empty($usernameOrEmail) || empty($password)) {

    echo json_encode(['error' => 'Username/Email and password are required.']);

    exit;

}



// Koneksi ke database

$host = "localhost";

$dbname = "u676821063_new2";

$user = "u676821063_new2";

$pass = "!/F:6h[E9";



try {

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);



    // Query untuk mendapatkan data user

    $stmt = $pdo->prepare("SELECT password, credit, status FROM user WHERE username = :usernameOrEmail OR email = :usernameOrEmail");

    $stmt->execute(['usernameOrEmail' => $usernameOrEmail]);

    $userData = $stmt->fetch(PDO::FETCH_ASSOC);



    if (!$userData) {

        // Jika akun tidak ditemukan

        echo json_encode(['error' => 'Account Not Found!']);

    } elseif (!password_verify($password, $userData['password'])) {
    //elseif ($password !== $userData['password']) { // Non encrypted dta base

        // Jika password salah

        echo json_encode(['error' => 'Wrong Password!']);

    } else {

        // Jika login berhasil

        echo json_encode([

            'message' => 'Login successful',

            'credit'  => $userData['credit'],

            'status'  => $userData['status']

        ]);

    }

} catch (PDOException $e) {

    echo json_encode(['error' => 'Database error: ' . $e->getMessage()]);

}

?>

