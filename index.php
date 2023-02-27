<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require 'vendor/autoload.php';
require_once 'Connection.php';

header("Content-type: application/json");
header("Access-Control-Allow-Origin: *");

$dados = file_get_contents('php://input');
$dados = json_decode($dados, true);

$conn =new Connection();
$cxPdo = $conn->connect();


$cmdSql = "SELECT * FROM users WHERE deletado = 0";

if(isset($dados['buscar_id'])){
    $busca = $dados['buscar_id'];
    $cmdSql= "SELECT * FROM users WHERE users.id = $busca AND users.deletado = 0";
}
if(isset($dados['deletar_id'])){
    $busca = $dados['deletar_id'];
    $cmdSql= "UPDATE users SET deletado = 1 WHERE users.id = $busca";
}
if(isset($dados['atualizar_id'])){
    $id= $dados['atualizar_id'];
    
    if($dados['name']){
        $nome=$dados['name'];
        if($dados['password']){
            $senha=$dados['password'];
            $cmdSql= "UPDATE users SET name='$nome', password='$senha' WHERE users.id = $id";
        }else{
            $cmdSql= "UPDATE users SET name='$nome' WHERE users.id = $id";
        }
    }else if ($dados['password']){
        $cmdSql= "UPDATE users SET password='$senha' WHERE users.id = $id";
    }else{
         return false;
    }
}
if(isset($dados['criar'])){
    $nome = $dados['name'];
    $senha = $dados['password'];
    $email = $dados['email'];
    if (!$nome || !$senha || !$email) {
        echo "Erro na Entrada";
        // var_dump($nome, $senha, $email);
        return false;
      } else {
        $cmdSql= "INSERT INTO users (name,password,email) VALUES ('$nome','$senha','$email')";
      }
}

$cxPrepare = $cxPdo->prepare($cmdSql);

if($cxPrepare->execute()){
    if($cxPrepare->rowCount() > 0){
        $dados= $cxPrepare->fetchAll();
    }
}else{
    var_dump ($cxPrepare);
}
echo json_encode($dados);




?>