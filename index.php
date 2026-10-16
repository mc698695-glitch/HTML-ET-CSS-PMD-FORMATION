<?php
$erreur = "";
$nom = "";
$prenom = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $nom = trim($_POST["nom"] );
    $prenom = trim($_POST["prenom"] );
    $email = trim($_POST["email"] );

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

     <label for="">Prénom *</label>
         <div> 
             <input required placeholder="Entrez votre prénom "
             minlength="2"
             maxlength="10"
             autocomplete="given-name"
              type="text" name="prenom">  
        </div>
        <div>
            <label for="">Nom *</label>
             <input required placeholder="Entrez votre nom" 
             maxlength="10"
             autocomplete="family name"
              type="text" name="nom">
        </div>
        <div>
            <label for="">Email *</label>
            <input required placeholder="Entrez votre email"
             maxlength="20"
             autocomplete="email"
            type="email" name="email">
        </div>
       
    <button type="submit">Envoyer</button>
</form>

</body>
</html>