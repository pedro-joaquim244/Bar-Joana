<?php

session_start();

function estaLogado(): bool
{
    return !empty($_SESSION['usuario_id']);
}

function usuarioId(): mixed
{
    return $_SESSION['usuario_id'] ?? null;
}

function usuarioLogado(mysqli $conn)
{
    $id = usuarioId();
    if (!$id) {
        return null;
    }
    if (
        !empty($_SESSION['usuario']) &&
        is_array($_SESSION['usuario'])
    ) {
        return $_SESSION['usuario'];
    }

    $sql = "SELECT id, nome, email, funcao FROM usuarios WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res && $res->num_rows > 0) {
        $_SESSION['usuario'] = $res->fetch_assoc();
        return $_SESSION['usuario'];
    }

    return null;
}

function login(mysqli $conn, $email, $senha)
{
    $sql = "SELECT id, nome, email, funcao, senha FROM usuarios WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if (!$res || $res->num_rows === 0) {
        return false;
    }

    $usuario = $res->fetch_assoc();

    $hash = (string)($usuario['senha'] ?? '');
    if ($hash === '' || !password_verify($senha, $hash)) {
        return false;
    }

    $_SESSION['usuario_id'] = (int)$usuario['id'];
    $_SESSION['funcao'] = $usuario['funcao'];
    $_SESSION['usuario'] = [
        'id' => (int)$usuario['id'],
        'nome' => $usuario['nome'],
        'email' => $usuario['email'],
        'funcao' => $usuario['funcao'],
    ];
    return true;
}

function logout()
{
    $_SESSION = [];
    session_destroy();
}

?>