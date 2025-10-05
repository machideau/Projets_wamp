<?php 
include 'header.php';
// require_once '..//config/database.php';
require_once '../controllers/inscriptionController.php';


$controller = new InscriptionController($db);
$controller->handleRequest();
?>

<div class="container">
    <h2>Créez votre compte enseignant</h2>
    <form method="POST">
        <div class="form-group">
            <label for="nom">Nom:</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="form-group">
            <label for="prenom">Prénom:</label>
            <input type="text" class="form-control" id="prenom" name="prenom" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" class="form-control" id="password" name="mot_de_passe" required>
        </div>
        <div class="form-group">
            <label for="telephone">Numéro de téléphone:</label>
            <input type="tel" class="form-control" id="telephone" name="telephone">
        </div>

        <!-- Sélection des classes -->
        <div class="form-group">
            <label for="classes">Classes:</label>
            <select class="form-control" id="optionSelect" name="classe_id" required>
                <option value="">Choisissez une classe</option>
                <?php foreach ($classes as $classe): ?>
                    <option value="<?= $classe['id'] ?>"><?= $classe['nom_classe'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <br>

        <!-- Sélection des matières -->
        <div class="form-group hidden" id="matiere">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Sélectionner</th>
                    </tr>
                </thead>
                <tbody id="matiere-list">
                    <!-- Les matières seront chargées ici dynamiquement via JavaScript -->
                </tbody>
            </table>
        </div>
        <a href="views/connexion.php" class="btn btn-link">S'inscrire</a>

        <button type="submit" class="btn btn-primary">S'inscrire</button>
    </form>
</div>

<script>
    document.getElementById('optionSelect').addEventListener('change', function() {
        const classeId = this.value;
        console.log(classeId);
        const matiereList = document.getElementById('matiere-list');

        if (classeId) {
            // Charger les matières via une requête AJAX
            fetch(`get_matieres.php?classe_id=${classeId}`)
                .then(response => response.json())
                .then(matieres => {
                    matiereList.innerHTML = '';
                    matieres.forEach(matiere => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${matiere.nom_matiere}</td>
                            <td><input type="checkbox" name="matieres[]" value="${matiere.id}"></td>
                        `;
                        matiereList.appendChild(row);
                    });
                    document.getElementById('matiere').classList.remove('hidden');
                });
        } else {
            document.getElementById('matiere').classList.add('hidden');
        }
    });
</script>

<?php include 'footer.php'; ?>