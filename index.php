<?php
// Déclaration des variables
$erreur = "";
$nom = "";
$prenom = "";
$email = "";

// Vérification de l'envoi du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Vérification de l'existence des champs
    if (isset($_POST["nom"]) && isset($_POST["prenom"]) && isset($_POST["email"])) {

        // Récupération et nettoyage des données
        $nom = trim($_POST["nom"]);
        $prenom = trim($_POST["prenom"]);
        $email = trim($_POST["email"]);

        // Vérification des champs obligatoires
        if ($nom === "" || $prenom === "" || $email === "") {
            $erreur = "Tous les champs sont obligatoires.";

        // Vérification du format de l'email
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $erreur = "Adresse e-mail invalide.";
        }
    } else {
        $erreur = "Tous les champs doivent être remplis.";
    }

    // Sécurisation des données avant leur affichage
    $nom = htmlspecialchars($nom, ENT_QUOTES, 'UTF-8');
    $prenom = htmlspecialchars($prenom, ENT_QUOTES, 'UTF-8');
    $email = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
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

    <!-- Affichage du message d'erreur -->
    <?php if ($erreur !== ""): ?>
        <p><?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?></p>

    <!-- Affichage des informations validées -->
    <?php elseif ($_SERVER["REQUEST_METHOD"] === "POST"): ?>
        <h3>Informations enregistrées</h3>
        <p>Nom : <?= $nom ?></p>
        <p>Prénom : <?= $prenom ?></p>
        <p>Email : <?= $email ?></p>
    <?php endif; ?>

    <!-- Formulaire de saisie -->
    <form method="POST" action="">

        <div>
            <label for="prenom">Prénom *</label>
            <input type="text" name="prenom" id="prenom"
                   placeholder="Entrez votre prénom"
                   minlength="2" maxlength="100"
                   autocomplete="given-name" required>
        </div>

        <div>
            <label for="nom">Nom *</label>
            <input type="text" name="nom" id="nom"
                   placeholder="Entrez votre nom"
                   maxlength="100"
                   autocomplete="family-name" required>
        </div>

        <div>
            <label for="email">Email *</label>
            <input type="email" name="email" id="email"
                   placeholder="Entrez votre email"
                   maxlength="254"
                   autocomplete="email" required>
        </div>

        <button type="submit">Envoyer</button>
        <button type="reset">Effacer</button>

    </form>

</body>
</html>