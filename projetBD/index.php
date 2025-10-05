<?php
session_start();

include "db.php";

// Vérifier que l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// Récupérer tous les utilisateurs
$sql = "SELECT u.Id, u.Nom, u.Prenom, u.Email, u.Role, i.Chemin AS ImageChemin 
        FROM Utilisateur u
        LEFT JOIN Image i ON u.Id_Image = i.Id";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Utilisateurs</title>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/v/bs5/dt-2.1.8/datatables.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="">
</head>
<body>
    <section class="container py-5">
        <div class="row">
            <div class="col-lg-8 col-sm mb-5 mx-auto">
                <h1 class="fs-4 text-center lead text-primary">Gestion des Utilisateurs</h1>
            </div>
        </div>
        <div class="dropdown-divider border-warning"></div>
        <!-- Bouton Ajouter visible uniquement pour les administrateurs -->
        <?php if ($_SESSION['role'] === 'Admin') : ?>
        <div class="row">
            <div class="col-md-6">
                <h5 class="fw-bold md-0">Liste des utilisateurs</h5>
            </div>
            <div class="col-md-6">
                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary btn-sm me-3" data-bs-toggle="modal" data-bs-target="#createModal">
                        <i class="fas fa-user-plus"> Ajouter</i>
                    </button>
                </div>
            </div>
        </div>
        <?php endif; ?>
        <div class="dropdown-divider border-warning"></div>
        <div class="row">
            <div class="table-responsive" id="userTable">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Email</th>
                            <th>Rôle</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                        <tr>
                            <th scope="row"><?= $row['Id']; ?></th>
                            <td><?= $row['Nom']; ?></td>
                            <td><?= $row['Prenom']; ?></td>
                            <td><?= $row['Email']; ?></td>
                            <td><?= $row['Role']; ?></td>
                            <td>
                                <a href="#" class="text-info me-2 detailBtn" data-id="<?= $row['Id']; ?>" title="Détails">
                                    <i class="fas fa-info-circle"></i>
                                </a>
                                <?php if ($_SESSION['role'] === 'Admin') : ?>
                                <a href="#" class="text-primary me-2 editBtn" data-id="<?= $row['Id']; ?>" title="Modifier">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="delete_user.php?id=<?= $row['Id']; ?>" class="text-danger me-2" title="Supprimer">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </section>

    <div class="d-flex justify-content-end mb-3 text-center">
        <a href="logout.php" class="btn btn-danger">Déconnexion</a>
    </div>


    <!-- Modal pour l'ajout d'utilisateur -->
    <div class="modal fade" id="createModal" tabindex="-1" aria-labelledby="createModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="createModalLabel">Ajouter un utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="create_user.php" method="POST" enctype="multipart/form-data">
                        <div class="form-floating mb-3">
                            <input type="text" name="nom" class="form-control" required>
                            <label for="nom">Nom</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" name="prenom" class="form-control" required>
                            <label for="prenom">Prénom</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="email" name="email" class="form-control" required>
                            <label for="email">Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" name="password" class="form-control" required>
                            <label for="password">Mot de passe</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select name="role" class="form-select" required>
                                <option value="Admin">Admin</option>
                                <option value="Utilisateur">Utilisateur</option>
                            </select>
                            <label for="role">Rôle</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="file" name="image" class="form-control" required>
                            <label for="image">Image de profil</label>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal pour afficher les détails de l'utilisateur -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-labelledby="detailModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailModalLabel">Détails de l'utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <!-- Image de profil de l'utilisateur -->
                    <img id="detailImage" src="" alt="Image de profil" class="img-fluid mb-3" style="max-width: 100px; border-radius: 50%;">
                    <h5><strong>Nom :</strong> <span id="detailNom"></span></h5>
                    <h5><strong>Prenom :</strong> <span id="detailPrenom"></span></h5>
                    <p><strong>Email :</strong> <span id="detailEmail"></span></p>
                    <h5><strong>Role :</strong> <span id="detailRole"></span></h5>
                </div>
            </div>
        </div>
    </div>
    <!-- Modal pour la modification de l'utilisateur -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Modifier l'utilisateur</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="post" enctype="multipart/form-data">
                        <input type="hidden" name="id" id="editId">
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="editNom" name="nom" placeholder="Nom"></textarea>
                            <!-- <label for="editNom">Nom</label> -->
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="editPrenom" name="prenom" placeholder="Prenom"></textarea>
                            <label for="editPrenom">Prenom</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="editEmail" name="email" placeholder="Email"></textarea>
                            <label for="editEmail">Email</label>
                        </div>
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="editRoleP" readOnly></textarea>
                            <label for="editRoleP">Rôle actuel</label>
                        </div>
                        <div class="form-floating mb-3">
                            <select name="role" class="form-select" required id="editRole">
                                <option value="Admin">Admin</option>
                                <option value="Utilisateur">Utilisateur</option>
                            </select>
                            <label for="editRole">Rôle</label>
                        </div>
                        <div class="mb-3">
                            <label for="editImage">Image actuelle:</label><br>
                            <img id="editPreview" src="#" alt="Image" class="img-fluid mb-2" style="max-width: 200px;"><br>
                            <input type="file" name="image" id="editImage" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>



    <!-- Script AJAX pour les actions de détails et d'édition -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
    $(document).ready(function() {
        // Ouverture du modal de détails
        $('.detailBtn').on('click', function() {
            var id = $(this).data('id');

            // Récupérer les détails de l'utilisateur via AJAX
            $.ajax({
                url: 'get_user.php',
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (!response.error) {
                        $('#detailNom').text(response.Nom);
                        $('#detailPrenom').text(response.Prenom);
                        $('#detailEmail').text(response.Email);
                        $('#detailRole').text(response.Role);
                        console.log('Chemin de l\'image:', 'uploads/' + response.Chemin);
                        $('#detailImage').attr('src', response.Chemin); // Chemin vers l'image
                        $('#detailModal').modal('show');
                    } else {
                        alert(response.error);
                    }
                },
                error: function() {
                    alert("no recuperation");
                }
            });
        });

        // Ouverture du modal d'édition
        $('.editBtn').on('click', function() {
            var id = $(this).data('id');

            // Récupérer les informations de l'utilisateur via AJAX pour les remplir dans le modal
            $.ajax({
                url: 'get_user.php',
                type: 'GET',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (!response.error) {
                        $('#editNom').text(response.Nom);
                        // console.log(response.Nom);
                        $('#editPrenom').text(response.Prenom);
                        $('#editEmail').text(response.Email);
                        // $('#editRole').text(response.Role);
                        $('#editRoleP').text(response.Role);
                        $('#editPreview').attr('src', response.Chemin);
                        $('#editImage').attr('src', response.Chemin); // Chemin vers l'image
                        $('#editModal').modal('show');
                    } else {
                        alert(response.error);
                    }
                }
            })
        });

        $('#editForm').on('submit', function(e) {
            e.preventDefault();
            var formData = new FormData(this);

            $.ajax({
                url: 'update_user.php',
                type: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                dataType: 'json',
                success: function(response) {
                    if (response.error) {
                        alert(response.message); // Affiche le message d'erreur
                    } else {
                        $('#editModal').modal('hide');
                        // alert(response.message); // Message de succès
                        // location.reload(); // Recharger la page
                    }
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.error("Erreur lors de la connexion au serveur :", jqXHR.responseText);
                    alert("Erreur lors de la connexion au serveur : " + textStatus + " - " + errorThrown);
                }

            });
        });

    });  

    </script>
</body>
</html>
