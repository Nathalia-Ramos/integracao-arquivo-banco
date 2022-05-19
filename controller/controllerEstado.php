<?php 

/********************************************************************
 * Objetivo: Arquivo responsável pela manipulação de dados de estados
 * Obs: Este arquivo fará a ponte entre a View e a Model
 * Autor: Nathalia
 * Data: 10/53/2022
 * Versão: 1.0
 *******************************************************************/

 require_once ('modulo/config.php');

 function listarEstado()
 {
     //import do arquivo que vai buscar os dados
     require_once('model/bd/estado.php');
 
     //chama a funcao que vai buscar os dados no bd
     $dados = selectAllEstados();
 
     if (!empty($dados))
         return $dados;
     else
         return false;
 }
 
















?>