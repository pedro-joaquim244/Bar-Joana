<?php



session_start();

function estaLogado() : bool {
return !empty($_SESSION['usuario_id']);

}


function usuarioId(){
    return $_SESSION['usuario_id'] ?? null;
}

function usuarioLogado(mysqli $conn){
    $id= usuarioId();
    if(!$id){
        return null;
    }
if(!empty($_SESSION['usuario']) && is_array (value: $_SESSION['usuario']))
{
    $_SESSION['usuario'];
}
$sql="SELECT id, nome, email, funcao from usuarios WHERE id";
$stmt= $conn->prepare(query:$sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();
if($res && $res->num_rows > 0){
    return $_SESSION['usuario'];
}
return null;

}

function login(mysqli $conn, $email, $senha ) {
    $sql="SELECT id, nome, email, funcao from usuarios WHERE email";
$stmt= $conn->prepare(query:$sql);
$stmt->bind_param("s", $email);
$stmt->execute();
$res = $stmt->get_result(); 
if (!$res || $res->num_rows === 0){
    return false;
}

$usuario = $res->fetch_assoc();
if(!password_verify($senha, $usuario['senha']))
    {
        return false;
    }

    $_SESSION['usuario_id'] = (int)$usuario['id'];
    $_SESSION['funcao'] = $usuario['funcao'];
    $_SESSION['usuario'] = [
        'id' => (int)$usuario['id'],
        'nome' => (int)$usuario['nome'],
        'email' => (int)$usuario['email'],
        'funcao' => (int)$usuario['funcao'],
    ];
    return false; 
}


function logout()
{
    $_SESSION=[];
    session_destroy();
}