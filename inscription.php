<?php
require_once 'inc/init.inc.php';

$pseudo = (!empty($_POST['pseudo'])) ? trim(strip_tags($_POST['pseudo'])): '';
$prenom = (!empty($_POST['prenom'])) ? trim(strip_tags($_POST['prenom'])): '';
$nom = (!empty($_POST['nom'])) ? trim(strip_tags($_POST['nom'])): '';
$email = (!empty($_POST['email'])) ? trim(strip_tags($_POST['email'])): '';
$sexe = (!empty($_POST['sexe'])) ? trim(strip_tags($_POST['sexe'])): '';
$adresse = (!empty($_POST['adresse'])) ? trim(strip_tags($_POST['adresse'])): '';
$ville = (!empty($_POST['ville'])) ? trim(strip_tags($_POST['ville'])): '';
$cp = (!empty($_POST['cp'])) ? trim(strip_tags($_POST['cp'])): '';
$mdp = (!empty($_POST['mdp'])) ? trim(strip_tags($_POST['mdp'])): '';

$pass=1;

if(isset($_POST['inscription'])){
    if(!empty($pseudo) && !empty($prenom) && !empty($nom) && !empty($email) && !empty($sexe) && !empty($adresse) && !empty($ville) && !empty($cp) && !empty($mdp)){
        
        // On verifie si le pseudo n'existe pas
        $check_pseudo = $lokisalle -> prepare("SELECT pseudo FROM membre WHERE pseudo = :pseudo");
        $check_pseudo -> bindValue(':pseudo',$pseudo, PDO::PARAM_STR);
        $check_pseudo -> execute();
        if( ($check_pseudo -> rowCount()) == 1 ){
            echo '<div> Ce pseudo existe deja !!! </div>';
            $pass=0;
        }

        if($pass){
            if( preg_match('/@/', $email) && preg_match('/[0-9]/', $mdp) ){
                // On verifie si l'adresse mail n'existe pas
                $check_mail = $lokisalle -> prepare("SELECT email FROM membre WHERE email = :email");
                $check_mail -> bindValue(':email',$email, PDO::PARAM_STR);
                $check_mail -> execute();
                if( ($check_mail -> rowCount()) > 0 ){
                    echo '<div> Cet email existe deja !!! </div>';
                    $pass=0;
                } 
            } else {
                echo '<div> Votre email n\'est pas correct / Le mot de passe doit comporter au moins un chiffre </div>';
                $pass=0;
              }
        }

        if($pass){
                $new_membre = $lokisalle -> prepare("INSERT INTO membre(pseudo,prenom,nom,email,sexe,adresse,ville,cp,mdp) VALUES (:pseudo,:prenom,:nom,:email,:sexe,:adresse,:ville,:cp,:mdp)");

                $new_membre -> bindValue(':pseudo',$pseudo, PDO::PARAM_STR);
                $new_membre -> bindValue(':prenom',$prenom, PDO::PARAM_STR);
                $new_membre -> bindValue(':nom',$nom, PDO::PARAM_STR);
                $new_membre -> bindValue(':email',$email, PDO::PARAM_STR);
                $new_membre -> bindValue(':sexe',$sexe, PDO::PARAM_STR);
                $new_membre -> bindValue(':adresse',$adresse, PDO::PARAM_STR);
                $new_membre -> bindValue(':ville',$ville, PDO::PARAM_STR);
                $new_membre -> bindValue(':cp',$cp, PDO::PARAM_STR);
                $mdp = password_hash($mdp, PASSWORD_DEFAULT);     
                $new_membre -> bindValue(':mdp',$mdp, PDO::PARAM_STR);

                $new_membre -> execute();
             }
        
    } else echo '<div> Veuillez renseigner tous les champs !! </div>';
}



include_once 'inc/header.inc.php';
?>
<div class="row centered-form formulaireAjout">
  <div class="col-xs-12 col-sm-8 col-md-4 col-sm-offset-2 col-md-offset-4">
    <div class="panel panel-default">
      <div class="panel-heading">
        <h2 class="panel-title">Inscription</h2>
      </div>
      <div class="panel-body">
    <form method="post">
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="form-group">
              <input id="nom" required value="<?= $nom ?>" placeholder="nom" name="Nom" type="text" />
              </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="form-group">
               <input id="prenom" required value="<?= $prenom ?>" placeholder="Prenom" name="prenom" type="text" />
              </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="form-group">
                <input type="text" value="<?= $ville ?>" required placeholder="Ville" name="ville" >
              </div>
              </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="form-group">
                <textarea required placeholder="Adresse" name="adresse"><?= $adresse ?></textarea>
              </div>
            </div>
              <div class="col-xs-6 col-sm-6 col-md-6">
                <div class="form-group">  
                <input type="text" value="<?= $cp ?>" required placeholder="Code postal" name="cp" >
              </div>
              </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="form-group">
              <input id="pseudo"  value="<?= $pseudo ?>" placeholder="Pseudo" name="pseudo" type="text" />
              </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="form-group">
                <input type="text" required value="<?= $email ?>" placeholder="Email" name="email" >
              </div>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="form-group">
                M <input type="radio" <?=($sexe == 'm') ? 'checked' : '' ?> required value="m" name="sexe" >
                F <input type="radio" <?=($sexe == 'f') ? 'checked' : '' ?> required value="f" name="sexe" >
              </div>
            </div>
            <div class="col-xs-6 col-sm-6 col-md-6">
              <div class="form-group">
                    <input type="text" value="" required placeholder="Mots de passe " name="mdp" >
              </div>
            </div>
        </div>
            <button style="margin-top: 15px;" type="reset" name="reset" class="btn btn-info btn-block">EFFACER</button>
            <button style="margin-top: 15px;" type="submit" name="inscription" class="btn btn-info btn-block">INSCRIPTION</button>      
    </form>
</div>
</div>
</div>
</div>


<?php
include_once 'inc/footer.inc.php';