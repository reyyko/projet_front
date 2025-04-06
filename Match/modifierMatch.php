<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Modifier un match</title>
    <link rel="stylesheet" href="match.css">
</head>
<body>
    <h1>Modifier un match</h1>
    <form id="formModif">
        <input type="hidden" name="id" id="id">

        <label for="dateMatch">Date :</label><br>
        <input type="date" name="dateMatch" id="dateMatch" required><br>

        <label for="time">Heure :</label><br>
        <input type="time" name="time" id="time" required><br>

        <label for="placeMeeting">Lieu de rencontre :</label><br>
        <input type="text" name="placeMeeting" id="placeMeeting" placeholder="ex: terrain de handball" required><br>

        <label for="opponent">Équipe adverse :</label><br>
        <input type="text" name="opponent" id="opponent" placeholder="ex: Les Tigres" required><br>

        <label for="result">Résultat :</label><br>
        <input type="text" name="result" id="result" placeholder="ex: 11-7"><br><br>

        <button type="button" onclick="window.location.href='match.php'">Annuler</button>
        <button type="submit" id="modifierBtn">Modifier</button>

    </form>

    <script>
        const token = localStorage.getItem("token");
        const id = new URLSearchParams(window.location.search).get('id');

        if (!token) {
            alert("Token manquant !");
            window.location.href = "../index.php";
        }

        // 🔹 Remplir les champs avec les données existantes
        fetch(`http://localhost/projetManolo/api/match/match.php?id=${id}`, {
            headers: {
                'Authorization': 'Bearer ' + token
            }
        })
        .then(res => res.json())
        .then(data => {
            document.getElementById("id").value = id;
            document.getElementById("dateMatch").value = data.Date_match;
            document.getElementById("time").value = data.Heure_Match;
            document.getElementById("placeMeeting").value = data.Lieu_de_rencontre;
            document.getElementById("opponent").value = data.Nom_equipe_adverse;
            document.getElementById("result").value = data.Resultat ?? '';
        })
        .catch(err => console.error("Erreur chargement match :", err));

        // 🔹 Envoyer les données modifiées
        document.getElementById("formModif").addEventListener("submit", function(e) {
            e.preventDefault();

            const matchData = {
                id: id,
                dateMatch: document.getElementById("dateMatch").value,
                time: document.getElementById("time").value,
                placeMeeting: document.getElementById("placeMeeting").value,
                opponent: document.getElementById("opponent").value,
                result: document.getElementById("result").value
            };

            fetch("http://localhost/projetManolo/api/match/modifierMatch.php", {
                method: "PUT",
                headers: {
                    "Content-Type": "application/json",
                    "Authorization": "Bearer " + token
                },
                body: JSON.stringify(matchData)
            })
            .then(res => res.json())
            .then(response => {
                alert(response.message);
                window.location.href = "match.php";
            })
            .catch(err => console.error("Erreur modification :", err));
        });
    </script>
</body>
</html>
