<?php
/**
 * Created by PhpStorm.
 * User: arb
 * Date: 21/05/2018
 * Time: 14:28
 */


require('controller/forumController.php');

try {
    if (isset($_GET['action'])) {
        if($_GET['action'] == 'homepage') { //page d'accueil
            listPosts();
        } else if ($_GET['action'] == 'displayformpost') { //affichage du formulaire d'ajout de sujet
            displayformpost();
        } else if ($_GET['action'] == 'addpost') { //action d'ajout de sujet
            addPost();
        } else if ($_GET['action'] == 'displaypost') { //afficher le détail un sujet
            post();
        } else if ($_GET['action'] == 'displayformanswer') { //affiche le formulaire d'ajout ou modification de réponse
            displayformanswer();
        } else if ($_GET['action'] == 'displayformsubject') { //affiche le formulaire de modification de sujet
            displayformsubject();
        } else if ($_GET['action'] == 'updateanswer') {
            updateanswer();
        } else if ($_GET['action'] == 'deleteanswer') {
            deleteanswer();
        } else {
            listPosts();
        }
    } else {
        listPosts();
    }
} catch (Exception $e) {
    $title = 'Erreur';
    $content = '<div class="error">'.$e->getMessage().'</div>';
    require('view/template.php');
}





