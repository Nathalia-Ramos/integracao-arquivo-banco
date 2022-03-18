<?php

/*****************************************************************************
 * Objetivo: Arquivo de rota para seguimentar as ações encaminhadas pela View
 *           (dados de um form, listagem de dados, ação de excluir ou atualizar)
 *            Esse arquivo será responsável por encaminhar as solicitações para 
 *            a controller  
 * 
 * Autor: Mariana
 * Data: 04/03/2022
 * Versão: 1.0
 *******************************************/


$action = (string) null;
$component = (string) null;


//Validação para verifivar se a requisição é um POST de um formulário
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

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
                //chama a funcao de inserir na controller
                $resposta = inserirContato($_POST);

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
            }

            break;
    }
}
