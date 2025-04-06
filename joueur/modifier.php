<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un joueur</title>
    <link rel="stylesheet" href="modifier.css">
</head>
<body>
    <div class="container">
        <h1>Modifier un joueur</h1>
        <form id="modifierForm">
            <input type="hidden" name="id" id="joueurId">
            <div class="form-row">
                <div class="form-group">
                    <label for="nom">Nom :</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom :</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="numero-licence">Numéro Licence :</label>
                    <input type="text" id="numero-licence" name="numero-licence" required>
                </div>
                <div class="form-group">
                    <label for="date-naissance">Date de Naissance :</label>
                    <input type="date" id="date-naissance" name="date-naissance" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="taille">Taille :</label>
                    <input type="text" id="taille" name="taille" required>
                </div>
                <div class="form-group">
                    <label for="poids">Poids :</label>
                    <input type="text" id="poids" name="poids" required>
                </div>
            </div>
            <div class="full-width">
                <label for="statut">Statut :</label>
                <select id="statut" name="statut" required>
                    <option value="Actif">Actif</option>
                    <option value="Blessé">Blessé</option>
                    <option value="Suspendu">Suspendu</option>
                </select>
            </div>
            <div class="form-actions">
                <input type="submit" value="Modifier">
                <a href="joueur.php">Annuler</a>
            </div>
        </form>
    </div>

    <script>
        // Fonction pour récupérer les données du joueur via l'API
        const joueurId = new URLSearchParams(window.location.search).get('id'); // Récupérer l'ID du joueur depuis l'URL

        fetch(`http://localhost/projetManolo/api/joueur/joueur_api.php?id=${joueurId}`, {
                headers: {
                    'Authorization': 'Bearer ' + localStorage.getItem('token')
                }
            })
            .then(res => res.json())
            .then(joueur => {
                document.getElementById('joueurId').value = joueur.Id_Joueur;
                document.getElementById('nom').value = joueur.Nom;
                document.getElementById('prenom').value = joueur.Prenom;
                document.getElementById('numero-licence').value = joueur.Numero_Licence;
                const dateRaw = joueur.Date_de_Naissance;

                    let dateFormatee = "";
                    if (dateRaw && dateRaw !== "0000-00-00") {
                        const date = new Date(dateRaw);
                        // Vérifie si la date est valide
                        if (!isNaN(date)) {
                            dateFormatee = date.toISOString().split('T')[0]; // yyyy-mm-dd
                        } else {
                            dateFormatee = dateRaw; // fallback brut
                        }
                    }

                    document.getElementById('date-naissance').value = dateFormatee;


                document.getElementById('taille').value = joueur.Taille;
                document.getElementById('poids').value = joueur.Poids;
                document.getElementById('statut').value = joueur.Statut;
            })
            .catch(error => console.error("Erreur récupération joueur :", error));


        // Fonction pour envoyer la requête PUT lorsque le formulaire est soumis
            document.getElementById('modifierForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Empêcher le comportement par défaut du formulaire (rechargement de la page)

            const data = {
                id: document.querySelector('input[name="id"]').value,
                nom: document.querySelector('input[name="nom"]').value,
                prenom: document.querySelector('input[name="prenom"]').value,
                numero_licence: document.querySelector('input[name="numero-licence"]').value,
                date_naissance: document.querySelector('input[name="date-naissance"]').value,
                taille: document.querySelector('input[name="taille"]').value,
                poids: document.querySelector('input[name="poids"]').value,
                statut: document.querySelector('select[name="statut"]').value
            };

            fetch('/projetManolo/api/joueur/joueur_api.php', {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + localStorage.getItem('token') // ✅ ajoute ça aussi ici
                },
                body: JSON.stringify(data)
            })

            .then(response => response.json())
            .then(data => {
                alert(data.message);  // Affiche le message de l'API
                window.location.href = 'joueur.php';  // Redirige vers la page des joueurs après modification
            })
            .catch(error => console.error('Erreur:', error));
        });
    </script>
</body>
</html>
