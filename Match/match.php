<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Matchs</title>
    <link rel="stylesheet" href="match.css">
</head>
<body>
    <?php include './../includes/navbar.php'; ?>

    <input type="search" id="search" placeholder="Rechercher une date, équipe ou lieu">
    <button onclick="loadMatchs()">Rechercher</button>
    <button onclick="window.location.href='ajouterMatch.html'">Ajouter un match</button>

    <h1>Liste des matchs</h1>
    <table border="1" id="matchTable">
        <thead>
            <tr>
                <th>Date</th>
                <th>Heure</th>
                <th>Équipe adverse</th>
                <th>Lieu</th>
                <th>Résultat</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <script>
        const token = localStorage.getItem('token');

        function loadMatchs() {
            const recherche = document.getElementById('search').value;
            let url = 'http://localhost/projetManolo/api/match/match.php';
            if (recherche) {
                url += '?recherche=' + encodeURIComponent(recherche);
            }

            fetch(url, {
                headers: {
                    "Authorization": "Bearer " + token
                }
            })
            .then(res => res.json())
            .then(data => {
                const tbody = document.querySelector('#matchTable tbody');
                tbody.innerHTML = '';

                data.forEach(row => {
                    const tr = document.createElement('tr');
                    tr.innerHTML = `
                        <td>${row.Date_match}</td>
                        <td>${row.Heure_Match}</td>
                        <td><a href="./../feuilledematch/FeuilledeMatch.php?id=${row.Id_Match_sport}">${row.Nom_equipe_adverse}</a></td>
                        <td>${row.Lieu_de_rencontre}</td>
                        <td>${row.Resultat ?? ''}</td>
                        <td>
                            <a href="modifierMatch.php?id=${row.Id_Match_sport}">Modifier</a> |
                            <a href="#" onclick="supprimerMatch(${row.Id_Match_sport})">Supprimer</a>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });
            });
        }

        function supprimerMatch(id) {
            if (confirm("Supprimer ce match ?")) {
                fetch('http://localhost/projetManolo/api/match/match.php', {
                    method: 'DELETE',
                    headers: {
                        "Authorization": "Bearer " + token,
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ id })

                })
                .then(res => res.json())
                .then(() => loadMatchs());
            }
        }

        loadMatchs(); // chargement initial
    </script>
</body>
</html>
