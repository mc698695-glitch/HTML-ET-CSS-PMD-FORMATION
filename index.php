<?php
declare(strict_types=1);
session_start();

// En-têtes de sécurité
header('X-Frame-Options: DENY');
header('X-Content-Type-Options: nosniff');
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline'");

// Jeton CSRF
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

// Supprime les espaces inutiles et protège l'affichage contre les attaques XSS
function e(string $s): string
{
    return htmlspecialchars(trim($s), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}
// Validation d'un nom / prénom : lettres (accents inclus), espaces, - et ' uniquement. Aucun chiffre.
function validerNom(string $valeur, string $label, array &$errors, string $cle): void
{
    $len = mb_strlen($valeur);
    if ($valeur === '') {
        $errors[$cle] = "$label est obligatoire.";
    } elseif ($len < 2 || $len > 50) {
        $errors[$cle] = "$label doit contenir entre 2 et 50 caractères.";
    } elseif (!preg_match('/^\p{L}+(?:[ \'’-]\p{L}+)*$/u', $valeur)) {
        $errors[$cle] = "$label ne doit contenir que des lettres (pas de chiffres ni de symboles).";
    }
}

$errors = [];
$old = ['prenom' => '', 'nom' => '', 'email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST'
    && ($_POST['form_id'] ?? '') === 'inscription') {
    // Vérification CSRF
    $token = $_POST['csrf'] ?? '';
    if (!is_string($token) || !hash_equals($_SESSION['csrf'], $token)) {
        http_response_code(403);
        exit('Requête invalide.');
    }

    // Nettoyage des entrées (on force le type string)
    $old['prenom'] = trim((string) ($_POST['prenom'] ?? ''));
    $old['nom']    = trim((string) ($_POST['nom'] ?? ''));
    $old['email']  = mb_strtolower(trim((string) ($_POST['email'] ?? '')));

    validerNom($old['prenom'], 'Le prénom', $errors, 'prenom');
    validerNom($old['nom'], 'Le nom', $errors, 'nom');

    if ($old['email'] === '') {
        $errors['email'] = "L'email est obligatoire.";
    } elseif (mb_strlen($old['email']) > 254 || !filter_var($old['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "L'email n'est pas valide.";
    }

    if (!$errors) {
        // Ici : enregistrement en base avec PDO + requête préparée, par exemple :
        // $stmt = $pdo->prepare('INSERT INTO users (prenom, nom, email) VALUES (?, ?, ?)');
        // $stmt->execute([$old['prenom'], $old['nom'], $old['email']]);

        $_SESSION['saved'] = $old;
        $_SESSION['csrf'] = bin2hex(random_bytes(32)); // nouveau jeton

        // Redirection (évite le renvoi du formulaire au rafraîchissement)
        header('Location: ' . $_SERVER['SCRIPT_NAME']);
        exit;
    }
}

$saved = $_SESSION['saved'] ?? null;
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

    <?php if ($saved): ?>
        <h2>Informations enregistrées</h2>
        <p>Nom : <?= e($saved['nom']) ?></p>
        <p>Prénom : <?= e($saved['prenom']) ?></p>
        <p>Email : <?= e($saved['email']) ?></p>
    <?php endif; ?>

    <form method="post" action="" autocomplete="off">
        <input type="hidden" name="csrf" value="<?= e($_SESSION['csrf']) ?>">

        <label for="prenom">Prénom *</label>
        <input type="text" id="prenom" name="prenom" required minlength="2" maxlength="50"
               pattern="[A-Za-zÀ-ÖØ-öø-ÿ]+([ '’\-][A-Za-zÀ-ÖØ-öø-ÿ]+)*"
               title="Lettres uniquement, sans chiffres"
               placeholder="Entrez votre prénom" value="<?= e($old['prenom']) ?>">
        <?php if (isset($errors['prenom'])): ?><div class="erreur"><?= e($errors['prenom']) ?></div><?php endif; ?>

        <label for="nom">Nom *</label>
        <input type="text" id="nom" name="nom" required minlength="2" maxlength="50"
               pattern="[A-Za-zÀ-ÖØ-öø-ÿ]+([ '’\-][A-Za-zÀ-ÖØ-öø-ÿ]+)*"
               title="Lettres uniquement, sans chiffres"
               placeholder="Entrez votre nom" value="<?= e($old['nom']) ?>">
        <?php if (isset($errors['nom'])): ?><div class="erreur"><?= e($errors['nom']) ?></div><?php endif; ?>

        <label for="email">Email *</label>
        <input type="email" id="email" name="email" required maxlength="254"
               placeholder="Entrez votre email" value="<?= e($old['email']) ?>">
        <?php if (isset($errors['email'])): ?><div class="erreur"><?= e($errors['email']) ?></div><?php endif; ?>

        <br><br>
        <button type="submit">Envoyer</button>
        <button type="submit">Effacer</button>
    </form>
</body>
</html>

