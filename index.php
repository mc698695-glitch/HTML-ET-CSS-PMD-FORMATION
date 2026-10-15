<?php
$erreur = "";
$nom = "";
$prenom = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom"] ?? "");
    $prenom = trim($_POST["prenom"] ?? "");
    $email = trim($_POST["email"] ?? "");

    if ($nom === "" || $prenom === "" || $email === "") {
        $erreur = "Tous les champs sont obligatoires.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "Adresse e-mail invalide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Formulaire PHP</title>
</head>
<body>

<h2>Formulaire d'inscription</h2>

<?php if ($erreur !== ""): ?>
    <p>
        <?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?>
    </p>
<?php elseif ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
    <h3>Informations enregistrées</h3>
    <p>Nom : <?= htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') ?></p>
    <p>Prénom : <?= htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8') ?></p>
    <p>Email : <?= htmlspecialchars($email, ENT_QUOTES, 'UTF-8') ?></p>
<?php endif; ?>

<form method="POST" action="">
    <label>Nom :</label>
    <input type="text" name="nom" required>
    <br><br>

    <label>Prénom :</label>
    <input type="text" name="prenom" required>
    <br><br>

    <label>Email :</label>
    <input type="email" name="email" required>
    <br><br>

    <button type="submit">Envoyer</button>
</form>

</body>
</html>