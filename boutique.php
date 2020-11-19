<?php
require_once('inc/init.inc.php');
// SI l'indice 'categorie' est bien définit dans l'URL et que sa valeur n'est pas vide, cela veut dire que l'internaute a clicqué sur le lien de catégorie et par conséquent transmit les paramètre ex : 'categorie=tee-shirt'
 if(isset($_GET['categorie']) && !empty($_GET['categorie']))
{
    // On selectionne tout en BDD par rapport à la catégorie transmise dans l'URL, afin d'afficher tout les produits liés à la catégorie

    $r = $bdd->prepare("SELECT * FROM produit WHERE categorie = :categorie");
    $r->bindValue(':categorie', $_GET['categorie'], PDO::PARAM_STR);
    $r->execute();


    //SI la requete de sélection ne retourne pas de résultat, que rowCount() retourne False, cela veut dire que la catégorie dans l'URL n'est pas connu en BDD, on redirige l'internaute vers la boutique
    if(!$r->rowCount())
    {
        header('location: boutique.php');
    }
}
else //SINON l'indice 'id_produit' n'est pas définit dans l'URL ou sa valeur est vide, alors on entre dans la condition ELSE et on selectionne l'ensemble de la table produit
{
    $r = $bdd->query("SELECT * FROM produit");
}


require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');
?>

<!-- Page Content -->
    <div class="container">

    <div class="row">

    <div class="col-lg-3">


      <?php 
      // On selectionne les catégories DISTINCT (élimine les doublons) dans la BDD
      $d = $bdd->query("SELECT DISTINCT categorie FROM produit" );
      
        ?>

   

        <h1 class="my-4 text-center">Que Du Lourd !! Viens Voir !!</h1>
        <div class="list-group">
        <li class="list-group-item bg-dark text-white">CATEGORIES</li>
        <?php while($c = $d->fetch(PDO::FETCH_ASSOC)):
                //echo '<pre>'; print_r($c); echo '</pre>'; 
                // fetch() retourne un ARRAY par tour de boucle WHILE contenant les données d'une catégoie
?>
        <a href="?categorie=<?= $c['categorie'] ?>" class="list-group-item"><?= $c['categorie'] ?></a> <!-- la boucle crée un lien par catégorie pour chaque tour de boucle -->

       
        <?php endwhile; ?>
        </div>
        
    
    </div>
    <!-- /.col-lg-3 -->

    <div class="col-lg-9">

        <div id="carouselExampleIndicators" class="carousel slide my-4" data-ride="carousel">
        <ol class="carousel-indicators">
            <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
            <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
        </ol>
        <div class="carousel-inner" role="listbox">
            <div class="carousel-item active">
            <img class="d-block img-fluid" src="<?= URL ?>photo/b1.jpg" alt="First slide">
            </div>
            <div class="carousel-item">
            <img class="d-block img-fluid" src="<?= URL ?>photo/b2.jpg" alt="Second slide">
            </div>
            <div class="carousel-item">
            <img class="d-block img-fluid" src="<?= URL ?>photo/b3.jpg" alt="Third slide">
            </div>
        </div>
        <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="sr-only">Next</span>
        </a>
        </div>

        <div class="row">

        <?php while($p = $r->fetch(PDO::FETCH_ASSOC)):
           // echo '<pre>'; print_r($p); echo '</pre>';  ?>

        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
            <a href="fiche_produit.php?id_produit=<?= $p['id_produit'] ?>"><img class="card-img-top" src="<?= $p['photo'] ?>" alt="<?= $p['titre'] ?>"></a>
            <div class="card-body">
                <h4 class="card-title">
                <a href="fiche_produit.php?id_produit=<?= $p['id_produit'] ?>"><?= $p['titre'] ?></a>
                </h4>
                <h5><?= $p['prix'] ?>€</h5>
                <p class="card-text"></p>
                <?php
                if(iconv_strlen($p['description']) > 80)
                    echo substr($p['description'], 0, 80) . '...';
                
                else 
                echo $p['description'];    
                ?>
            </div>
            <div class="card-footer">
                <a href="fiche_produit.php?id_produit=<?= $p['id_produit'] ?>" class="btn btn-info">Plus d'infos &raquo;</a>
            </div>
            </div>
        </div>

        <?php endwhile; ?>

        </div>
        <!-- /.row -->

    </div>
    <!-- /.col-lg-9 -->

    </div>
    <!-- /.row -->

    </div>
<!-- /.container -->

<?php
require_once('inc/footer.inc.php');