<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un joueur</title>
    <link rel="stylesheet" href="ajouter.css">
</head>
<body>
    <div class="container">
        <h1>Ajouter un joueur</h1>
        <form id="ajouterForm">
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
                    <input type="text" id="numero-licence" name="numero-licence" maxlength="9" required>
                </div>
                <div class="form-group">
                    <label for="date-naissance">Date de Naissance :</label>
                    <input type="date" id="date-naissance" name="date-naissance" required>
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label for="taille">Taille (en cm) :</label>
                    <input type="text" id="taille" name="taille" required>
                </div>
                <div class="form-group">
                    <label for="poids">Poids (en kg) :</label>
                    <input type="text" id="poids" name="poids" required>
                </div>
            </div>
            <div class="form-group">
                <label for="statut">Statut :</label>
                <select id="statut" name="statut" required>
                    <option value="Actif">Actif</option>
                    <option value="Blessé">Blessé</option>
                    <option value="Suspendu">Suspendu</option>
                </select>
            </div>
            <div class="form-actions">
                <input type="reset" value="Réinitialiser">
                <input type="submit" value="Ajouter">
            </div>
        </form>
    </div>

    <script>
        document.getElementById('ajouterForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const token = localStorage.getItem('token');

            const data = {
                nom: document.getElementById('nom').value,
                prenom: document.getElementById('prenom').value,
                numero_licence: document.getElementById('numero-licence').value,
                date_naissance: document.getElementById('date-naissance').value,
                taille: document.getElementById('taille').value,
                poids: document.getElementById('poids').value,
                statut: document.getElementById('statut').value
            };

            fetch('http://localhost/projetManolo/api/joueur/joueur_api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Authorization': 'Bearer ' + token
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(response => {
                console.log("Réponse JSON :", response);
                if (response.success) {
                    alert(response.message || "Joueur ajouté !");
                    window.location.href = '/projetManolo/front/joueur/joueur.php'; // ✅ redirection OK
                } else {
                    alert("Erreur serveur : " + (response.message || "Ajout impossible."));
                }
            })
            .catch(error => {
                console.error("Erreur JS ou réseau :", error);
                alert("Une erreur est survenue.");
            });
        });
    </script>

</body>
</html>
