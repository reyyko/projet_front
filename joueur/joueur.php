<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion des joueurs</title>
    <link rel="stylesheet" href="joueur.css">
</head>
<body>
    <?php include './../includes/navbar.php'; ?>

    <h1>Liste des joueurs</h1>

    <form action="ajouter.php" method="get">
        <button type="submit">Ajouter un joueur</button>
    </form>

    <table id="joueursTable">
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Licence</th>
                <th>Date Naissance</th>
                <th>Taille</th>
                <th>Poids</th>
                <th>Statut</th>
                <th>Commentaire</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody></tbody>
    </table>

    <script>
        const token = localStorage.getItem('token');
        if (!token) {
            alert("Veuillez vous reconnecter.");
            window.location.href = "../index.php";
        }

        // Charger les joueurs
        fetch('http://localhost/projetManolo/api/joueur/joueur_api.php', {
            method: 'GET',
            headers: {
                "Authorization": "Bearer " + token
            }
        })
        .then(res => {
            if (!res.ok) throw new Error("Erreur " + res.status);
            return res.json();
        })
        .then(joueurs => {
            const tableBody = document.querySelector('#joueursTable tbody');
            if (joueurs.length > 0) {
                joueurs.forEach(joueur => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${joueur.Id_Joueur}</td>
                        <td>${joueur.Nom}</td>
                        <td>${joueur.Prenom}</td>
                        <td>${joueur.Numero_Licence}</td>
                        <td>${joueur.Date_de_Naissance}</td>
                        <td>${joueur.Taille}</td>
                        <td>${joueur.Poids}</td>
                        <td>${joueur.Statut}</td>
                        <td>
                            <div id="commentaireTexte_${joueur.Id_Joueur}">${joueur.Commentaire || ''}</div>
                            <form onsubmit="ajouterCommentaire(event, ${joueur.Id_Joueur})">
                                <input type="text" id="commentaire_${joueur.Id_Joueur}" placeholder="Ajouter un commentaire" required>
                                <button type="submit">Enregistrer</button>
                            </form>
                        </td>
                        <td>
                            <button onclick="supprimerJoueur(${joueur.Id_Joueur})">Supprimer</button>
                           <form method="get" action="modifier.php" style="margin-top: 5px;">
                                <input type="hidden" name="id" value="${joueur.Id_Joueur}">
                                <button type="submit">Modifier</button>
                            </form>

                        </td>
                    `;
                    tableBody.appendChild(row);
                });
            } else {
                const row = document.createElement('tr');
                row.innerHTML = "<td colspan='10'>Aucun joueur trouvé.</td>";
                tableBody.appendChild(row);
            }
        })
        .catch(error => {
            document.body.innerHTML += `<p style="color:red;">Erreur de chargement : ${error.message}</p>`;
        });

        // Ajouter un commentaire
        function ajouterCommentaire(event, id) {
            event.preventDefault();
            const commentaire = document.getElementById(`commentaire_${id}`).value;

            fetch('http://localhost/projetManolo/api/joueur/ajouter_commentaire.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    id_joueur: id,
                    commentaire: commentaire
                })
            })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    alert(data.message);
                    // 🟢 Mise à jour en direct du commentaire affiché
                    document.getElementById(`commentaireTexte_${id}`).innerText = commentaire;
                    document.getElementById(`commentaire_${id}`).value = ''; // Réinitialise le champ
                } else {
                    alert("Erreur : " + data.message);
                }
            })
            .catch(err => {
                console.error("Erreur commentaire :", err);
                alert("Une erreur est survenue.");
            });
        }



        // Supprimer un joueur
        function supprimerJoueur(id) {

            
            const token = localStorage.getItem("token");

            fetch("http://localhost/projetManolo/api/joueur/supprimer_api.php", {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + token
                },
                body: JSON.stringify({ id: id })
            })
            .then(res => res.json())
            .then(data => {
                        if (data.success) {
                location.reload(); // ✅ suppression OK, on recharge la page
            } else {
                console.warn("Suppression échouée (mais silencieuse) :", data.message);
                // 👉 ne rien afficher, mais tu peux aussi faire : alert("Suppression impossible");
                location.reload();
            }})
            .catch(error => {
                console.error("Erreur JS ou réseau :", error);
                alert("Une erreur est survenue.");
            });
        }


    </script>
</body>
</html>
