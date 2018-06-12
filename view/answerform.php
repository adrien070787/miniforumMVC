<?php
switch ($_GET['action']) {
    case 'displaypost':
        $action = 'displaypost&id='.$post['id'];
        $valueauthor = "";
        $valuemessage = "";
        $buttonname = 'valider';
        break;
    case 'displayformanswer':
        $action = 'displaypost&id='.$idPost.'&id_answer='.$answer['id'];
        $buttonname = 'updater';
        $valueauthor = $answer['author'];
        $valuemessage = $answer['content'];
        break;
    case 'displayformsubject':
        $action = 'displaypost&id='.$idPost;
        $buttonname = 'updateSubject';
        $valueauthor = $postToUpdate['author'];
        $valuemessage = $postToUpdate['content'];
        break;
}

?>


<form class="form-horizontal" action="index.php?action=<?= $action ?>" method="post">

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-12 control-label" for="author">Votre nom</label>
        <div class="col-md-12">
            <input id="author" name="author" type="text" value="<?= $valueauthor ?>" placeholder="Saisissez votre prenom" class="form-control input-md">
        </div>
    </div>

    <!-- Text input-->
    <div class="form-group">
        <label class="col-md-12 control-label" for="message">Votre message</label>
        <div class="col-md-12">
            <textarea id="message" name="message" type="text" placeholder="Entrez votre message" class="form-control input-md" rows="12"><?= $valuemessage ?></textarea>
        </div>
    </div>

    <!-- Button -->
    <div class="form-group">
        <div class="col-md-12">
            <button type="submit" id="<?= $buttonname ?>" name="<?= $buttonname ?>" class="btn btn-primary">
                <i class="glyphicon glyphicon-edit"></i>
                Valider
            </button>
        </div>
    </div>
</form>