<?php
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=gestion_employe', 'root', '');
    echo "Connexion PDO MySQL réussie !";
} catch (PDOException $e) {
    echo "Erreur PDO : " . $e->getMessage();
}
