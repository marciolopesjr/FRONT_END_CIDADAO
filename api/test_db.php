<?php
require_once 'config/database.php';

$db = connect_db();

if ($db) {
    echo "Conexão com o MySQL estabelecida com sucesso!";
}
?>