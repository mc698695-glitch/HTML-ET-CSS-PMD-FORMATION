<?php
require_once "config.php";

// Initialisation des variables
$email = "";
$erreur = "";

// Vérifie si le formulaire a été envoyé
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Vérifie le jeton de sécurité CSRF
    verifierCsrf();

    // Récupère les données du formulaire
    $email = trim($_POST["email"] ?? "");
    $mot_de_passe = $_POST["mot_de_passe"] ?? "";

    // Vérifie que les données reçues sont des chaînes de caractères
    if (
        !is_string($email) ||
        !is_string($mot_de_passe) ||
        $email === "" ||
        $mot_de_passe === "" ||
        strlen($email) > 255 ||
        !filter_var($email, FILTER_VALIDATE_EMAIL)
    ) {
        $erreur = "E-mail ou mot de passe incorrect.";
    } else {

        // Recherche l'utilisateur avec son adresse e-mail
        $requete = $pdo->prepare(
            "SELECT id, nom, prenom, email, mot_de_passe
             FROM utilisateurs
             WHERE email = ?"
        );

        $requete->execute([$email]);
        $utilisateur = $requete->fetch(PDO::FETCH_ASSOC);

        // Vérifie le mot de passe
        if (
            $utilisateur &&
            password_verify(
                $mot_de_passe,
                $utilisateur["mot_de_passe"]
            )
        ) {
            // Renouvelle l'identifiant de session
            session_regenerate_id(true);

            // Enregistre les informations de l'utilisateur
            $_SESSION["utilisateur_id"] = (int) $utilisateur["id"];
            $_SESSION["utilisateur_nom"] = $utilisateur["prenom"];

            // Renouvelle le jeton CSRF
            $_SESSION["csrf_token"] = bin2hex(random_bytes(32));

            // Redirige vers la page des contacts
            header("Location: contacts.php");
            exit;
        } else {
            $erreur = "E-mail ou mot de passe incorrect.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

<main class="conteneur">
    <h1>Connexion</h1>

    <?php if ($erreur !== ""): ?>
        <p class="erreur"><?= e($erreur) ?></p>
    <?php endif; ?>

    <form method="post" action="">
        <input
            type="hidden"
            name="csrf_token"
            value="<?= e($_SESSION["csrf_token"] ?? "") ?>"
        >

        <label for="email">Adresse e-mail</label>
        <input
            type="email"
            id="email"
            name="email"
            maxlength="255"
            value="<?= e($email) ?>"
            required
            autocomplete="email"
        >

        <label for="mot_de_passe">Mot de passe</label>
        <input
            type="password"
            id="mot_de_passe"
            name="mot_de_passe"
            required
            autocomplete="current-password"
        >

        <button type="submit">Se connecter</button>
    </form>

    <p><a href="inscription.php">Créer un compte</a></p>
</main>

</body>
</html>