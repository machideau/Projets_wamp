document.addEventListener('DOMContentLoaded', function() {
    const goButton = document.getElementById('sigin');
  
    if (goButton) {
      goButton.addEventListener('click', function(event) {
        event.preventDefault(); // Empêche la redirection par défaut du lien
        window.location.href = 'inscription.php'; 
      });
    }
});
  