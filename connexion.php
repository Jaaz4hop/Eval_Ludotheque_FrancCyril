<?php
session_start();

include 'utils/utils.php';

$message = '';

if(isset($_POST['submit'])) {
    if(!empty($_POST['email']) && !empty($_POST['password'])) {
        if(filter_var($_POST['email'],FILTER_VALIDATE_EMAIL)) {
            if(strlen($_POST['password']) >= 12) {
                 $email = trim($_POST['email']);
                 $password = $_POST['password'];

                 try {
                    $pdo = new PDO('mysql:host=localhost;dbname=ludotheque','root','root',[PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
                    
                    $request=$pdo->prepare("SELECT m.id_membre, m.prenom, m.nom, m.email, m.mot_de_passe FROM membre m WHERE m.email = ?;");

                    $request->bindParam(1,$email,PDO::PARAM_STR);

                    $request->execute();

                    $data = $request->fetch(PDO::FETCH_ASSOC);

                    if($data && password_verify($password, $data['mot_de_passe'])){
                        $_SESSION['id'] = $data['id_membre'];
                        $_SESSION['prenom'] = $data['prenom'];
                        $_SESSION['nom'] = $data['nom'];
                        $_SESSION['email'] = $data['email'];
                        header('Location: index.php');
                        exit();
                    } else {
                        $message = "Email et/ou mot de passe incorrect ";
                    }
                }catch(EXCEPTION $error){
                    die($error->getMessage());
                }
            } else {
                $message = 'Votre mot de passe doit contenir au moins 12 caracatères.';
            }
        } else {
            $message = 'Veuillez respecter le format du champ email.';
        }
    } else {
        $message = 'Veuillez renseigner tout les champs.';
    }
}

?>

<?php include 'includes/header.php'; ?>
<main>
    <form action="" method="post">
        <fieldset>
            <legend>Formulaire de connexion</legend>
            <label for="email">Email</label>
            <input type="email" name="email" id="email">
            <label for="password">Mot-de-passe</label>
            <input type="password" name="password" id="password">
            <input type="submit" name="submit" value="Se connecter">
        </fieldset>
    </form>
    <p>
        <?php echo $message; ?>
    </p>
</main>
<?php include 'includes/footer.php'; ?>