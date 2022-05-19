<?php 


/*****************************************************************************
 * Objetivo:  Objetivo: arquivo responsavel por manipular os dados dentro do BD
 * ( select)
 * Autor: Nathalia
 * Data: 10/05/2022
 * Versão: 1.0
 *******************************************************************************/

 require_once ('conexaoMysql.php');



 function selectAllEstados()
{
    //abre a conexao com o banco de dados
    $conexao = conexaoMysql();

    //script para listar todos os dados do banco de dados 
    $sql = 'select * from tblestados order by nome asc';

    $result = mysqli_query($conexao, $sql); 

    if ($result) {

        $cont = 0;
        
        while ($rsDados = mysqli_fetch_assoc($result)) {
            //cria um array com os dados do banco de dados 
            $arrayDados[$cont] = array(
                "idestao"    =>   $rsDados['idestado'],
                "nome"       =>   $rsDados['nome'],
                "sigla"      =>   $rsDados['sigla']
            );
            $cont++;
        }

        // Solicita o fechamento da conexão com o BD. Ação obrigatória (abrir e fechar) 
        fecharConexaoMySql($conexao);

        return $arrayDados;
    }
}














?>