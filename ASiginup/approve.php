<?php
// Vérifiez si l'utilisateur est connecté et si il a le droit d'accéder à cette page
// if (!isset($_SESSION['username']) || $_SESSION['status'] !== 'admin') {
//     header('Location: login.php');
//     exit;
// }

$conn = new mysqli('localhost', 'root', '', 'user_management');
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Récupérez le nom d'utilisateur et le nouveau statut
$username = $_POST['username'];
$status = $_POST['status'];

$stmt = $conn->prepare("UPDATE users SET status = ? WHERE username = ?");
$stmt->bind_param("ss", $status, $username);

if ($stmt->execute()) {
    echo "Le compte de " . $username . " a été " . ($status === 'approved' ? 'approuvé.' : 'rejeté.');
} else {
    echo "Erreur lors de l'approbation du compte : " . $stmt->error;
}

$stmt->close();
$conn->close();

// Redirigez vers la page d'administration
header('Location: admin.php');
exit;
?>
