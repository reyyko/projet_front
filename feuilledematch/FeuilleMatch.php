<?php
require __DIR__ . '/../../back/Requetes/RequeteFeuilledeMatch.php'; // Inclure le fichier contenant les fonctions SQL


try {
    // Connexion à la base de données
    $pdo = connectDB();
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupérer les informations de tous les matchs
    $stmt = $pdo->query("
        SELECT Id_Match_sport, Nom_equipe_adverse, Date_match
        FROM match_sport
        ORDER BY Date_match DESC
    ");
    $feuilles_de_match = $stmt->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    echo "Erreur : " . $e->getMessage();
    $feuilles_de_match = [];
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="FeuilleMatch.css">
    <title>Feuilles de Match</title>
</head>
<body>
    <?php include './../includes/navbar.php'; ?>
    <h1>Liste des Feuilles de Match</h1>
    <table>
        <thead>
            <tr>
                <th>ID Match</th>
                <th>Équipe Adverse</th>
                <th>Date</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($feuilles_de_match) > 0): ?>
                <?php foreach ($feuilles_de_match as $feuille): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($feuille['Id_Match_sport']); ?></td>
                        <td><?php echo htmlspecialchars($feuille['Nom_equipe_adverse']); ?></td>
                        <td><?php echo htmlspecialchars($feuille['Date_match']); ?></td>
                        <td>
                            <a href="FeuilledeMatch.php?id=<?php echo $feuille['Id_Match_sport']; ?>">Voir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="4" style="text-align: center;">Aucune feuille de match disponible.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
