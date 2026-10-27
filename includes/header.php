<?php
if($_SESSION) {
    $nav = "Bonjour {$_SESSION['prenom']}<nav>
            <a href='index.php'>Accueil</a>
            <a href='ajout-jeu.php'>Ajouter un jeu</a>
            <a href='deconnexion.php'>Se déconnecter</a>
            </nav>";
} else {
    $nav = "<nav>
            <a href='index.php'>Accueil</a>
            <a href='inscription.php'>Inscription</a>
            <a href='connexion.php'>Connexion</a>
            </nav>";
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Le dé Fêlé</title>
    <link rel="stylesheet" href="src/css/style.css">
</head>
<body>
    <header>
        <h1>Ludothèque le Dé Fêlé</h1>
            <?php echo $nav; ?>
    </header>
