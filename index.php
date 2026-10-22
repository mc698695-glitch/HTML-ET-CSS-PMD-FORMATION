<?php
declare(strict_types=1);
session_start();
// En-têtes de sécurité
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'");
// Fonction pour supprimer les espaces et protéger l'affichage
function securite(string $valeur): string
{
    // Supprimer les espaces au début et à la fin
    $valeur = trim($valeur);
    // Protéger les caractères HTML spéciaux
    $valeur = htmlspecialchars($valeur, ENT_QUOTES, 'UTF-8');
    // Retourner la valeur traitée
    return $valeur;
}
// Initialisation des variables
$nom = "";
$prenom = "";
$email = "";
$erreur = "";
$enregistre = false;
// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des valeurs envoyées
    $nom = trim($_POST['nom'] ?? '');
    $prenom = trim($_POST['prenom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    // Vérification du nom
    if ($nom === '' || !preg_match("/^[\p{L} '’-]{2,50}$/u", $nom)) {
        $erreur = "Veuillez saisir un nom valide.";
    }
    // Vérification du prénom
    elseif ($prenom === '' || !preg_match("/^[\p{L} '’-]{2,50}$/u", $prenom)) {
        $erreur = "Veuillez saisir un prénom valide.";
    }
    // Vérification de l'adresse email
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $erreur = "Veuillez saisir une adresse email valide.";
    }
    // Si toutes les valeurs sont valides
    else {
        $enregistre = true;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Formulaire d'inscription</title>
</head>
<body>
    <h1>Formulaire d'inscription</h1>
    <!-- Affichage du message d'erreur -->
    <?php if ($erreur !== ''): ?>
        <p style="color:red;">
            <?= securite($erreur) ?>
        </p>
    <?php endif; ?>
    <!-- Affichage des informations si elles sont valides -->
    <?php if ($enregistre): ?>
        <h2>Informations saisies</h2>
        <p>Nom : <?= securite($nom) ?></p>
        <p>Prénom : <?= securite($prenom) ?></p>
        <p>Email : <?= securite($email) ?></p>
    <?php endif; ?>
    <!-- Formulaire d'inscription -->
    <form method="post" action="">
        <label for="nom">Nom *</label>
        <input
            type="text"
            id="nom"
            name="nom"
            minlength="2"
            maxlength="50"
            required
            value="<?= securite($nom) ?>"
        >
        <br><br>
        <label for="prenom">Prénom *</label>
        <input
            type="text"
            id="prenom"
            name="prenom"
            minlength="2"
            maxlength="50"
            required
            value="<?= securite($prenom) ?>"
        >
        <br><br>
        <label for="email">Email *</label>
        <input
            type="email"
            id="email"
            name="email"
            maxlength="254"
            required
            value="<?= securite($email) ?>"
        >
        <br><br>
        <button type="submit">Envoyer</button>
    </form>
</body>
</html>