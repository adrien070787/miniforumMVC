<?php


$title = $post['title']." - Forum";
ob_start();
?>
<div class="row">
  <a href="index.php">Revenir à la liste des sujets</a>
</div>

<div class="row">
    <?= $alert; ?>
</div>

<h1>Sujet : <?= $post['title']?></h1>

<div class="post row">
      <div class="post-info col-md-12">
        Posté par <strong><?= $post['author'] ?></strong>
        &nbsp;&nbsp;
        <a href="?action=displayformsubject&id=<?= $post['id'] ?>" title="Modifier ce sujet" class="edit">
          Modifier
        </a><br>
        <small>Le <?= $post['date_fr'] ?></small>
      </div>
      <div>
        <?= $post['content'] ?>
      </div>
</div>


<?php
    while($answer = $answers->fetch()) {
 ?>
    <div class="post-answer row">
      <div class="post-answer-info col-md-12">
        Posté par <strong><?= $answer['author'] ?></strong>
        &nbsp;&nbsp;
        <a href="?action=displayformanswer&id=<?= $post['id'] ?>&id_answer=<?= $answer['id'] ?>" title="Modifier cette réponse" class="edit">
          Modifier
        </a>
        &nbsp;&nbsp;
        <form style="float: right;" action="?action=displaypost&id=<?= $post['id'] ?>&id_answer=<?= $answer['id'] ?>" method="post">
          <button type="submit" name="supprimer">supprimer</button>
        </form>
        <br>
        <small>Le <?= $answer['date_fr'] ?></small>
      </div>
      <div>
        <?= $answer['content'] ?>
      </div>
    </div>
<?php
    }
?>


<hr>

<fieldset>
<legend>Ajouter un commentaire</legend>

<?php
  include('answerform.php');
?>

</fieldset>

<?php
$content = ob_get_clean();
require('template.php');
?>



          