<?php
$prenom = $_POST['prenom'];
$nom = $_POST['nom'];
$email = $_POST['email'];
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
       <p>Lorem ipsum dolor, sit amet consectetur adipisicing elit. Dolorem porro deleniti tenetur libero officiis ipsum assumenda reiciendis laudantium nisi repudiandae tempora nemo ut sunt beatae optio accusantium labore, vel voluptate!</p>
    </header>
    <!--CONTENU DE LA PAGE-->
    <main>
      <form action="traitement.php" method="post">
        <input type="text" name="prenom">  
        <input type="text" name="nom">
        <input type="email" name="email">
        <button type="submit">Envoyer</button>
        
      </form>
    </main>

    <!--PIED DE LA PAGE-->
    <footer>
        <p>&copy; 2026 - Mon formulaire</p>
    </footer>

</body>
</html>
