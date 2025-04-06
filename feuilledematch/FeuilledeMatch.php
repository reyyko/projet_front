<?php
require __DIR__ . '/../../back/Requetes/RequeteFeuilledeMatch.php'; // Inclure le fichier contenant les fonctions SQL

// Vérification de l'ID du match
if (!isset($_GET['id'])) {
    echo "Aucun match sélectionné.";
    exit();
}

$id_match = (int)$_GET['id'];

// Récupérer les données via les fonctions
$match = getMatchById($id_match);
if (!$match) {
    echo "Match introuvable.";
    exit();
}

$joueurs_disponibles = getJoueursDisponibles($id_match);
$joueurs_titulaires = getJoueursTitulaires($id_match);
$joueurs_remplacants = getJoueursRemplacants($id_match);

// Gestion du formulaire pour ajouter ou retirer des joueurs
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['ajouter'])) {
            $id_joueur = (int)$_POST['ajouter'];
            $poste = $_POST['poste'][$id_joueur] ?? null;
            $estTitulaire = isset($_POST['titulaire'][$id_joueur]) ? 1 : 0;

            // Vérifications avant l'ajout comme titulaire
            if ($estTitulaire === 1) {
                // Vérifier le nombre total de titulaires
                $nombreTotalTitulaires = getNombreTitulaires($id_match);

                if ($nombreTotalTitulaires >= 7) {
                    echo "<script>alert('Limite de 7 titulaires atteinte. Impossible d\'ajouter plus de joueurs titulaires.');</script>";
                } else {
                    // Vérifier les limites par poste
                    $limitesPostes = [
                        'Gardien' => 1,
                        'Meneur' => 1,
                        'Pivot' => 1,
                        'Arrière' => 2,
                        'Ailier' => 2
                    ];

                    if (isset($limitesPostes[$poste])) {
                        $nombreTitulairesPoste = getNombreTitulairesParPoste($id_match, $poste);

                        if ($nombreTitulairesPoste >= $limitesPostes[$poste]) {
                            echo "<script>alert('Limite atteinte pour le poste de $poste.');</script>";
                        } else {
                            // Ajouter ou mettre à jour le joueur
                            ajouterOuMettreAJourJoueur($id_match, $id_joueur, $poste, $estTitulaire);
                        }
                    } else {
                        echo "<script>alert('Poste non valide.');</script>";
                    }
                }
            } else {
                // Ajouter en tant que remplaçant
                ajouterOuMettreAJourJoueur($id_match, $id_joueur, $poste, $estTitulaire);
            }
        }

        // Retirer un joueur des titulaires ou remplaçants
        if (isset($_POST['retirer'])) {
            $id_joueur = (int)$_POST['retirer'];
            retirerJoueur($id_match, $id_joueur);
        }

        header("Location: FeuilledeMatch.php?id=$id_match");
        exit();
    } catch (PDOException $e) {
        echo "Erreur lors de l'enregistrement : " . $e->getMessage();
    }
    echo '<script>
        setTimeout(() => {
            window.location.href = "FeuilledeMatch.php?id=' . $id_match . '";
        }, 2000); // Redirection après 3 secondes
    </script>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="FeuilledeMatch.css">
    <title>Feuille de Match - <?php echo htmlspecialchars($match['Nom_equipe_adverse']); ?></title>
</head>
<body>
    <h1>Feuille de Match - <?php echo htmlspecialchars($match['Nom_equipe_adverse']); ?></h1>

    <!-- Joueurs Disponibles -->
    <h2>Joueurs Disponibles</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Taille</th>
                <th>Poids</th>
                <th>Commentaire</th>
                <th>Poste</th>
                <th>Titulaire</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($joueurs_disponibles as $joueur): ?>
                <tr>
                    <td><?= htmlspecialchars($joueur['Nom']); ?></td>
                    <td><?= htmlspecialchars($joueur['Prenom']); ?></td>
                    <td><?= htmlspecialchars($joueur['Taille']); ?></td>
                    <td><?= htmlspecialchars($joueur['Poids']); ?></td>
                    <td><?= htmlspecialchars($joueur['Commentaire']); ?></td>
                    <form method="POST" action="FeuilledeMatch.php?id=<?= $id_match; ?>">
                    <td>
                            <select name="poste[<?= $joueur['Id_Joueur']; ?>]" required>
                                <option value="">Choisir</option>
                                <option value="Gardien">Gardien</option>
                                <option value="Meneur">Meneur</option>
                                <option value="Pivot">Pivot</option>
                                <option value="Ailier">Ailier</option>
                                <option value="Arrière">Arrière</option>
                            </select>
                        </td>
                        <td>
                            <input type="checkbox" name="titulaire[<?= $joueur['Id_Joueur']; ?>]">
                        </td>
                        <td>
                            <button type="submit" name="ajouter" value="<?= $joueur['Id_Joueur']; ?>">Ajouter</button>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Joueurs Titulaires</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Poste</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($joueurs_titulaires as $titulaire): ?>
                <tr>
                    <td><?php echo htmlspecialchars($titulaire['Nom']); ?></td>
                    <td><?php echo htmlspecialchars($titulaire['Prenom']); ?></td>
                    <td><?php echo htmlspecialchars($titulaire['Poste']); ?></td>
                    <td>
                        <form method="POST" action="FeuilledeMatch.php?id=<?php echo $id_match; ?>">
                            <button type="submit" name="retirer" value="<?php echo $titulaire['Id_Joueur']; ?>">Retirer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <h2>Joueurs Remplaçants</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Poste</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($joueurs_remplacants as $remplacant): ?>
                <tr>
                    <td><?php echo htmlspecialchars($remplacant['Nom']); ?></td>
                    <td><?php echo htmlspecialchars($remplacant['Prenom']); ?></td>
                    <td><?php echo htmlspecialchars($remplacant['Poste']); ?></td>
                    <td>
                        <form method="POST" action="FeuilledeMatch.php?id=<?php echo $id_match; ?>">
                            <button type="submit" name="retirer" value="<?php echo $remplacant['Id_Joueur']; ?>">Retirer</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="actions">
        <button class="back" onclick="window.location.href='FeuilleMatch.php';">Retour</button>
        <button class="note" onclick="window.location.href='AttribuerNote.php?id=<?php echo $id_match; ?>';">Attribuer Note</button>
    </div>
</body>
</html>
