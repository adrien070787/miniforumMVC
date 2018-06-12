<?php


$title = "Accueil - Forum";
ob_start();
?>

<div class="row">
    <?= $alert; ?>
</div>

<div class="row">
    <p>Appropriez vous cet espace de discussion</p>
</div>

<div class="row">
    <a href="index.php?action=displayformpost">
        <button id="valider" name="valider" class="btn btn-primary">
            <i class="glyphicon glyphicon-edit"></i>
            Nouveau sujet
        </button>
    </a>
</div>

<div class="row">
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Sujet</th>
                <th>Auteur</th>
                <th>Date</th>
            </tr>
        </thead>
        <tbody>

        <?php

        if (!empty($posts)) {
            while ($post = $posts->fetch()) {
                ?>
                <tr>
                    <td><a href="?action=displaypost&id=<?= $post['id'] ?>"><?= $post['title'] ?></a></td>
                    <td><?= $post['author'] ?></td>
                    <td><?= $post['date_fr'] ?></td>
                </tr>
                <?php
            }
        } else {
            echo 'Aucun sujet pour le moment';
        }
            ?>

        </tbody>
    </table>
</div>

<?php
$content = ob_get_clean();
require('template.php');
?>