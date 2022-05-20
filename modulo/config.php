<?php 
/*********************************************************************
 * Objetivo: Arquivo responsavel pela criação de variaveis e constantes do projeto
 * Autor: Nathalia
 * Data: 25/04/2022
 * Versão: 1.0
 *********************************************************************/

/*******************VARIAVEIS E CONSTANTES GLOBAIS DO PROJETINHO********************* */



 //Limitação de 5mb para upload de imagens 
 const MAX_FILE_UPLOAD = 5120;

 //Definindo o tipo de extensão que vai ser aceita
 const EXT_FILE_UPLOAD = array("image/jpg", "image/jpeg" , "image/png" , "image/gif"); 

 const DIRETORIO_FILE_UPLOAD = "arquivos/";

define('SRC', ['DOCUMENT_ROOT'].'/Nathalia\integracaoBanco');
echo(SRC);

/*******************FUNÇÕES GLOBAIS PARA O PROJETO*********************** */

//Função para converter um array em formato JSON
function crearJSON($arrayDados)
{
    //Validação para tratar array sem conteúdo
    if(!empty($arrayDados))
    { 
    //Configura o padrão da conversão para o formato JSON
    header('Content-Type: application/json');
    $dadosJSON = json_encode($arrayDados);

    //json_encode(); -> Converte um array para JSON
    //json_decode(); -> Converte um JSON para array
       
        return $dadosJSON;
    }else{ 
        return false;

    }
    

 }







?>