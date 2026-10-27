<?php

session_start();

include 'utils/utils.php';

$affichage = ''; 

try { 
    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','root',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                    
    $request=$pdo->prepare("SELECT j.titre, c.nom_categorie, j.joueurs_min, j.joueurs_max, j.duree, m.prenom FROM categorie c JOIN jeu j ON c.id_categorie = j.id_categorie JOIN membre m ON j.id_membre = m.id_membre ORDER BY j.titre;");

    $request->execute();

    $data = $request->fetchAll(PDO::FETCH_ASSOC);

    foreach($data as $jeu){
        $titre = htmlentities($jeu['titre']);
        $categorie = htmlentities($jeu['nom_categorie']);
        $duree = htmlentities($jeu['duree']);
        $auteurF = htmlentities($jeu['prenom']);

        $format = formatJoueurs(htmlentities($jeu['joueurs_min']), htmlentities($jeu['joueurs_max']));
        $categDuree = categorieDuree(htmlentities($jeu['duree']));

        $affichage .=  "<li> Titre: $titre, Catégorie: $categorie, Format: $format, {$duree}min : $categDuree, ajouté par $auteurF";
    }



}catch(EXCEPTION $error){
    die($error->getMessage());
}

?>



<?php include 'includes/header.php'; ?>
<main>
    <h2>Liste des jeux de la ludothèque</h2>
    <ul>
        <?php echo $affichage; ?>
    </ul>
</main>
<?php include 'includes/footer.php'; ?>