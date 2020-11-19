<?php
require_once('../inc/init.inc.php');
//SI l'internaute N'EST PAS (!) administrateur du site, il n'a rien à faire sur cette page, on le redirige vers la page connexion
if(!adminConnect())
{
    header('location:' . URL . 'connexion.php');
}
//SUPRESSION MEMBRE
// EXO : réaliser le traitement SQL + PHP permettant de supprimer un membre de la BDD en fonction de l'id_membre transmit dans l'URL


/*if($_POST)
{
if(isset($_GET['action']) && $_GET['action'] == 'suppression')
{

    $del = $bdd->prepare("DELETE FROM membre WHERE id_membre = :id_membre");
   $del->bindValue(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);
   $del->execute();

       

    
}

if(isset($_GET['action']) && $_GET['action'] == 'modification')
{

    $edit = $bdd->prepare("UPDATE membre SET id_membre = :id_membre AS ID, pseudo = :pseudo, nom = :nom, prenom = :prenom, email = :email, civilite = :civilite, ville = :ville, adresse = :adresse, code_postal = :code_postal AS Code Postal, role = :role WHERE id_membre = :id_membre");
    $edit->bindValue('id_membre', $_GET['id_membre'], PDO::PARAM_INT);

    $_GET['action'] = 'affichage';

    $v = "<p class=' col-md-3 mx-auto bg-success text-center text-white p-3 rounded'>Le membre titre : <strong>$_POST[pseudo]</strong> a bien été modifié !</p>";

}
    $edit->bindValue(':ID', $_POST['ID'], PDO::PARAM_STR);
    $edit->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
    $edit->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
    $edit->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
    $edit->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
    $edit->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
    $edit->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
    $edit->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
    $edit->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
    $edit->bindValue(':role', $_POST['role'], PDO::PARAM_INT);

    $edit->execute();

echo '<pre>'; print_r($_POST); echo'</pre>';
}*/
 if(isset($_GET['action']) && $_GET['action'] == 'modification')
{
    
    if(isset($_GET['id_membre']) && !empty($_GET['id_membre']))
    {
        $r = $bdd->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
        $r->bindValue(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);
        $r->execute();

        if($r->rowCount())
        {
            $m = $r->fetch(PDO::FETCH_ASSOC);
            echo '<pre>'; print_r($m); echo '</pre>';
        }
        else
        {
            header('location:' . URL . 'admin/gestion_membre.php');   
        }

    }
    else
    {
        header('location:' . URL . 'admin/gestion_membre.php');
    }
    // la boucle FOREACH génère une variable par tour de boucle
    // On se sert de la variable $k qui receptionne un indice du tableau ARRAY par tour de boule pour créer une variable
    foreach($m as $k => $v)
    {
        $$k = (isset($m[$k])) ? $m[$k] : '';
    }
    // REQUETE UPDATE MODIFICATION MEMBRE

    if($_POST)
    {
        echo '<pre>'; print_r($_POST); echo '</pre>';
        $up = $bdd->prepare("UPDATE membre SET civilite = :civilite, pseudo = :pseudo, nom = :nom, prenom = :prenom, email = :email, ville = :ville, adresse = :adresse, code_postal = :code_postal, statut = :statut WHERE id_membre = :id_membre");
        $up->bindValue(':civilite', $_POST['civilite'], PDO::PARAM_STR);
        $up->bindValue(':pseudo', $_POST['pseudo'], PDO::PARAM_STR);
        $up->bindValue(':nom', $_POST['nom'], PDO::PARAM_STR);
        $up->bindValue(':prenom', $_POST['prenom'], PDO::PARAM_STR);
        $up->bindValue(':email', $_POST['email'], PDO::PARAM_STR);
        $up->bindValue(':ville', $_POST['ville'], PDO::PARAM_STR);
        $up->bindValue(':adresse', $_POST['adresse'], PDO::PARAM_STR);
        $up->bindValue(':code_postal', $_POST['code_postal'], PDO::PARAM_INT);
        $up->bindValue(':id_membre', $_GET['id_membre'], PDO::PARAM_INT);
        $up->bindValue(':statut', $_POST['statut'], PDO::PARAM_INT);
        $up->execute();

        $vUpdt = "<p class='col-md-3 mx-auto bg-success text-center text-white p-3 rounded my-4'>Le membre  a bien été modifié !</p>";

        $_GET = '';
    }
}



require_once('../inc/header.inc.php');
require_once('../inc/nav.inc.php');

