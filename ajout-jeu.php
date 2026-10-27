<?php
session_start();

include 'utils/utils.php';

if(!$_SESSION){
    header('Location: index.php');
    exit();
}

$message = '';

if(isset($_POST['submit'])){
    if(!empty($_POST['titre']) && !empty($_POST['mini-joueurs']) && !empty($_POST['maxi-joueurs']) && !empty($_POST['duree']) && !empty($_POST['categorie'])){
        if(filter_var($_POST['mini-joueurs'], FILTER_VALIDATE_INT) && filter_var($_POST['maxi-joueurs'], FILTER_VALIDATE_INT) && filter_var($_POST['duree'], FILTER_VALIDATE_INT) && filter_var($_POST['categorie'], FILTER_VALIDATE_INT)){
            if($_POST['mini-joueurs'] >= 1){
                if($_POST['maxi-joueurs'] >= $_POST['mini-joueurs']){
                    if($_POST['duree'] > 0) {
                        $titre = trim($_POST['titre']);
                        $min = trim($_POST['mini-joueurs']);
                        $max = trim($_POST['maxi-joueurs']);
                        $duree = trim($_POST['duree']);
                        $categorie = trim($_POST['categorie']);
                        $membre = $_SESSION['id'];
                        try {
                            $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','root',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

                            $request=$pdo->prepare("SELECT j.id_jeu, j.titre, j.joueurs_min, j.joueurs_max, j.duree FROM jeu j WHERE j.titre = ?;");

                            $request->bindParam(1,$titre,PDO::PARAM_STR);

                            $request->execute();

                            $data = $request->fetch(PDO::FETCH_ASSOC);

                            if(!$data){

                                $request=$pdo->prepare("INSERT INTO jeu (titre, joueurs_min, joueurs_max, duree, id_categorie, id_membre) VALUES (?, ?, ?, ?, ?, ?);");

                                $request->bindParam(1,$titre,PDO::PARAM_STR);
                                $request->bindParam(2,$min,PDO::PARAM_INT);
                                $request->bindParam(3,$max,PDO::PARAM_INT);
                                $request->bindParam(4,$duree,PDO::PARAM_INT);
                                $request->bindParam(5,$categorie,PDO::PARAM_INT);
                                $request->bindParam(6,$membre,PDO::PARAM_INT);

                                $request->execute();

                                $message = 'Votre jeu a bien été ajouté.';
                            } else {
                                $message = 'Ce jeu est déjà présent dans nos données.';
                            }

                        }catch(EXCEPTION $error){
                            $message = "Une erreur est survenue pendant l'enregistrement du jeu. Veuillez réessayer plus tard.";
                        }
                    } else {
                        $message = 'La durée ne peut être égal à 0.';
                    }
                } else {
                    $message = 'Le nombre maximal de joueur ne peut être inférieur au nombre minimal.';
                }
            } else {
                $message = 'Le nombre minimum de joueurs doit au moins être égale à 1.';
            }
        } else {
            $message = 'Veuillez ne rentrer que des entiers dans les champs qui requièrent un nombre.';
        }
    } else {
        $message = 'Tout les champs ne sont pas remplis.';
    }
}

?>

<?php include 'includes/header.php'; ?>
<main>
    <form action="" method="post">
        <fieldset>
            <legend>Ajouter un jeu</legend>
            <label for="titre">Titre</label>
            <input type="text" name="titre" id="titre">
            <label for="mini-joueurs">Nombre minimum de joueurs</label>
            <input type="number" name="mini-joueurs" id="mini-joueurs" step="1" value="1">
            <label for="maxi-joueurs">Nombre maximum de joueurs</label>
            <input type="number" name="maxi-joueurs" id="maxi-joueurs" step="1" value="1">
            <label for="duree">Durée d'une partie(en minutes)</label>
            <input type="number" name="duree" id="duree" step="1" value="1">
            <label for="categorie">Catégorie</label>
            <select name="categorie" id="categorie">
                <option value="1">Stratégie</option>
                <option value="2">Ambiance</option>
                <option value="3">Coopératif</option>
                <option value="4">Familial</option>
                <option value="5">Enquête</option>
                <option value="6">Abstrait</option>
            </select>
            <input type="submit" name="submit" value="Ajouter un jeu">
        </fieldset>
    </form>
    <p>
        <?php echo $message ?>
    </p>
</main>
<?php include 'includes/footer.php'; ?>