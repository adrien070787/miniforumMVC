<?php
/**
 * Created by PhpStorm.
 * User: arb
 * Date: 21/05/2018
 * Time: 14:42
 */

require('model/forumModel.php');

function listPosts() {
    if (isset($_POST['valider'])) {
        addPost();
        $alert = '<div class="alert alert-success col-md-12" role="alert">Votre post a été ajouté avec succes</div>';
    } else {
        $alert = '';
    }
    $posts = getposts();
    require('view/homepage.php');
}

function post() {
    if (isset($_POST['valider'])) {
        $alert = addAnswer();
    } else if (isset($_POST['updater'])) {
        $alert = updateanswer();
    } else if (isset($_POST['supprimer'])) {
        $alert = deleteanswer();
    } else if (isset($_POST['updateSubject'])) {
        $alert = updatePost();
    } else {
        $alert = '';
    }
    $post = getpost($_GET['id']);
    $answers = getanswers($_GET['id']);
    require('view/post.php');
}




function displayformpost() {
    require('view/displayformpost.php');
}

function addPost() {
        if (isset($_POST['subject']) AND isset($_POST['message']) AND isset($_POST['author']) AND !empty($_POST['subject']) AND !empty($_POST['message']) AND !empty($_POST['author'])) {
            $affectedLines = post_add($_POST['subject'], $_POST['message'], $_POST['author']);
            if ($affectedLines == '0' OR $affectedLines == 0 OR $affectedLines == false) {
                throw new Exception('Impossible d\'ajouter le sujet');
            }
        } else {
            //header('Location: index.php?action=post&id_post='.$_GET['id_post']);
            throw new Exception('Le sujet n\'a pas été posté. Veuillez renseigner un auteur, un sujet, et un message');
        }

}

function addAnswer() {
    $affectedLines = ann_add_answer($_GET['id'], $_POST['author'], $_POST['message']);
    if ($affectedLines == '0' OR $affectedLines == 0 OR $affectedLines == false) {
       throw new Exception('Impossible d\'ajouter la réponse');
    } else {
        $alert = '<div class="alert alert-success col-md-12" role="alert">Votre post a été ajouté avec succes</div>';
        return $alert;
    }
}


function displayformanswer() {
    $id_answer = $_GET['id_answer'];
    $idPost = $_GET['id'];
    $answer = ann_get_answer_by_id($id_answer);
    require('view/displayformanswer.php');
}

function displayformsubject() {
    $idPost = $_GET['id'];
    $postToUpdate = getpost($idPost);
    require('view/displayformanswer.php');
}


function updateanswer() {
    $id_answer = $_GET['id_answer'];
    $content = $_POST['message'];
    $affectedLines = ann_update_answer($id_answer, $content);
    if ($affectedLines == '0' OR $affectedLines == 0 OR $affectedLines == false) {
       throw new Exception('Impossible de mettre à jour la réponse');
    } else {
        $alert = '<div class="alert alert-success col-md-12" role="alert">Votre réponse a été modifiée avec succes</div>';
        return $alert;
    }
}

function deleteanswer() {
    $id_answer = $_GET['id_answer'];
    $affectedLines = ann_delete_answer($id_answer);
    if ($affectedLines == '0' OR $affectedLines == 0 OR $affectedLines == false) {
       throw new Exception('Impossible de supprimer la réponse');
    } else {
        $alert = '<div class="alert alert-success col-md-12" role="alert">Votre réponse a été supprimée avec succes</div>';
        return $alert;
    }
}


function updatePost() {
    $affectedLines = post_update($_GET['id'], $_POST['message']);
    if ($affectedLines == '0' OR $affectedLines == 0 OR $affectedLines == false) {
       throw new Exception('Impossible de modifier la réponse');
    } else {
        $alert = '<div class="alert alert-success col-md-12" role="alert">Votre sujet a été modifié avec succes</div>';
        return $alert;
    }
}

