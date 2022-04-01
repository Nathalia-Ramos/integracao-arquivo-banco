<?php

/********************************************************************
 * Objetivo: Arquivo responsável pela manipulação de dados de contato 
 * Obs: Este arquivo fará a ponte entre a View e a Model
 * 
 * Autor: Mariana
 * Data: 04/03/2022
 * Versão: 1.0
 *******************************************************************/

//função para receber dados da View e encaminhar para a model (Inserir)
function inserirContato($dadosContato)
{
    //validacao para verificar se o objeto esta vazio
    if (!empty($dadosContato)) {

        //validacao de caixa vazia dos elementos: nome, celular e email pois são obrugatorias no BD
        if (!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])) {
            //criacao do array de dados que sera encaminhado para a model para inserir no BD,
            // é importante criar esse array conforme a necessidade de manipulação do BD
            //obs: criar as chaves do array conforme os nomes dos atributos do banco de dados 
            $arrayDados = array(
                "nome" => $dadosContato['txtNome'],
                "telefone" => $dadosContato['txtTelefone'],
                "celular" => $dadosContato['txtCelular'],
                "email" => $dadosContato['txtEmail'],
                "obs" => $dadosContato['txtObs'],
            );

            //import do arquivo de modelagem para manipular o BD
            require_once('model/bd/contato.php');

            //chama a funcao que fara o insert no banco de dados (essa funcao esta na model)
            if (insertContato($arrayDados))
                return true;
            else
                return array('idErro' => 1, 'message' => 'Não foi possível inserir os dados no banco de dados');
        } else
            return array(
                'idErro' => 2,
                'message' => 'Existem campos obrigatórios que não foram preenchidos'
            );
    }
}
//função para receber dados da View e encaminhar para a model (Atualizar)
function atualizarContato()
{
    
}


//função para realizar a exclusão de um contato 
function excluirContato($id)
{

    // Validação para verificar se id contem um numero valido
    if ($id != 0 && !empty($id) && is_numeric($id)) {

        // Import do arquivo de contato
        require_once('model/bd/contato.php');

        // Chama a função da model e valida se o retorno foi verdadeiro ou falso
        if (deleteContato($id))
            return true;
        else
            return array(
                'idErro' => 3,
                'message' => 'O banco de dados não pode excluir o registro.'
            );
    } else
        return array(
            'idErro' => 4,
            'message' => 'Não é possível excluir um registro sem informar um Id válido'
        );
}


//função para solicitar os dados da model e encaminhar a lista de contatos para a view
function listarContato()
{
    //import do arquivo que vai buscar os dados
    require_once('model/bd/contato.php');

    //chama a funcao que vai buscar os dados no bd
    $dados = selectAllContatos();

    //
    if (!empty($dados))
        return $dados;
    else
        return false;
}

//Função para buscar um contato através do id do registro 
function buscarContato($id)
{   
    //Validação para verificar se id contém um número válido
    if ($id != 0 && !empty($id) && is_numeric($id)){

        require_once('model/bd/contato.php');

    
        //Chama a função na model que vai buscar no BD
        $dados = selectByIdContato($id);

        //Valida se existem dados para serem desenvolvidos
        if(!empty($dados)){
            return $dados;
        }else{
            return false;
        }
        
    }else
        return array(
            'idErro' => 4,
            'message' => 'Não é possível buscar um registro sem informar um Id válido'
        );



}
?>