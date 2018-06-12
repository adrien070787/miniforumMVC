<?php
/**
 * Created by PhpStorm.
 * User: arb
 * Date: 21/05/2018
 * Time: 14:59
 */


function dbConnect() {
    $bdd = new PDO('mysql:host=localhost;dbname=miniforum;charset=utf8', 'miniforum', 'miniforum');
    return $bdd;
}


/* Tous les posts ------------------------------------------ */

function getposts() {
    $bdd = dbConnect();
    $requete = $bdd->query('SELECT id, title, content, 
                            DATE_FORMAT(time_submit, "%d/%c/%Y à %Hh%imin%Ss") as date_fr, 
                            author FROM post ORDER BY time_submit DESC');
    return $requete;
}

/**
 * Obtenir tous les posts d'une page
 * @param page_num: numéro de la page
 * @return record set
 */
function getposts_by_page($page_num) {
    $post_per_page = 10;
    $first = ($page_num - 1) * $post_per_page;
    $bdd = dbConnect();
    $requete = $bdd->query('SELECT p.id, p.title, p.content, DATE_FORMAT(p.time_submit, "%d/%c/%Y à %Hh%imin%Ss") as date_fr, p.author
	        FROM post p
	        ORDER BY p.time_submit DESC
	        LIMIT '.$first.', '.$post_per_page);
    return $requete;
}


function getpost($id) {
    $bdd = dbConnect();
    $requete = $bdd->prepare('SELECT id, title, content, 
                            DATE_FORMAT(time_submit, "%d/%c/%Y à %Hh%imin%Ss") as date_fr, 
                            author FROM post WHERE id = ?');
    $requete->execute(array($id));
    return $requete->fetch();
}

function getanswers($id) {
    $bdd = dbConnect();
    $requete = $bdd->prepare('SELECT id, content, 
                            DATE_FORMAT(time_submit, "%d/%c/%Y à %Hh%imin%Ss") as date_fr, 
                            author,idPost FROM answer WHERE idPost = ?');
    $requete->execute(array($id));
    return $requete;
}


/**
 * Obtenir le nombre de sujets
 */
function post_count()
{
    $bdd = dbConnect();
    $requete = $bdd->query('SELECT COUNT(p.id) AS total
	        FROM post p');
    $result = $requete->fetch();
    return $result[0];
}


function post_add($title, $content, $author)
{
    $bdd = dbConnect();

    $requete = $bdd->prepare('INSERT INTO post (title, content, time_submit, author)
	        VALUES (?, ?, NOW(), ?)');
    $requete->bindParam('title', $title, PDO::PARAM_STR);
    $requete->bindParam('content', $content, PDO::PARAM_STR);
    $requete->bindParam('author', $author, PDO::PARAM_STR);
    $affectedLines = $requete->execute(array($title, $content, $author));

    return $affectedLines;

}


/**
 * Ajouter une réponse à une annonce
 */
function ann_add_answer($id_post, $author, $message)
{
    $bdd = dbConnect();

    $requete = $bdd->prepare('INSERT INTO answer (content, author, idPost, time_submit)
            VALUES (? ,?, ?, NOW())');
    
    $requete->bindParam('message', $message, PDO::PARAM_INT);
    $requete->bindParam('author', $author, PDO::PARAM_STR);
    $requete->bindParam('idPost', $idPost, PDO::PARAM_INT);
    
    
    $affectedLines = $requete->execute(array($message, $author, $id_post));

    return $affectedLines;
}

function ann_update_answer($id, $content)
{
    $bdd = dbConnect();

    $requete = $bdd->prepare('UPDATE answer SET content = ? WHERE id = ?');
    $requete->bindParam('content', $content, PDO::PARAM_STR);
    $requete->bindParam('id', $id, PDO::PARAM_INT);
    $affectedLines = $requete->execute(array($content, $id));

    return $affectedLines;
}


function ann_delete_answer($id)
{
    $bdd = dbConnect();
    $requete = $bdd->prepare('DELETE FROM answer WHERE id = ?');
    $requete->bindParam('id', $id, PDO::PARAM_INT);
    $affectedLines = $requete->execute(array($id));

    return $affectedLines;
}

/**
 * Obtenir une réponse
 * @param id: id de la réponse
 * @return array ou NULL
 */
function ann_get_answer_by_id($id)
{
    $bdd = dbConnect();
    $requete = $bdd->prepare('SELECT a.id, a.content, a.time_submit, a.author, a.idPost
            FROM answer a
            WHERE a.id = ?');
    $requete->bindParam('id', $id, PDO::PARAM_INT);
    $requete->execute(array($id));
    return $requete->fetch();
}



function post_update($id, $content)
{
    $bdd = dbConnect();
    $requete = $bdd->prepare('UPDATE post
	        SET content = ?
	        WHERE id = ?');
    $requete->bindParam('content', $id, PDO::PARAM_STR);
    $requete->bindParam('id', $id, PDO::PARAM_INT);
    $affectedLines = $requete->execute(array($content, $id));

    return $affectedLines;
}


/**
 * Supprimer un post et toutes ses réponses
 * @param id: id du post
 */
function post_delete($id)
{
    $id = intval($id);
    $sql = 'DELETE FROM answer
	        WHERE idPost = '.$id.';';
    sql_query($sql);
    $sql = 'DELETE FROM post
	        WHERE id = '.$id.';';
    sql_query($sql);
}


function post_grant_edit($id)
{
    $id = intval($id);
    // admin, modo, ou auteur
    if ($_SESSION['status'] == 'A'
        || $_SESSION['status'] == 'O'
        || $_SESSION['id'] == $id)
    {
        return true;
    }
    return false;
}

function post_add_view_count($id)
{
    $id = intval($id);
    $sql = 'UPDATE post
	        SET view_count = (view_count + 1)
	        WHERE id = '.$id.';';
    sql_query($sql);
}

/* Petites annonces uniquement ----------------------------- */

/**
 * Obtenir liste des annonces
 * @param viewer: id du membre qui demande la liste les sujets
 */
function ann_get_list($page_num = NULL, $viewer = 0)
{

    /*
    answer_last_submit = timestamp dernière réponse, ou timestamp post si 0 réponse
    answer_count = nombre de réponses à ce post
    answer_author = auteur dernière réponse
    viewer_count = nombre de réponses de $viewer

    trié par dernière réponse, ou heure du post si 0 réponse
    */

    $post_per_page = 10;

    $sql = 'SELECT p.id,
                   p.title,
                   p.content,
                   p.time_submit,
                   p.view_count,
                   p.author,
                   IFNULL(a.time_submit, p.time_submit) AS answer_last_submit,
                   (
                    SELECT COUNT(a2.id)
                    FROM answer a2
                    WHERE a2.idPost = p.id
                   ) AS answer_count,
                   (
                    SELECT a3.author
                    FROM answer a3
                   ) AS answer_author,
                   (
                    SELECT COUNT(a4.author)
                    FROM answer a4
                    WHERE a4.idPost = p.id AND a4.author = '.$viewer.'
                   ) AS viewer_count
            FROM post p
            LEFT JOIN answer a ON a.idPost = p.id
            WHERE (a.time_submit = (SELECT MAX(a3.time_submit)
                                        FROM answer a3
                                        WHERE a3.idPost = p.id)
                       OR a.time_submit IS NULL)
            GROUP BY p.id,
                     p.title,
                     p.content,
                     p.time_submit,
                     p.author,
                     answer_last_submit
            ORDER BY answer_last_submit DESC';
    if ($page_num != NULL) {
        $first = ($page_num - 1) * $post_per_page;
        $sql .= ' LIMIT '.$first.', '.$post_per_page.';';
    }

    $bdd = dbConnect();
    $requete = $bdd->query($sql);
    return $requete;
}














function ann_count_answer($id_post)
{
    $id_post = intval($id_post);
    $sql = 'SELECT COUNT(id)
	        FROM answer
	        WHERE idPost = '.$id_post.';';
    $data = sql_query($sql);
    $row = mysql_fetch_row($data);
    return $row[0];
}


/**
 * Obtenir les réponses d'une annonce
 * @param id: id de l'annonce
 */
function ann_get_answers($id, $page_num = 1)
{
    $id = intval($id);
    $first = ($page_num - 1) * POST_PER_PAGE;
    $sql = 'SELECT a.id, a.content, a.time_submit,
                   a.idAuthor AS author_id,
	               m.name AS author_name,
	               m.firstname AS author_firstname
            FROM answer a
            LEFT JOIN member m ON m.id = a.idAuthor
            WHERE a.idPost = '.$id.'
            ORDER BY a.time_submit
            LIMIT '.$first.', '.POST_PER_PAGE.';';
    $data = sql_query($sql);
    return $data;
}







