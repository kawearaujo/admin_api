<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Headers: *');
require 'vendor/autoload.php';
require_once 'Connection.php';
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

$dados = file_get_contents('php://input');
$dados = json_decode($dados, true);
if (isset($dados['email']) && !empty($dados['email'])){
    $email= $dados['email'];
}
if (isset($dados['password']) && !empty($dados['password'])){
    $password= $dados['password'];
}

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$conn =new Connection();
$cxPdo = $conn->connect();

$cmdSql="SELECT * FROM users WHERE email='$email' AND password='$password'";

$cxPrepare = $cxPdo->prepare($cmdSql);
if($cxPrepare->execute()){
    if($cxPrepare->rowCount() > 0){
        $dados= $cxPrepare->fetch();
        // var_dump($dados);
        $nome=$dados->name;   
        // echo( $nome);
        // exit;
        $payload = [
            "exp"=>time()+1000000,
            "iat"=>time(),
            "email"=>$email,
            "name"=>$nome,
        ];

        $encode = JWT::encode($payload, $_ENV['KEY'], 'HS256');
        // $decoded = JWT::decode($encode,  new Key($_ENV['KEY'], 'HS256'));

        echo json_encode($encode);
    }
    
}else{
    var_dump($cxPrepare);
}