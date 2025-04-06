<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Statistiques</title>
    <link rel="stylesheet" href="statistique.css">
</head>
<body>
    <?php include './../includes/navbar.php'; ?>

    <h1>Statistiques générales</h1>

    <div id="stats-generales">
        <p><strong>Matchs gagnés :</strong> <span id="nb_gagnes">...</span></p>
        <p><strong>Matchs perdus :</strong> <span id="nb_perdus">...</span></p>
        <p><strong>Matchs nuls :</strong> <span id="nb_nuls">...</span></p>
        <p><strong>% gagnés :</strong> <span id="pct_gagnes">...</span>%</p>
        <p><strong>% perdus :</strong> <span id="pct_perdus">...</span>%</p>
        <p><strong>% nuls :</strong> <span id="pct_nuls">...</span>%</p>
    </div>

    <h2>Statistiques des joueurs</h2>
    <table border="1" id="tableJoueurs">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Statut</th>
                <th>Titulaires</th>
                <th>Remplaçants</th>
                <th>Note moyenne</th>
                <th>% Victoires</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <script>
        const token = localStorage.getItem('token');

        fetch('http://localhost/projetManolo/back/statistiques/statistiques.php', {
            method: 'GET',
            headers: {
                "Authorization": "Bearer " + token
            }
        })
        .then(res => {
            if (!res.ok) throw new Error("Erreur " + res.status);
            return res.json();
        })
        .then(data => {
            // Statistiques générales
            document.getElementById('nb_gagnes').textContent = data.nb_matchs_gagnes ?? '0';
            document.getElementById('nb_perdus').textContent = data.nb_matchs_perdus ?? '0';
            document.getElementById('nb_nuls').textContent = data.nb_matchs_nuls ?? '0';
            document.getElementById('pct_gagnes').textContent = data.pourcentage_gagnes ?? '0';
            document.getElementById('pct_perdus').textContent = data.pourcentage_perdus ?? '0';
            document.getElementById('pct_nuls').textContent = data.pourcentage_nuls ?? '0';

            // Tableau joueurs
            const tbody = document.querySelector('#tableJoueurs tbody');
            tbody.innerHTML = '';
            data.statistiques_joueurs.forEach(joueur => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${joueur.Nom}</td>
                    <td>${joueur.Prenom}</td>
                    <td>${joueur.Statut}</td>
                    <td>${joueur.total_selections_titulaire ?? 0}</td>
                    <td>${joueur.total_selections_remplacant ?? 0}</td>
                    <td>${parseFloat(joueur.moyenne_evaluations).toFixed(2) ?? '0.00'}</td>
                    <td>${joueur.pourcentage_matchs_gagnes ?? 0}%</td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => {
            document.body.innerHTML += `<p style="color:red;">Erreur de chargement : ${error.message}</p>`;
        });
    </script>
</body>
</html>
