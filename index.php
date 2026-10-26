<?php
// Connexion à la base de données
$host = "localhost";
$base = "Gestion_Contacts";
$utilisateur = "root";
$mot_de_passe_db = "";

try {
    $connexion = new PDO(
        "mysql:host=$host;dbname=$base;charset=utf8mb4",
        $utilisateur,
        $mot_de_passe_db
    );

    // Activer les erreurs PDO
    $connexion->setAttribute(
        PDO::ATTR_ERRMODE,
        PDO::ERRMODE_EXCEPTION
    );
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données.");
}

// Initialiser les variables
$nom = "";
$prenom = "";
$email = "";
$message = "";

// Vérifier l'envoi du formulaire
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Récupérer et nettoyer les valeurs
    $nom = trim($_POST["nom"] ?? "");
    $prenom = trim($_POST["prenom"] ?? "");
    $email = trim($_POST["email"] ?? "");
    $mot_de_passe = $_POST["mot_de_passe"] ?? "";

    // Vérifier les champs obligatoires
    if (
        $nom === "" ||
        $prenom === "" ||
        $email === "" ||
        $mot_de_passe === ""
    ) {
        $message = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Veuillez saisir une adresse email valide.";
    } elseif (strlen($mot_de_passe) < 8) {
        $message = "Le mot de passe doit contenir au moins 8 caractères.";
    } else {
        // Hacher le mot de passe avant de l'enregistrer
        $mot_de_passe_hache = password_hash(
            $mot_de_passe,
            PASSWORD_DEFAULT
        );

        try {
            // Préparer la requête d'insertion
            $requete = $connexion->prepare(
                "INSERT INTO utilisateurs
                (nom, prenom, email, mot_de_passe)
                VALUES (:nom, :prenom, :email, :mot_de_passe)"
            );

            // Associer les valeurs aux paramètres
            $requete->execute([
                ":nom" => $nom,
                ":prenom" => $prenom,
                ":email" => $email,
                ":mot_de_passe" => $mot_de_passe_hache
            ]);

            $message = "Utilisateur enregistré avec succès.";

            // Vider les champs après l'enregistrement
            $nom = "";
            $prenom = "";
            $email = "";

        } catch (PDOException $e) {
            // Gérer le cas où l'email existe déjà
            if ($e->getCode() === "23000") {
                $message = "Cette adresse email est déjà utilisée.";
            } else {
                $message = "Une erreur est survenue lors de l'enregistrement.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
</head>
<body>

    <h1>Créer un compte</h1>

    <p>
        <?= htmlspecialchars($message, ENT_QUOTES, "UTF-8") ?>
    </p>

    <form action="" method="post">
        <label for="nom">Nom :</label>
        <input
            type="text"
            id="nom"
            name="nom"
            value="<?= htmlspecialchars($nom, ENT_QUOTES, "UTF-8") ?>"
            required
        >
        <br><br>

        <label for="prenom">Prénom :</label>
        <input
            type="text"
            id="prenom"
            name="prenom"
            value="<?= htmlspecialchars($prenom, ENT_QUOTES, "UTF-8") ?>"
            required
        >
        <br><br>

        <label for="email">Email :</label>
        <input
            type="email"
            id="email"
            name="email"
            value="<?= htmlspecialchars($email, ENT_QUOTES, "UTF-8") ?>"
            required
        >
        <br><br>

        <label for="mot_de_passe">Mot de passe :</label>
        <input
            type="password"
            id="mot_de_passe"
            name="mot_de_passe"
            minlength="8"
            required
        >
        <br><br>

        <button type="submit">S'inscrire</button>
    </form>

</body>
</html>