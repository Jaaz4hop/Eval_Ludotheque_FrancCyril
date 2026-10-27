<?php
session_start();

include 'utils/utils.php';

$message = '';

if(isset($_POST['submit'])){
    if(!empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['verif'])){
        if(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
            if(mb_strlen($_POST['password'], 'UTF-8') >= 12){
                if($_POST['password'] == $_POST['verif']){

                    $prenom = trim($_POST['prenom']);
                    $nom = trim($_POST['nom']);
                    $email = trim($_POST['email']);
                    $password = password_hash($_POST['password'],PASSWORD_DEFAULT);

                    try {
                        $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','root',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                        
                        $request=$pdo->prepare("SELECT m.prenom, m.nom, m.email FROM membre m WHERE m.email = ?;");

                        $request->bindParam(1,$email,PDO::PARAM_STR);

                        $request->execute();

                        $data = $request->fetch(PDO::FETCH_ASSOC);

                        if(empty($data)){

                            $request=$pdo->prepare("INSERT INTO membre(prenom, nom, email, mot_de_passe) VALUES (?, ?, ?, ?);");

                            $request->bindParam(1,$prenom,PDO::PARAM_STR);
                            $request->bindParam(2,$nom,PDO::PARAM_STR);
                            $request->bindParam(3,$email,PDO::PARAM_STR);
                            $request->bindParam(4,$password,PDO::PARAM_STR);

                            $request->execute();

                            $message = "L'utilisateur {$nom} {$prenom}, mail: {$email} a été enregistré.";

                        } else {
                            $message = 'Cet email est déjà utilisé par un utilisateur.';
                        }
                    }catch(EXCEPTION $error){
                        die($error->getMessage());
                    }
                } else {
                    $message = "Le mot de passe et sa vérification ne correspondent pas.";
                }
            } else {
                $message = "Votre Mot-de-passe doit contenir au moins 12 caractères.";
            }
        } else {
            $message = "Merci de respecter le format du champ email.";
        }
    } else {
        $message = 'Veuillez remplir tout les champs';
    }
}

?>

<?php include 'includes/header.php'; ?>
<main>
    <form action="" method="post">
        <fieldset>
            <legend>Formulaire d'incription</legend>
            <label for="prenom">Prénom</label>
            <input type="text" name="prenom" id="prenom" required>
            <label for="nom">Nom</label>
            <input type="text" name="nom" id="nom" required>
            <label for="email">Email</label>
            <input type="email" name="email" id="email" required>
            <label for="password">Mot-de-passe</label>
            <input type="password" name="password" id="password" minlength="12" required >
            <label for="verif">Vérifier votre Mot-de-passe</label>
            <input type="password" name="verif" id="verif" minlength="12" required >
            <input type="submit" name="submit" value="S'enregistrer">
        </fieldset>
        <p>
            <?php echo $message; ?>
        </p>
    </form>
</main>
<?php include 'includes/footer.php'; ?>