<?php include 'header.php'; ?>

<div class="container">
    <h2>Créez votre compte enseignant</h2>
    <form method="POST" action="index.php?action=login">
    <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">Mot de passe:</label>
            <input type="password" class="form-control" name="password" id="password" required>
        </div>
        <br>
        
        <a class="btn btn-link" id="sigin">S'inscrire</a>
        

        <button type="submit" class="btn btn-primary">Se connecter</button>
    </form>

</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const goButton = document.getElementById('sigin');
  
        if (goButton) {
        goButton.addEventListener('click', function(event) {
            event.preventDefault(); // Empêche la redirection par défaut du lien
            window.location.href = 'views/inscription.php'; 
        });
        }
    });
  
</script>

<?php include 'footer.php'; ?>