<?php
session_start();
require 'database_connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Verifica se é um cadastro ou uma edição
    if (isset($_POST['create_usuario'])) {
        $nome = mysqli_real_escape_string($conn, trim($_POST['taskName']));
        $cost = mysqli_real_escape_string($conn, trim($_POST['taskCost']));
        $date = mysqli_real_escape_string($conn, trim($_POST['taskDate']));
    
        // Verificar se já existe uma tarefa com o mesmo nome
        $checkSql = "SELECT * FROM todo WHERE Nome_da_tarefa = '$nome'";
        $checkResult = mysqli_query($conn, $checkSql);
    
        if (mysqli_num_rows($checkResult) > 0) {
            // Se já existir, você pode enviar uma mensagem de erro ou alertar
            echo "Erro: Já existe uma tarefa com esse nome.";
        } else {
            // Caso contrário, insere a nova tarefa
            $sql = "INSERT INTO todo (Nome_da_tarefa, Custo, Data) VALUES ('$nome', '$cost', '$date')";
            mysqli_query($conn, $sql);
    
            if (mysqli_affected_rows($conn) > 0) {
                header('location: index.php');
                exit;
            } else {
                echo "Erro ao criar a tarefa.";
            }
        }
    }

    
    if (isset($_POST['update_usuario'])) {
        $tarefaId = mysqli_real_escape_string($conn, $_POST['taskId']);
        $nome = mysqli_real_escape_string($conn, trim($_POST['taskName']));
        $cost = mysqli_real_escape_string($conn, trim($_POST['taskCost']));
        $date = mysqli_real_escape_string($conn, trim($_POST['taskDate']));
    
        // Verificar se já existe uma tarefa com o mesmo nome, mas com ID diferente
        $checkSql = "SELECT * FROM todo WHERE Nome_da_tarefa = '$nome' AND Identificador != '$tarefaId'";
        $checkResult = mysqli_query($conn, $checkSql);
    
        if (mysqli_num_rows($checkResult) > 0) {
            // Se já existir, você pode enviar uma mensagem de erro ou alertar
            echo "Erro: Já existe uma tarefa com esse nome.";
        } else {
            // Caso contrário, atualiza a tarefa
            $sql = "UPDATE todo SET Nome_da_tarefa = '$nome', Custo = '$cost', Data = '$date' WHERE Identificador = '$tarefaId'";
            mysqli_query($conn, $sql);
    
            if (mysqli_affected_rows($conn) > 0) {
                header('location: index.php');
                exit;
            } else {
                echo "Erro ao atualizar a tarefa.";
            }
        }
    }

    
    if (isset($_POST['delete_usuario'])){
        $tarefaId = mysqli_real_escape_string($conn, $_POST['delete_usuario']);
        $sql = "DELETE FROM todo WHERE Identificador = '$tarefaId'";
        
        mysqli_query($conn, $sql);
        
        if (mysqli_affected_rows($conn) > 0){
            header('Location: index.php');
            exit;
        } else{
            header('Location: index.php');
            exit;            
        }
    }
}
?>
