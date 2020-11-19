<?php
require_once('inc/init.inc.php');

if(!connect())
{
    header("location: connexion.php");
}


require_once('inc/header.inc.php');
require_once('inc/nav.inc.php');

//echo '<pre>'; print_r($_SESSION); echo'</pre>';

?>
<!--
    Exo : Faites en sorte d'afficher 'Bonjour pseudo' en passant par le fichier session de l'utilisateur
 -->

<h1 class="display-4 text-center my-4">Bonjour <span class="text-info"><?= $_SESSION['user']['pseudo'] ?></span></h1>
<!-- Exo : afficher les information personnelles de l'internaute contenu en session sur la page profil avec la mise e forme -->
<!--<div class="col-md-3 mx-auto card mb-3 shadow-lg">
  <div class="card-body">
    <h5 class="card-title">Présentation</h5>

    <?php foreach($_SESSION['user'] as $key => $value): ?>

        <?php if($key != 'id_membre' && $key != 'statut'): ?>
    
    <p class="card-text"><strong><?= $key ?></strong> : <? $value ?></p>
        <?php endif; ?>
        <?php endforeach; ?>    
    
        <a href="#" class="card-link">Modifier</a>
    
  </div>
</div>--> 
<div class="col-md-3 mx-auto card mb-3 shadow-lg">
    <div class="card-body">

        <h5 class="card-title">Vos informations personnelle</h5><hr>

        <?php foreach($_SESSION['user'] as $key => $value): ?>

            <?php if($key != 'id_membre' && $key != 'statut'): ?>

                <p class="card-text"><strong><?= $key ?></strong> : <?= $value ?></p>
            
            <?php endif; ?>

        <?php endforeach; ?>

        <a href="#" class="card-link">Modifier</a>

    </div>
</div>

<?php
require_once('inc/footer.inc.php');