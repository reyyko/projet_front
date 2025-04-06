<?php
    require_once __DIR__ . '/../back/Authentification/session.php';
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>Authentification</title>
        <link rel="stylesheet" href="index.css">
    </head>
    <body>
        <form id="loginForm">
        <label for="login">Identifiant :</label><br>
        <input type="text" id="login" placeholder="Identifiant"><br>

        <label for="password">Mot de passe :</label><br>
        <input type="password" id="password" placeholder="Mot de passe"><br>

        <button type="submit">Se connecter</button>
        <p id="error" style="color:red;"></p>
        </form>

        <script>
            document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault(); // Empêche la soumission classique du form

            const login = document.getElementById('login').value;
            const password = document.getElementById('password').value;

            fetch('http://localhost/projetManolo/back/Authentification/authentificationOperation.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ login, password })
            })
            .then(res => res.json())
            .then(data => {
                if (data.token) {
                    localStorage.setItem('token', data.token); // Stockage dans localStorage 
                    window.location.href = 'Statistiques/statistiques.php'; // Redirection vers la page sécurisée
                } else {
                    document.getElementById('error').textContent = data.message || "Erreur d'authentification.";
             }
            })
            .catch(error => {
                console.error('Erreur :', error);
                document.getElementById('error').textContent = "Erreur de connexion au serveur.";
            });
        });
        </script>

    </body>
</html>

<?php
    if(isset($_SESSION['message'])){
        echo $_SESSION['message'];
        unset($_SESSION['message']);
    }
?>