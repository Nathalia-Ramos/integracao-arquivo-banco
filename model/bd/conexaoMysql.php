<?php

/*********************************************************************
 * Objetivo: arquivo para criar a conexão com o banco de dados MySQL
 * Autor: Nathalia
 * Data: 25/02/2022
 * Versão: 1.0
 *********************************************************************/

// Constantes para estabelecer a conexao com o banco de dados (local do banco, Ususário, Senha e database)
const SERVER = 'localhost';
const USER = 'root';
const PASSWORD = 'bcd127';
const DATABASE = 'dbcontatos';

$resultado = conexaoMysql();

// Abre a conexao com o banco de dados MySql
function conexaoMysql()
{

    $conexao = array();

    //Se a conecao for estabelecida com o Banco de dados, vamos ter um array de dados sobre a conexao
    $conexao = mysqli_connect(SERVER, USER, PASSWORD, DATABASE);

    //Validação para verificar se a conexao foi realizada com sucesso
    if ($conexao)
        return $conexao;
    else
        return false;
}

// Fecha a conexao com o BD MySql 
function fecharConexaoMySql($conexao)
{
    mysqli_close($conexao);
}

/**
 * Existem 3 formas de criar a conexao com o banco de dados MySql:
 * 
 *      biblioteca: mysql_connect() -> versao antiga do php de fazer conexao com o BD (não oferece performance e segurança)
 * 
 *      biblioteca: mysqli_connect() -> versão mais atualizada do php de fazer a conexao com o BD (melhor performance,  requisitos basicos de segurança, 
 *                                                                                                 
 *                                                                                                   permite ser utilizada para programação estruturada e POO)
 * 
 *      biblioteca: PDO() -> versão mais completa e eficiente para conexao com o banco de dados. Indicada pela segurança e POO(Programação Orientada a Objeto)
 */
