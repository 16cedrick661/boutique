<?php
require_once('../inc/init.inc.php');

if(!adminConnect())
{
    header('location:' . URL . 'connexion.php');
}
if(isset($_GET['action']) && $_GET['action'] == 'details')
{
     if(isset($_GET ['id_commande']) && !empty($_GET['id_commande']))
    {
        $dCmd = $bdd->prepare("SELECT dc.produit_id AS ID, p.photo, p.reference, p.titre, p.categorie, dc.quantite, dc.prix FROM details_commande dc INNER JOIN produit p ON dc.produit_id = p.id_produit AND dc.commande_id = :id_commande");
        $dCmd->bindValue(':id_commande', $_GET['id_commande'], PDO::PARAM_INT);
        $dCmd->execute();

        if(!$dCmd->rowCount())
        {
            header('location: ' . URL . 'admin/gestion_commande.php');
        }
        
        
    }
    else
    {
        header('location' . URL . 'admi/gestion_commande.php');
    }
}



require_once('../inc/header.inc.php');
require_once('../inc/nav.inc.php');




// Exo : Afficher la liste des commandes sous forme de tableau HTML contenant les colonnes suivantes : 
/*
    id_commande
    nom
    prenom
    email
    montant
    date_enregistrement
    etat
    edit, détail, supp

    JOINTURE SQL enre la table commande et la table membre
    BOUCLE + FETCH



*/  



$r = $bdd->query("SELECT id_commande AS CMD, id_membre AS 'N° CLIENT', email, prenom, nom, adresse, DATE_FORMAT(date_enregistrement, '%d/%m/%Y à %H:%i:%s') AS DATE, montant,  etat FROM membre INNER JOIN commande ON membre_id = id_membre");



$com = $bdd->query("SELECT id_produit AS produit, categorie, reference, photo FROM details_commande INNER JOIN produit ON produit_id = id_produit");



?>

<h1 class=""display-4 text-center my-4>Liste des commandes</h1>

<h5><span class="badge badge-success"><?= $r->rowCount() ?></span> commande(s).</h5>

<table class=" table table-bordered text-center"><tr>
<?php for($i = 0; $i < $r->columnCount(); $i++):

    $c = $r->getColumnMeta($i);

?>

    <th><?= strtoupper($c['name']) ?></th>
<?php endfor; ?>

    <th>MODIF</th>
    <th>VOIR</th>
    <th>SUPP</th>

</tr>
<?php while($cmd = $r->fetch(PDO::FETCH_ASSOC)):
   // echo '<pre>'; print_r($cmd); echo '</pre>';



?>
    <tr>
    <?php foreach($cmd as $k => $v): ?>

        <?php if($k == 'montant'): ?>

        <td><?= $v ?>€</td>

        <?php else: ?>

        <td><?= $v ?></td>

        <?php endif; ?>


      

    <?php endforeach; ?>

        <td><a href="?action=modification&id_commande=<?= $cmd['CMD'] ?>" class="btn btn-dark"><i class='far fa-edit'></i></a></td>
        <td><a href="?action=details&id_commande=<?= $cmd['CMD'] ?>" class="btn btn-primary"><i class='fas fa-search'></i></a></td>
        <td><a href="?action=suppression&id_commande=<?= $cmd['CMD'] ?>" class="btn btn-danger"><i class='far fa-trash-alt'></i></a></td>



    </tr>
<?php endwhile; ?>




<?php if(isset($_GET['action']) && $_GET['action'] == 'details'): ?>

    <h4 class=""display-4 text-center my-4>Détails de la commandes</h4>
    <table class=" table table-bordered text-center"><tr>
<?php for($i = 0; $i < $dCmd->columnCount(); $i++):

    $c = $dCmd->getColumnMeta($i);

?>

    <th><?= strtoupper($c['name']) ?></th>
<?php endfor; ?>

</tr>
<?php while( $p = $dCmd->fetch(PDO::FETCH_ASSOC)):
    //echo '<pre>'; print_r($p); echo '</pre>';
    ?>


<tr>
<?php foreach($p as $k => $v): ?>

    <?php if($k == 'photo'): ?>

        <td><img src="<?= $v ?>" alt="<?= $p['titre'] ?>" style="width: 50px;"></td>

    <?php else: ?>
        <td><?= $v ?></td>
    <?php endif; ?>



<?php endforeach; ?>
</tr>


<?php endwhile; ?>   
<?php endif; ?>

</table>





<?php
require_once('../inc/footer.inc.php');