// Exo : Afficher l'ensemble de la table membre sous forme de tableau HTMl (sauf le mot de passe)
// SELECT + TABLE + FETCH
// Prévoir 2 colonnes supplémentaire pour la modification et suppression de chasue membre

 $r = $bdd->query("SELECT id_membre AS ID, pseudo, nom, prenom, email, civilite, ville, adresse, code_postal AS 'CODE POSTAL', statut AS ROLE FROM membre");

?>

<h1 class="display-4 text-center my-4"> Listes des Membres</h1>

<?php 
if(isset($vd)) echo $vd; 
if(isset($vUpdt)) echo $vUpdt;

//TRAITEMENT AFFICHAGE NOMBRE ADMIN
$ad = $bdd->query("SELECT * FROM membre WHERE statut = 1");


if($r->rowCount() == 1)
    $txt = 'admin enregistré.';

else 
    $txt = 'admins enregistrés.';    


?>



<table class="col-md-8 mx-auto table table-bordered text-center"><tr>

<?php 
for($i = 0; $i < $r->columnCount(); $i++):
    
    $c = $r->getColumnMeta($i);    
?>

        <th><?= strtoupper($c['name']) ?></th>
   
    <?php
    endfor;
    ?>
    <th>EDIT</th>
    <th>SUPP</th>

</tr>
<?php while($m = $r->fetch(PDO::FETCH_ASSOC)): ?>

    <tr>
    <?php foreach($m as $k => $v): ?>

        <?php if($k == 'ROLE'): ?>

            <?php if($v == 0): ?>
                <td>MEMBRE</td>
            <?php else: ?> 
                <td class="bg-info text-white">ADMIN</td>
                
            <?php endif; ?>


        <?php else: ?>
        <td><?= $v ?></td>

        <?php endif; ?>

    <?php endforeach; ?>

            <td><a href="?action=modification&id_membre=<?= $m['ID'] ?>" class="btn btn-dark"><i class='far fa-edit'></i></a></td>



            <td><a href="?action=suppression&id_membre=<?= $m['ID'] ?>" class="btn btn-danger"><i class='far fa-trash-alt'></i></a></td>
    </tr>


<?php endwhile; ?> 

</table>  
 
<?php if(isset($_GET['action']) && $_GET['action'] == 'modification'); ?>

<form method="post" class="col-md-6 mx-auto" enctype="multipart/form-data">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="civilite">Civilité</label>
                    <select id="Civilite" name="civilite" class="form-control">
                        <option value="homme"><?php if($civilite == 'homme') echo 'selected'; ?>Monsieur</option>
                        <option value="femme"><?php if($civilite == 'femme') echo 'selected'; ?>Madame</option>
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="pseudo">Pseudo</label>
                    <input type="text" class="form-control" id="pseudo" name="pseudo" placeholder="ex : toto78" value="<?= $pseudo ?>" >
                </div>

                <div class="form-group col-md-6">
                    <label for="nom">Nom</label>
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="ex : toto78" value="<?= $nom ?>">
                </div>

                <div class="form-group col-md-6">
                    <label for="prenom">Prénom</label>
                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="ex : toto78" value="<?= $prenom ?>">
                </div>

                <div class="form-group col-md-6">
                    <label for="email">Email</label>
                    <input type="text" class="form-control" id="email" name="email" placeholder="ex : toto78@gmail.com" value="<?= $email ?>" >
                </div>

                <div class="form-group col-md-6">
                    <label for="ville">Ville</label>
                    <input type="text" class="form-control" id="ville" name="ville" placeholder="ex : toto78" value="<?= $ville ?>">
                </div>

                <div class="form-group col-md-6">
                    <label for="adresse">Adresse</label>
                    <input type="text" class="form-control" id="adresse" name="adresse" placeholder="ex : toto78" value="<?= $adresse ?>">
                </div>

                <div class="form-group col-md-6">
                    <label for="code_postal">Code Postal</label>
                    <input type="text" class="form-control" id="code_postal" name="code_postal" placeholder="ex : toto78" value="<?= $code_postal ?>">
                </div>

                <div class="form-row">
                <div class="form-group col-md-12">
                    <label for="statut">Rôle</label>
                    <select id="statut" name="statut" class="form-control">
                        <option value="membre">Membre</option>
                        <option value="admin">Admin</option>
                    </select>
                </div>

            </div>
            <br>
            <button type="submit" class="btn btn-dark mb-3"><?= strtoupper($_GET['action']) ?> MODIFIER</button> 

</form>




<?php
require_once('../inc/footer.inc.php'); 