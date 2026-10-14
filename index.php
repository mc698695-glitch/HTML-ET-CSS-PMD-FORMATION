<?php
$errors="";
if(!empty($_POST["prenom"]) && !empty($_POST["nom"]) && !empty($_POST["email"])){
     $prenom = $_POST['prenom'];
     $nom = $_POST['nom'];
     $email = $_POST['email'];
    if ($prenom === "" || $nom === "" || $email === "") {
        $errors = "Tous les champs sont obligatoires.";
    } elseif (
        strlen($prenom) > 200 ||
        strlen($nom) > 200 ||
        strlen($email) > 254
    ) {
        echo "La longueur d'un champ est invalide.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "L'adresse email est invalide.";
    } /*else {
        echo "Prénom : " .
            htmlspecialchars($prenom, ENT_QUOTES, "UTF-8") . "<br>";
        echo "Nom : " .
            htmlspecialchars($nom, ENT_QUOTES, "UTF-8") . "<br>";
        echo "Email : " .
            htmlspecialchars($email, ENT_QUOTES, "UTF-8") . "<br>";
    }*/
} 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire</title>
</head>

<body>
    <!--EN-TETE DE LA COMMENTAIRE---->
    <header>
       <h1>MOHAMED</h1>
    </header>
    <!--CONTENU DE LA PAGE-->
    <main>
        <p>Les champs avec une * sont obligatoires.</p>
      <form action="" method="post"> 
        <div id="form_user_errors">
          <?php echo $errors;?>
        </div>
        <div>
            <label for="">Prénom *</label> 
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
      // Affichage des resulats en bas 
      <p><?php echo $errors; ?></p>

    <p>Prénom : <?php echo $prenom; ?></p>
    <p>Nom : <?php echo $nom; ?></p>
    <p>Email : <?php echo $email; ?></p>
    </main>

    <!--PIED DE LA PAGE-->
    <footer>
        <p>&copy; 2026 - Mon formulaire</p>
    </footer>

</body>
</html>
