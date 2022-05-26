<?php 
/*********************************************************************
 * Objetivo: Arquivo  responsavel em realizar uploads de arquivos
 * Autor: Nathalia
 * Data: 25/04/2022
 * Versão: 1.0
 *********************************************************************/


 //função para realizar upload de imagens
function uploadFile($arrayFile){

    require_once(SRC.'modulo/config.php');

    $arquivo = $arrayFile;
    $sizeFile = (int) 0;
    $typeFile = (string) null;
    $nameFile = (string) null;
    $tempFile = (string) null;


    // validação para identificar se existe um arquivo válido(Maior que 0 e que tenha uma extensão)
    if($arquivo ['size'] > 0 && $arquivo ['type'] != "")
    {
        //Recupera o tamanho do arquivo em bytes e converte en kb ( /1024)
        $sizeFile = $arquivo['size']/1024;

        //Recupera o tipo do arquivo
        $typeFile = $arquivo ['type']; 

        //Recupera o nome do arquivo
        $nameFile = $arquivo ['name'];

        //Recupera o caminho do diretorio temporario que está no arquivo
        $tempFile = $arquivo ['tmp_name'];

        //Validação para permitir o upload apenas de arquivo 5mb
        if($sizeFile <= MAX_FILE_UPLOAD)
        {      
                //Validação para permitir somente as extensões válidas
                if(in_array($typeFile, EXT_FILE_UPLOAD ))
                {   
                    //Separa somente o nome do arquivo sem a sua extesão
                    $nome = pathinfo ($nameFile, PATHINFO_FILENAME);

                    //Separa somente a extesãp do arquivo sem o nome 
                    $extensao = pathinfo ($nameFile, PATHINFO_EXTENSION);

                    //Existem diversos algoritmos para criptografia, como:
                        //md5()
                        //sha1()
                        //hash()

                    //md5() -> Gerando uma criptografia de dados
                    /**uniqid -> Gerando uma sequencia numerica diferente 
                                tendo como base configurações da máquina */
                    //time -> Pega a hora, minuto e segundo que está sendo feito o upload da foto           
                    $nomeCripty = md5($nome.uniqid(time()));

                    //Montamos novamente o nome do arquivo com a extesão 
                    $foto = $nomeCripty.".".$extensao;

                    //Envia o arquivo da pasta temporaria do apache para a pasta criada no projeto
                   if(move_uploaded_file($tempFile, SRC.DIRETORIO_FILE_UPLOAD.$foto))
                   {
                        return $foto;
                   }else{
                        return array(
                            'idErro' => 13,
                            'message' => 'Não foi possível mover o arquivo para o servidor');
                   }

                }else{
                    return array(
                        'idErro' => 12,
                        'message' => 'A extensão do arquivo selecionado não é permitida no upload');
                }
            }else{
                return array(
                    'idErro' => 10,
                    'message' => 'Tamanho de arquivo inválido no upload');
            }     

        }else{
            return array(
                'idErro' => 11,
                'message' => 'Não é possível realizar o upload sem um arquivo selecionado');
        }     

    }







?>