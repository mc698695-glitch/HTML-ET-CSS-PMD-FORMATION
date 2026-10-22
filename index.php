<?php
declare(strict_types=1);
session_start();
// En-têtes de sécurité
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'");
// Fonction pour supprimer les espaces et sécuriser l'affichage
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
$motDePasseHache = "";
// Traitement du formulaire d'inscription
if (
    $_SERVER['REQUEST_METHOD'] === 'POST'
    && isset($_POST['form_inscription'])
) {
    // Récupération des valeurs saisies
    $nom = securite($_POST['nom'] ?? '');
    $prenom = securite($_POST['prenom'] ?? '');
    $email = securite($_POST['email'] ?? '');
    $motDePasse = $_POST['mot_de_passe'] ?? '';
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
    // Vérification du mot de passe
    elseif (strlen($motDePasse) < 12) {
        $erreur = "Le mot de passe doit contenir au moins 12 caractères.";
    }
    // Si toutes les valeurs sont valides
    else {
        // Hacher le mot de passe
        $motDePasseHache = password_hash(
            $motDePasse,
            PASSWORD_DEFAULT
        );
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
        <p style="color: red;">
            <?= securite($erreur) ?>
        </p>
    <?php endif; ?>
    <!-- Affichage des informations validées -->
    <?php if ($enregistre): ?>
        <h2>Inscription validée</h2>
        <p>Nom : <?= $nom ?></p>
        <p>Prénom : <?= $prenom ?></p>
        <p>Email : <?= $email ?></p>
        <p>Le mot de passe a été haché avec succès.</p>
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
            value="<?= $nom ?>"
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
            value="<?= $prenom ?>"
        >
        <br><br>
        <label for="email">Email *</label>
        <input
            type="email"
            id="email"
            name="email"
            maxlength="254"
            required
            value="<?= $email ?>"
        >
        <br><br>
        <label for="mot_de_passe">Mot de passe *</label>
        <input
            type="password"
            id="mot_de_passe"
            name="mot_de_passe"
            minlength="6"
            maxlength="72"
            required
        >
        <br><br>
        <!-- Identification du formulaire -->
        <button
            type="submit"
            name="form_inscription"
            value="1"
        >
            Envoyer
        </button>
    </form>
</body>
</html>