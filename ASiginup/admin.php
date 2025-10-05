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

// Récupérez tous les utilisateurs
$stmt = $conn->prepare("SELECT * FROM users");
$stmt->execute();
$users = $stmt->get_result();

echo "<h1>Liste des utilisateurs</h1>";
echo "<table border='1'>";
echo "<tr><th>ID</th><th>Nom d'utilisateur</th><th>Email</th><th>Status</th><th>Action</th></tr>";

while ($user = $users->fetch_assoc()) {
    echo "<tr>";
    echo "<td>" . $user['id'] . "</td>";
    echo "<td>" . htmlspecialchars($user['username']) . "</td>";
    echo "<td>" . htmlspecialchars($user['email']) . "</td>";
    echo "<td>" . ($user['status'] === 'approved' ? 'Approuvé' : 'En attente') . "</td>";
    echo "<td><form action='approve.php' method='post'>";
    echo "<input type='hidden' name='username' value='" . $user['username'] . "'>";
    echo "<select id='status' name='status'>";
    echo "<option value='approved'>Approuvé</option>";
    echo "<option value='rejected'>Rejeté</option>";
    echo "</select>";
    echo "<button type='submit'>Valider</button>";
    echo "</form></td>";
    echo "</tr>";
}

echo "</table>";

$stmt->close();
$conn->close();

// Bouton pour retourner à la page d'accueil
echo "<a href='login.php'><button>Se déconnecter</button></a>";
?>
