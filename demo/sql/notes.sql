CREATE TABLE IF NOT EXISTS notes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    eleve_id INT NOT NULL,
    matiere_id INT NOT NULL,
    note_classe DECIMAL(4,2),
    note_devoir DECIMAL(4,2),
    note_composition DECIMAL(4,2),
    trimestre INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (eleve_id) REFERENCES eleves(id),
    FOREIGN KEY (matiere_id) REFERENCES matieres(id)
);