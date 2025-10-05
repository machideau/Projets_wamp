import pandas as pd

# Création des DataFrames pour chaque classe
classe_1 = pd.DataFrame({
    "email_eleve": [
        "john.smith@email.com", "olivia.brown@email.com", "sophia.miller@email.com",
        "ethan.martinez@email.com", "amelia.gonzalez@email.com", "elijah.thomas@email.com",
        "lily.jackson@email.com", "samuel.perez@email.com", "ella.harris@email.com",
        "benjamin.walker@email.com"
    ],
    "note_classe": [14, 16, 15, 17, 13, 18, 14, 16, 17, 15],
    "note_devoir": [15, 17, 16, 18, 14, 19, 13, 15, 16, 14],
    "note_composition": [13, 15, 14, 16, 12, 17, 15, 14, 15, 13],
    "trimestre": [1] * 10
})

classe_2 = pd.DataFrame({
    "email_eleve": [
        "emma.johnson@email.com", "noah.jones@email.com", "lucas.davis@email.com",
        "isabella.hernandez@email.com", "logan.wilson@email.com", "matthew.martin@email.com",
        "chloe.thompson@email.com", "david.clark@email.com", "victoria.hall@email.com"
    ],
    "note_classe": [15, 17, 14, 16, 18, 13, 15, 14, 17],
    "note_devoir": [16, 18, 15, 17, 19, 14, 16, 13, 16],
    "note_composition": [14, 16, 13, 15, 17, 12, 14, 12, 15],
    "trimestre": [1] * 9
})

classe_3 = pd.DataFrame({
    "email_eleve": [
        "liam.williams@email.com", "ava.garcia@email.com", "mia.rodriguez@email.com",
        "mason.lopez@email.com", "harper.anderson@email.com", "daniel.moore@email.com",
        "scarlett.lee@email.com", "henry.white@email.com", "carter.allen@email.com",
        "grace.lewis@email.com"
    ],
    "note_classe": [14, 16, 17, 15, 18, 13, 16, 14, 17, 15],
    "note_devoir": [15, 17, 18, 16, 19, 14, 17, 13, 16, 14],
    "note_composition": [13, 15, 16, 14, 17, 12, 15, 12, 15, 13],
    "trimestre": [1] * 10
})

# Enregistrement en fichiers CSV
file_classe_1 = "classe_1.csv"
file_classe_2 = "classe_2.csv"
file_classe_3 = "classe_3.csv"

classe_1.to_csv(file_classe_1, index=False)
classe_2.to_csv(file_classe_2, index=False)
classe_3.to_csv(file_classe_3, index=False)

file_classe_1, file_classe_2, file_classe_3