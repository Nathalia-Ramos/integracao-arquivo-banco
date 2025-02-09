<?php

/********************************************************************
 * Objetivo: Arquivo responsável pela manipulação de dados de contato 
 * Obs: Este arquivo fará a ponte entre a View e a Model
 * 
 * Autor: Nathalia
 * Data:  04/03/2022
 * Versão: 1.0
 *******************************************************************/

//função para receber dados da View e encaminhar para a model (Inserir)
function inserirContato($dadosContato)
{   
    //Declaramos essa variavel para 
    $nomeFoto = (string) null;
    

    //validacao para verificar se o objeto esta vazio
    if (!empty($dadosContato)) 
    {
        //Recebe o objeto imagem que foi caminhado dentro do array
        $file = $dadosContato['file'];

        //validacao de caixa vazia dos elementos: nome, celular e email pois são obrugatorias no BD
        if (!empty($dadosContato[0]['Nome']) && !empty($dadosContato[0]['Celular']) && !empty($dadosContato[0]['Email']) && ($dadosContato[0]['Estado'])) 
        {  
            //Validação para idenficar se chegou um arquivo para uploud
            if($file['Foto']['name'] != null)
            {   
                //import da função de uploud
                require_once(SRC.'modulo/upload.php');
                //Chama a função de uploud
                $nomeFoto = uploadFile($file['Foto']);

                if(is_array($nomeFoto))
                {
                    //Caso aconteça algum erro no processo uploud, a função irá retornar um array 
                    //com a possivel mensagem de erro. 
                    //Esse array será retornado para a router e ela irá exibir para o usuário
                    return $nomeFoto;
                }
            }
            //criacao do array de dados que sera encaminhado para a model para inserir no BD,
            // é importante criar esse array conforme a necessidade de manipulação do BD
            //obs: criar as chaves do array conforme os nomes dos atributos do banco de dados 
            $arrayDados = array(
                "nome"     => $dadosContato[0]['Nome'],
                "telefone" => $dadosContato[0]['Telefone'],
                "celular"  => $dadosContato[0]['Celular'],
                "email"    => $dadosContato[0]['Email'],
                "obs"      => $dadosContato[0]['Obs'],         
                "foto"     => $nomeFoto,
                "idestado" => $dadosContato[0]['Estado']
            );

            //import do arquivo de modelagem para manipular o BD
            require_once(SRC.'model/bd/contato.php');

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
function atualizarContato($dadosContato, $id)
{
    $statusUplouad = (boolean) false;

    

    //validacao para verificar se o objeto esta vazio
    if (!empty($dadosContato)) {

        //validacao de caixa vazia dos elementos: nome, celular e email pois são obrugatorias no BD
        if (!empty($dadosContato['txtNome']) && !empty($dadosContato['txtCelular']) && !empty($dadosContato['txtEmail'])) {


            //Validação para garantir que o id seja válido
            if (!empty($id) && $id != 0 && is_numeric($id)) {
                //criacao do array de dados que sera encaminhado para a model para inserir no BD,
                // é importante criar esse array conforme a necessidade de manipulação do BD
                //obs: criar as chaves do array conforme os nomes dos atributos do banco de dados 
                $arrayDados = array(
                    "id"       => $id,
                    "nome"     => $dadosContato['txtNome'],
                    "telefone" => $dadosContato['txtTelefone'],
                    "celular"  => $dadosContato['txtCelular'],
                    "email"    => $dadosContato['txtEmail'],
                    "obs"      => $dadosContato['txtObs'],
                    "idestado" => $dadosContato['sltestado']

                );

                //import do arquivo de modelagem para manipular o BD
                require_once('model/bd/contato.php');

                //chama a funcao que fará o update no banco de dados (essa funcao esta na model)
                if (updateContato($arrayDados))
                    return true;
                else
                    return array('idErro' => 1, 'message' => 'Não foi possível atualizar os dados no banco de dados');
             }else{ 
                    return array(
                        'idErro' => 4,
                        'message' => 'Não é possível atualizar um registro sem informar um Id válido');
                }     
            }else
                return array(
                    'idErro' => 2,
                    'message' => 'Existem campos obrigatórios que não foram preenchidos'
                    );
            
            
    }


}

//função para realizar a exclusão de um contato 
function excluirContato($arrayDados)
{
    //recebe o id do registro que será excluido
    $id = $arrayDados['id'];
    $foto = $arrayDados['foto'];

    // Validação para verificar se id contem um numero valido
    if ($id != 0 && !empty($id) && is_numeric($id)) {

        // Import do arquivo de contato
        require_once(SRC.'model/bd/contato.php');
      //  require_once ('modollo/config.php');

        // Chama a função da model e valida se o retorno foi verdadeiro ou falso
        if (deleteContato($id))
        {   
            //unlink -> função para apagar um arquivo do diretorio
            //permite apagar a foto fisicamente do diretorio no servidor 
            unlink(SRC.DIRETORIO_FILE_UPLOAD.$foto);
            return true;
        else
        {
             return array(
            'idErro' => 5,
            'message' => 'O registro do banco de Dados foi excluído com sucesso, 
            porém a imagem não foi excluída no diretório do servidor'
        );
        }
       
        }
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
    require_once(SRC.'model/bd/contato.php'); 

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
    if ($id != 0 && !empty($id) && is_numeric($id)) {

        require_once(SRC.'model/bd/contato.php');


        //Chama a função na model que vai buscar no BD
        $dados = selectByIdContato($id);

        //Valida se existem dados para serem desenvolvidos
        if (!empty($dados)) {
            return $dados;
        } else {
            return false;
        }
    } else
        return array(
            'idErro' => 4,
            'message' => 'Não é possível buscar um registro sem informar um Id válido'
        );
}
