<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Lista</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
  </head>
  <body>
    <div class="container mt-4">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Tarefas

                        </h4>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>Identficador</th>
                                    <th>Nome da tarefa</th>
                                    <th>Custo</th>
                                    <th>Data limite</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                require 'database_connection.php';
                                $sql = 'SELECT * FROM todo';
                                $tarefas = mysqli_query($conn, $sql);
                                
                                if (mysqli_num_rows($tarefas) > 0){
                                    foreach($tarefas as $tarefa){
                                ?>
                                <tr>
                                    <td><?=$tarefa['Identificador']?></td>
                                    <td><?=$tarefa['Nome_da_tarefa']?></td>
                                    <td id="coust"><?="R$".number_format($tarefa['Custo'],2,",",".")?></td>
                                    <td><?=date('d/m/Y', strtotime($tarefa['Data']))?></td>
                                    <td>
                                        <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#editModal" data-id="<?=$tarefa['Identificador']?>">Editar</button>
                                        <form action="save_task.php" method="POST" class="d-inline">
                                            <button onclick="return confirm('Tem certeza que deseja excluir?')" type="submit" name="delete_usuario" value="<?=$tarefa['Identificador']?>" class="btn btn-danger btn-sm">Excluir</button>
                                        </form>
                                    </td>
                                </tr>
                                <?php
                                }
                                } else {
                                    echo '<h5>Nenhuma tarefa encontrada</h5>';
                                }
                                ?>
                            </tbody>
                        </table>
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#janelaModal">Incluir tarefa</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="janelaModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Cadastro</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="save_task.php" method="POST">
                <input type="text" name="taskName" placeholder="Nome da Tarefa" class="form-control">
                <input type="number" name="taskCost" placeholder="Custo" class="form-control">
                <input type="date" name="taskDate" placeholder="Data" class="form-control">
                
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Cancelar</button>
                
                <button type="submit" name="create_usuario" class="btn btn-primary btn-sm">Salvar</button>
            </form>
          </div>
        </div>
      </div>
    </div>
    
    <div class="modal fade" id="editModal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Editar</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form action="save_task.php" method="POST">
                <input type="hidden" id="taskId" name="taskId">
                <input type="text" id="taskName" name="taskName" placeholder="Nome da Tarefa" class="form-control">
                <input type="number" id="taskCost" name="taskCost" placeholder="Custo" class="form-control">
                <input type="date" id="taskDate" name="taskDate" placeholder="Data" class="form-control">
                
                <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Cancelar</button>
                <button type="submit" name="update_usuario" class="btn btn-primary btn-sm">Salvar</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        document.querySelectorAll('.btn-info').forEach(button => {
            button.addEventListener('click', function () {
                const tarefaId = this.getAttribute('data-id');
                
                const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                editModal.show();
        
                fetch('buscar_tarefa.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `id=${tarefaId}`
                })
                .then(response => response.text()) // Primeiro pegue como texto para ver qualquer HTML ou erro
                .then(text => {
                    console.log("Resposta bruta:", text); // Verifique se há HTML ou mensagem de erro
                    return JSON.parse(text); // Converta para JSON se tudo estiver correto
                })
                .then(data => {
                    console.log(data);
                    if (data.error) {
                        console.error(data.error);
                    } else {
                        document.getElementById('taskId').value = tarefaId;
                        document.getElementById('taskName').value = data.Nome_da_tarefa;
                        document.getElementById('taskCost').value = data.Custo;
                
                        // Formatar a data para o formato yyyy-MM-dd
                        const formattedDate = new Date(data.Data).toISOString().split('T')[0];
                        document.getElementById('taskDate').value = formattedDate;
                    }
                })
                .catch(error => console.error('Erro:', error));
            });
        });
        
        document.querySelectorAll('td[id="coust"]').forEach(function(coustElement) {
            // Extrair o valor numérico da célula e formatar corretamente
            let coustString = coustElement.innerText.replace('R$', '').trim(); // Remover 'R$' e espaços extras
            coustString = coustString.replace('.', '').replace(',', '.'); // Substituir ponto por nada e vírgula por ponto
            
            // Converter para número
            let coust = parseFloat(coustString);
            
            console.log(coust); // Verificar se o número está correto
            
            // Verificar se o valor é maior ou igual a 1000
            if (coust >= 1000) {
                coustElement.style.backgroundColor = "yellow"; // Alterar o fundo da célula
            }
    });

    </script>

  </body>
</html>
