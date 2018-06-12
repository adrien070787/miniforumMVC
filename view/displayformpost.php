<?php
$title = "Nouveau Sujet - Forum";
ob_start();
?>

<form class="form-horizontal" action="index.php?action=homepage" method="post">

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-12 control-label" for="author">Auteur</label>
        <div class="col-md-12">
            <input id="author" name="author" type="text" placeholder="Saisissez votre prenom" class="form-control input-md">
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-12 control-label" for="subject">Titre du sujet</label>
        <div class="col-md-12">
            <input id="subject" name="subject" type="text" placeholder="Saisissez un titre" class="form-control input-md">
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-12 control-label" for="message">Votre message</label>
        <div class="col-md-12">
            <textarea id="message" name="message" type="text" placeholder="Entrez votre message" class="form-control input-md" rows="12"></textarea>
        </div>
    </div>

    <!-- Button -->
    <div class="form-group">
        <div class="col-md-12">
            <a href="/index.php">
                <button id="annuler" name="annuler" class="btn btn-primary btn-danger">
                    <i class="glyphicon glyphicon-chevron-left"></i>Annuler et revenir
                </button>
            </a>
            <button id="valider" name="valider" class="btn btn-primary">
                <i class="glyphicon glyphicon-edit"></i>
                Valider
            </button>
        </div>
    </div>
</form>


<?php
$content = ob_get_clean();
require('template.php');
?>
