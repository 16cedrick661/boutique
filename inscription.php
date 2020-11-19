<?php 
require_once('inc/init.inc.php');

//SI l'inernaute est connecté, cela veut dire que l'indice 'user' est bien définit dans la session, alors il n'a rien à faire sur la page connexion, on le redirige vers sa page profil
if(connect())
{
    header("location: profil.php");
}

/* 
 Nous sommes dans la balise <main></main> 


Exo : 
1. Réaliser un formulaire d'inscription correspondant à la table 'membre' de la BDD 'boutique' (sauf id_membre) et ajouter le champ 'confirmer mot de passe'
(name="confirm_mdp")

2. Contrôler en PHP que l'on receptionne bien toute les données saisies dans le formulaire
3. Contrôler la validité du pseudo, si le pseudo est existant en BB, alors on affiche un message d'erreur. Faites de même pour le champs 'email'


4. Informer l'internaute si les mots de passe ne correspondent pas.

5. Gérer les failles XSS

6. Si l'internaute a correctement remplit le formulaire, réaliser le traitement PHP + SQL permettant d'insérer le membre en BDD (requete préparée | prepare() + bindValue())
  
*/
//echo '<pre>'; print_r($_POST); echo'</pre>';

if($_POST)
{
    // bordure rouge en cas d'erreur dans le formulaire
    $border = "border border-danger";
    // On selectionne TOUT en BDD A CONDITION que le champ pseudo soit égal au pseudo que l'internaute a saisie dans le champ du formulaire
    $verifPseudo = $bdd->prepare("SELECT * FROM membre WHERE pseudo = :pseudo");
    $verifPseudo->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR); // on transmet le pseudo saisie dans le formulaire dans le marqueur déclaré :pseudo
    $verifPseudo->execute(); // execution de la requête préparée

    // SI la requête de sélection a retourné au moins 1 résultat, cela veut dire que le pseudo est connu en BDD, alors on entre dans le IF et on affiche un message d'erreur à l'internaute
    
    if(empty($_POST['pseudo']))
    {
        $errorPseudo = "<p class='test-danger font-italic'>Veuillez mettre un pseudo, merci.</p>";
        $error = true;
    }
    
    elseif($verifPseudo->rowCount())
    {
        $errorPseudo = "<p class='text-danger font-italic'>Pseudo déjà existant, merci d'en changer.</p>";
        $error = true;
    }

    $verifEmail = $bdd->prepare("SELECT * FROM membre WHERE email = :email");
    $verifEmail->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $verifEmail->execute();

    // SI la condition renvoie TRUE, cela veut dire que rowCount() retourne un INT donc une ligne de la BDD, donc l'Email est connu en BDD
    //SI la condition IF renvoie FALSE, cela veut dire que rowCount() retourne BOOLEAN FALSE, donc l'Email n'est pas connu en BDD
    
    
    if(empty($_POST['email']))
    {
        $errorEmail = "<p class='test-danger font-italic'>Veuillez mettre un email, merci.</p>";
        $error = true;
    }
    
    
    
    
    elseif($verifEmail->rowCount())
    {
        $errorEmail = "<p class='text-danger font-italic'>Compte existant à cette adresse Email.</p>";
        $error = true;
    }
//SI la valeur du champ 'mot de passe' est différente du champ 'confirmer votre mot d passe', alors on rentre dans la condition IF
    if($_POST['mdp'] !=$_POST['confirm_mdp'])
    {
        $errorMdp = "<p class='text-danger font-italic'>Les mots de passe ne correspondent pas.</p>";
        $error = true;
    }

     if(!isset($error))
    {
        foreach($_POST as $key => $value)
        {
            $_POST[$key] = htmlspecialchars($value);
        }
        //cryptage du mot de passe en BDD
        //Les mots de passe ne sont jamais gardés en clair dans la BDD
        // password_hash() : fonction prédéfinie qui crée une clé de hachage pour le mot de passe dans la BDD
        $_POST['mdp'] = password_hash($_POST['mdp'], PASSWORD_BCRYPT);
    }

    $req = "INSERT INTO membre (pseudo, mdp, nom, prenom, email, civilite, ville, code_postal, adresse) VALUES (:pseudo, :mdp, :nom, :prenom, :email, :civilite, :ville, :code_postal, :adresse)";
    $insert = $bdd->prepare($req);
    $insert->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $insert->bindValue(':mdp', $_POST['mdp'], PDO::PARAM_STR);
    $insert->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
    $insert->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
    $insert->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $insert->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
    $insert->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
    $insert->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_STR);
    $insert->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);

    $insert->execute();

    // Après l'insertion du membre en BDD, on le redirige vers la page validation_inscription.php grâce à la fonction prédéfinie header() 
    header("location: validation_inscription.php");

   

}
require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
?>

 
<h1 class="display-4 text-center my-5">Créer votre compte</h1><hr>

<form class="text-center" method="post" action="">
    <div>
        <label for="pseudo">Pseudo</label><br>
        <input type="text" <?php if(isset($errorPseudo)) echo $border; ?> id="pseudo" name="pseudo"  placeholder="votre pseudo" pattern="[a-zA-Z0-9-_.]{1,20}" title="caractères acceptés : a-zA-Z0-9-_." required="required"><br>
        <?php if(isset($errorPseudo)) echo $errorPseudo; ?>
    </div>  
    <div>    
        <label for="mdp">Mot de passe</label><br>
        <input type="password" id="mdp" name="mdp" placeholder="Votre mot de passe" required="required"><br>
    </div>
    <div>
        <label for="confirm_mdp">Confirmer votre mot de passe</label><br>
        <input type="password" id="confirm_mdp" name="confirm_mdp" placeholder="Confirmez"><br>
        <?php if(isset($errorMdp)) echo $errorMdp; ?>

    </div>     
     <div>
        <label for="nom">Nom</label><br>
        <input type="text" id="nom" name="nom" placeholder="votre nom"><br>
    </div> 
    <div>    
        <label for="prenom">Prénom</label><br>
        <input type="text" id="prenom" name="prenom" placeholder="votre prénom"><br>
    </div> 
    <div>
        <label for="email">Email</label><br>
        <input type="text"  id="email" name="email" placeholder="exemple@gmail.com"><br>
        <?php if(isset($errorEmail)) echo $errorEmail; ?>
     </div>     
     <div>
        <label for="civilite">Civilité</label><br>
        <input name="civilite" value="m" checked="" type="radio">Homme
        <input name="civilite" value="f" type="radio">Femme<br>
     </div>
     <div>             
        <label for="ville">Ville</label><br>
        <input type="text" id="ville" name="ville" placeholder="votre ville" pattern="[a-zA-Z0-9-_.]{5,15}" title="caractères acceptés : a-zA-Z0-9-_."><br>
     </div>  
     <div>   
        <label for="cp">Code Postal</label><br>
        <input type="text" id="code_postal" name="code_postal" placeholder="code postal" pattern="[0-9]{5}" title="5 chiffres requis : 0-9"><br>
    </div>
    <div>      
        <label for="adresse">Adresse</label><br>
        <textarea id="adresse" name="adresse" placeholder="votre dresse" pattern="[a-zA-Z0-9-_.]{5,15}" title="caractères acceptés :  a-zA-Z0-9-_."></textarea><br><br>
     </div>
    <input type="submit" name="inscription" value="S'affilier">
</form>



<?php
require_once('inc/footer.inc.php');
