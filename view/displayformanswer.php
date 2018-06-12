<?php
$title = "Modifier une réponse - Forum";
ob_start();
echo '<h2>Modifier une réponse</h2>';
include('answerform.php');

$content = ob_get_clean();
require('template.php');
?>