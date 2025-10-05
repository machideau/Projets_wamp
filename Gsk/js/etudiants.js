document.addEventListener('DOMContentLoaded', () => {
    const LS_STUDENTS_KEY = 'ecole_app_students';
    const LS_TEACHERS_KEY = 'ecole_app_teachers'; // À définir dans enseignants.js
    const LS_COURSES_KEY = 'ecole_app_courses';   // À définir dans cours.js

    function getCount(key) {
        const items = JSON.parse(localStorage.getItem(key)) || [];
        return items.length;
    }

    const totalEtudiantsEl = document.getElementById('total-etudiants');
    const totalEnseignantsEl = document.getElementById('total-enseignants');
    const totalCoursEl = document.getElementById('total-cours');

    if (totalEtudiantsEl) totalEtudiantsEl.textContent = getCount(LS_STUDENTS_KEY);
    if (totalEnseignantsEl) totalEnseignantsEl.textContent = getCount(LS_TEACHERS_KEY); // Sera 0 si enseignants.js pas encore implémenté
    if (totalCoursEl) totalCoursEl.textContent = getCount(LS_COURSES_KEY);     // Sera 0 si cours.js pas encore implémenté

    // Vous pouvez ajouter ici d'autres logiques pour le tableau de bord
});