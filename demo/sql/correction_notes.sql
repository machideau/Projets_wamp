-- Créer une table temporaire avec la même structure que notes
CREATE TEMPORARY TABLE temp_notes LIKE notes;
INSERT INTO temp_notes SELECT * FROM notes;

-- Désactiver temporairement les vérifications de clés étrangères
SET FOREIGN_KEY_CHECKS = 0;

-- Vider la table notes
TRUNCATE TABLE notes;

-- Réactiver les vérifications
SET FOREIGN_KEY_CHECKS = 1;

-- Insérer les notes avec les bons IDs d'élèves
INSERT INTO notes (eleve_id, matiere_id, note_classe, note_devoir, note_composition, trimestre)
SELECT DISTINCT
    COALESCE(e.id, tn.eleve_id) as eleve_id,
    tn.matiere_id,
    tn.note_classe,
    tn.note_devoir,
    tn.note_composition,
    tn.trimestre
FROM temp_notes tn
LEFT JOIN eleves e ON tn.eleve_id = e.email
WHERE 
    -- Vérifier que l'élève existe
    (e.id IS NOT NULL OR EXISTS (SELECT 1 FROM eleves WHERE id = tn.eleve_id))
    -- Vérifier que la matière existe
    AND EXISTS (SELECT 1 FROM matieres WHERE id = tn.matiere_id)
    -- Éviter les doublons selon la contrainte unique
    AND NOT EXISTS (
        SELECT 1 
        FROM notes n 
        WHERE n.eleve_id = COALESCE(e.id, tn.eleve_id)
        AND n.matiere_id = tn.matiere_id 
        AND n.trimestre = tn.trimestre
    );

-- Supprimer la table temporaire
DROP TEMPORARY TABLE temp_notes;

-- Vérifier les résultats
SELECT 
    COUNT(*) as total_notes,
    COUNT(DISTINCT eleve_id) as total_eleves,
    COUNT(DISTINCT matiere_id) as total_matieres
FROM notes;
