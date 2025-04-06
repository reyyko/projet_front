<?php
require __DIR__ .'/../../back/Requetes/RequeteFeuilledeMatch.php'; // Inclure le fichier contenant les fonctions SQL

// Vérification de l'ID du match
if (!isset($_GET['id'])) {
    echo "Aucun match sélectionné.";
    exit();
}

$id_match = (int)$_GET['id'];

try {
    // Récupérer les joueurs titulaires et remplaçants
    $joueurs = getJoueursByMatch($id_match);

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        foreach ($_POST['note'] as $id_joueur => $note) {
            updateNoteForJoueur($id_match, $id_joueur, $note);
        }
        header("Location: FeuilledeMatch.php?id=$id_match");
        exit();
    }
} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    exit();
}


?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="AttribuerNote.css">
    <title>Attribuer Notes - Match <?php echo $id_match; ?></title>
</head>
<body>
    <h1>Attribuer Notes</h1>

    <form method="POST" action="">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Poste</th>
                    <th>Statut</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($joueurs as $joueur): ?>
                    
                    <tr>
                        <td><?php echo htmlspecialchars($joueur['Nom']); ?></td>
                        <td><?php echo htmlspecialchars($joueur['Prenom']); ?></td>
                        <td><?php echo htmlspecialchars($joueur['Poste']); ?></td>
                        <td><?php echo $joueur['estTitulaire'] ? 'Titulaire' : 'Remplaçant'; ?></td>
                        <td>
                            <select name="note[<?php echo $joueur['Id_Joueur']; ?>]">
                                <?php for ($i = 0; $i <= 10; $i++): ?>
                                    <option value="<?php echo $i; ?>" <?php echo ($joueur['Note'] == $i) ? 'selected' : ''; ?>><?php echo $i; ?></option>
                                <?php endfor; ?>
                            </select>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
        <div class="actions">
            <button type="button" onclick="window.location.href='FeuilledeMatch.php?id=<?php echo $id_match; ?>';">Retour</button>
            <button type="submit">Enregistrer</button>
        </div>
    </form>
</body>
</html>
