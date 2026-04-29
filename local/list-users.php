<?php
/**
 * @copyright Actra AG - https://www.actra.ch
 * @license   MIT
 */

declare(strict_types=1);

try {
    $pdo = new PDO(
        dsn: 'mysql:host=db;dbname=db;charset=utf8mb4',
        username: 'db',
        password: 'db',
        options: [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
    $stmt = $pdo->query(query: 'SELECT email, firstName, lastName FROM auth_user ORDER BY email');
    while ($row = $stmt->fetch(
        mode: PDO::FETCH_OBJ
    )) {
        echo '- ' . $row->email . ' (' . $row->firstName . ' ' . $row->lastName . ')' . PHP_EOL;
    }
} catch (PDOException $e) {
    echo 'Fehler: ' . $e->getMessage();
}