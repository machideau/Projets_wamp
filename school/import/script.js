const uploadForm = document.getElementById('uploadForm');
const progressContainer = document.getElementById('progressContainer');
const progressBar = document.getElementById('progressBar');


document.getElementById('uploadForm').addEventListener('submit', function (e) {
    e.preventDefault();

    progressContainer.classList.remove('d-none');
    simulateUpload();

    const fileInput = document.getElementById('csvFile');
    const file = fileInput.files[0];

    if (file) {
        const formData = new FormData();
        formData.append('csvFile', file);

        fetch('/upload', {
            method: 'POST',
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            document.getElementById('message').textContent = data.message;
        })
        .catch(error => {
            console.error('Erreur:', error);
            document.getElementById('message').textContent = 'Une erreur est survenue.';
        });
    } else {
        document.getElementById('message').textContent = 'Veuillez sélectionner un fichier CSV.';
    }
});

function simulateUpload() {
    let progress = 0;
    const interval = setInterval(() => {
        progress += 10;
        progressBar.style.width = `${progress}%`;
        progressBar.setAttribute('aria-valuenow', progress);

        if (progress >= 110) {
            clearInterval(interval);
            setTimeout(() => {
                progressContainer.classList.add('d-none');
                // alert('Téléchargement terminé !');
                document.getElementById('message').textContent = 'Téléchargement terminé !';
            }, 500);
        }
    }, 500);
}