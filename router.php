<?php

/*****************************************************************************
 * Objetivo: Arquivo de rota para seguimentar as ações encaminhadas pela View
 *           (dados de um form, listagem de dados, ação de excluir ou atualizar)
 *            Esse arquivo será responsável por encaminhar as solicitações para 
 *            a controller  
 * 
 * Autor: Nathalia
 * Data: 04/03/2022
 * Versão: 1.0
 *******************************************************************************/


$action = (string) null;
$component = (string) null;


//Validação para verifivar se a requisição é um POST de um formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST' || $_SERVER['REQUEST_METHOD'] == 'GET') {

    //recebendo dados via url para saber quem está solicitando e qual ação será realizada
    $component = strtoupper($_GET['component']);
    $action = strtoupper($_GET['action']);

    //estrutura condicional para validar quem está solicitando algo para o router
    switch ($component) {

        case 'CONTATOS':

            //import da controller contato
            require_once('controller/controllerContatos.php');

            //validacao para identificar o tipo de acao que sera realizada
            if ($action == 'INSERIR') {

                if(isset($_FILES) && !empty ($_FILES))
                {
                   //chama a funcao de inserir na controller
                    $resposta = inserirContato($_POST, $_FILES);
                
                }else{
                    $resposta = inserirContato($_POST, null);
                }

             

                //valida o tipo de dado que a controller retorna
                if (is_bool($resposta)) //se for booleano
                {
                    //verificar se o retorno foi verdadeiro
                    if ($resposta)
                        echo ("<script> 
                                alert('Registro inserido com sucesso!');
                                window.location.href = 'index.php'; 
                            </script>"); // essa funcao retorna a página inicial apos a execuca
                } elseif (is_array($resposta))

                    echo ("<script> 
                        alert('" . $resposta['message'] . "');
                        window.history.back(); 
                   </script>");

            } elseif ($action == 'DELETAR') {

                //Recebe o id do registro que devera ser excluido, 
                //e foi enviado pela url no link da imagem do excluir que foi acionado na index
                $idcontato = $_GET['id'];
                
                //Chama a função de excluir na controller
                $resposta = excluirContato($idcontato);

                if (is_bool($resposta)) {
                    if ($resposta) {
                        echo ("<script> 
                                alert('Registro excluído com sucesso!');
                                window.location.href = 'index.php'; 
                            </script>");
                    }
                } elseif (is_array($resposta)) {
                    echo ("<script> 
                        alert('" . $resposta['message'] . "');
                        window.history.back(); 
                          </script>");
                }  
            }elseif($action == 'BUSCAR'){
                //Recebe o id do registro que devera ser editado, 
                //e foi enviado pela url no link da imagem do editar que foi acionado na index
                $idcontato = $_GET['id'];
                
                //Chama a função de excluir na controller
                $dados = buscarContato($idcontato);

                //Ativa a utilização de variaveis de sessão no servidor
               session_start();

                // Guarda em variavel de sessão os dados que o BD retornou para a busca do id
                    //Obs - essa variavel será utilizada na index.php, 
                      //para colocar os dados nas caixas de texto
               $_SESSION['dadosContato'] = $dados;

                //Utilizando o header tambem poderemos chamar a index.php, 
                    //porem haverá uma ação de carregamento no navegador(piscando a tela novamente)
               
                 //header('location: index.php);

                //Utilizando o require iremos apenas importar a tela da index, 
                   //assim nao havendo um novo carregamento da pagina
               require_once('index.php');

            }elseif($action == 'EDITAR'){

                //Recebe o id que foi encaminhado no action do form pela URL
                $idcontato = $_GET ['id'];
                
                //Chama a função de editar na controller
                $resposta = atualizarContato($_POST, $idcontato);    

                //valida o tipo de dado que a controller retorna
                if (is_bool($resposta)) //se for booleano
                {
                    //verificar se o retorno foi verdadeiro
                    if ($resposta)
                        echo ("<script> 
                                alert('Registro atualizado com sucesso!');
                                window.location.href = 'index.php'; 
                            </script>"); // essa funcao retorna a página inicial apos a execuca
                } elseif (is_array($resposta))

                    echo ("<script> 
                        alert('" . $resposta['message'] . "');
                        window.history.back(); 
                   </script>");
            }
                
            break;
    }
}
