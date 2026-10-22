<?php
declare(strict_types=1);
session_start();
// En-têtes de sécurité
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'");
// Fonction pour supprimer les espaces et protéger l'affichage
function e(string $s): string
{
    return htmlspecialchars(trim($s), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
// Initialisation des variables
$nom = "";
$prenom = "";
$email = "";
$erreur = "";
$enregistre = false;
// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupération des valeurs
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
    // Vérification de l'email
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
    <?php if ($erreur !== ''): ?>
        <p style="color:red;">
            <?= e($erreur) ?>
        </p>
    <?php endif; ?>
    <?php if ($enregistre): ?>
        <h2>Informations saisies</h2>
        <p>Nom : <?= e($nom) ?></p>
        <p>Prénom : <?= e($prenom) ?></p>
        <p>Email : <?= e($email) ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label for="nom">Nom *</label>
        <input type="text" id="nom" name="nom"
               minlength="2" maxlength="50" required
               value="<?= e($nom) ?>">
        <br><br>
        <label for="prenom">Prénom *</label>
        <input type="text" id="prenom" name="prenom"
               minlength="2" maxlength="50" required
               value="<?= e($prenom) ?>">
        <br><br>
        <label for="email">Email *</label>
        <input type="email" id="email" name="email"
               maxlength="254" required
               value="<?= e($email) ?>">
        <br><br>
        <button type="submit">Envoyer</button>
        
    </form>
</body>
</html>