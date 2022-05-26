<?php 
/*********************************************************************************
 * $request - Recebe dados do corpo da requisição (JSOM, FORM/DATA, XML, etc)
 * $response - Envia dados de retono da API
 * $args - Permite receber dados de atributos na API
 *********************************************************************************/

    //import do arquivo autoload, que fará as instancias do slim
    require_once('vendor/autoload.php');

    //Criando um objeto do slim chamado app, para configurar os EndPoint
    $app = new \Slim\App();

    //EndPoint: Requisição para listar todos os contatos
    $app->get('/contatos', function($request, $response, $args){
        require_once('../modulo/config.php');
        require_once('../controller/controllerContatos.php');
        
        //Solicita os dados para a controller
        if($dados = listarContato())
        { 
            //Realiza a conversão do array de dados em formato JSON
            if($dadosJSON = crearJSON($dados))
            {
                //Caso exista dados a serem retornados, informamos o statusCode 200
                // e encviamos um JSON com todos os dados encontrados
                $return    -> withStatus(200)
                           -> withHeader('Content-Type', 'application/json')
                           -> write($dadosJSON);
            }
        }else
        {   
            //Retorna um statusCode que significa que a requisição foi aceita, porém sem conteúdo de retorno
            $return  -> withStatus(404) //Esse numero é apenas para dizer o tipo de erro (status code)
                       -> withHeader('Content-Type', 'application/json')
                       -> write('{"message": "Item não encontrado"}');
        }

    });

    //EndPoint: Requisição para listar todos os contatos pelo id
    $app->get('/contatos/{id}', function($request, $response, $args){
        
        //Recebe o ID do registro que deverá ser retornado pela api
        //Esse ID está chegando pela variavel criada no EndPoint
        $id = $args['id'];

        require_once('../modulo/config.php');
        require_once('../controller/controllerContatos');

        if($dados = buscarContato($id))
        {
            //Verifica se houve algum tipo de erro no retorno dos dados da controller
            if(!isset($dados['idErro']))
            {
                
                if($dadosJSON = crearJSON($dados))
                {   
                    //Comverte para JSON o erro, pois a controller retorna um array
                    $return    -> withStatus(200)
                            -> withHeader('Content-Type', 'application/json')
                            -> write($dadosJSON);
                }

            }else
            {     //Retorna um statusCode que significa que a requisição foi aceita, porém sem conteúdo de retorno
                    $return   -> withStatus(404) //Esse numero é apenas para dizer o tipo de erro (status code)
                              -> withHeader('Content-Type', 'application/json')
                              -> write('{"message": "Dados inválidos ",
                                            "Erro": '.$dadosJSON.'
                                        }');
            }
      
        
        }else
        {
                //Retorna um erro que significa que o cliente passou dados errados
                 $return   -> withStatus(404) //Esse numero é apenas para dizer o tipo de erro (status code)
                           -> withHeader('Content-Type', 'application/json')
                           -> write('{"message": "Item não encontrado"}');
        }
     
        // echo($id);
        // die;
    });

    //EndPoint: Requisição para inserir um novo contato
    $app->post('/contatos', function($request, $response, $args){

        //Recebe do header da requisição qual será o content-type
        $contentTypeHeader = $request->getHeaderLine('content-Type');

        //Cria um array pois depedendo do content-type temos mais informações separadas
        $contentType = explode(";", $contentTypeHeader);
       
        switch ($contentType[0]) {
            case 'multipart/form-data':
                
                //Recebe os dados comuns enviado pelo corpo da requisição
              $dadosBody = $request->getParsedBody();

              //Recebe uma imagem enviada pelo corpo da requisição
              $uploadFile = $request->getUploadedFiles();

              //Cria um array com todos os dados que chegaram pela requisição,dvido aos dados serem protegidos
              //Criamos um array e recuperamos os dados pelos metodos do objeto
              $arrayFoto = array(
                             "name"     =>$uploadFile['foto'] ->getClientFileName(),
                             "type"     =>$uploadFile['foto'] ->getClientMediaType(),
                             "size"     => $uploadFile['foto']->getSize(),
                             "tmo_name" =>$uploadFile['foto'] ->file
                            );

                //Cria uma chave chamada foto para colocar todos os dados do objeto conforme é gerado em form
                $file = array("foto"=>$arrayFoto);

                $arrayDados = array($dadosBody,
                                    "file" => $file
                                );

                require_once('../modulo/config.php');
                require_once('../controller/controlerContatos.php');

                $resposta = inserirContato($arrayDados);
                if(is_bool($resposta) && $resposta == true)
                {
                    return $response  ->withStatus(200)
                                      ->withHeader('Content-Type', 'application/json')
                                      ->write("{'message': 'Inserido}");
                }elseif(is_array($resposta) && $resposta['idErro']){

                    $dadosJSON = crearJSON($resposta);
                    return $response  ->withStatus(400)
                                      ->withHeader('Content-Type', 'application/json')
                                      ->write('{"message": "Houve um problema na hora de inserir ",
                                        "Erro": '.$dadosJSON.'
                                    }');
                }

                return $response  ->withStatus(200)
                                  ->withHeader('Content-Type', 'application/json')
                                  ->write("{'message': 'Formato selecionado foi Formdata'}");
                        
                break;
            case 'application/json':
                return $response  ->withStatus(200)
                                  ->withHeader('Content-Type', 'application/json')
                                  ->write("{'message': 'Formato selecionado foi JSON'}");
                break;

            default:
                return $response  ->withStatus(400)
                                  ->withHeader('Content-Type', 'application/json')
                                  ->write("{'message': 'Formato do content-type não é válido para essa requisição'}");
                break;
        }

    });
    

    
    //EndPint para deleter um id
    $app->delete('/contatos/{id}', function($request, $response, $args){
        
        if(!is_numeric($args['id']))
        {
            //Recebe um ID enviado no EndPoint através da variavel ID
           $id = ($args['id']);

           require_once('../modulo/config.php');
           require_once('../controller/controllerContatos');

           //Busca o nome da foto para ser excluída na controller
          if($dados = buscarContato($id))
          {
              //Busca o nome da foto que a controller retornou
              $foto = $dados['foto'];

              //Cria um array com o ID e o nome da foto a ser enviada para controller excluir o registro
              $arrayDados = array(
                 "id" => $id,
                 "foto" => $foto
              );

              //Chama a função de excluir o contato, encaminhando o array com o ID e a foto
              $resposta = excluirContato($arrayDados);
              if(is_bool($resposta) && $resposta == true)
              {
                return $response   -> withStatus(200) 
                                   -> withHeader('Content-Type', 'application/json')
                                   -> write('{"Registro excluído com sucesso"}');

              }elseif (is_array($resposta) && isset($resposta['idErro']))
              {
                  //Validação referente ao erro 5, que significa que o registro foi EXCLUÍDO do banco de dados
                  // e a não existia no servidor
                  if($resposta ['idErro'] == 5)
                  {
                    return $response   -> withStatus(200) 
                                       -> withHeader('Content-Type', 'application/json')
                                       -> write('{"Registro excluído com sucesso, porém houve um problema na exclusão da imagem na pasta do servidor"}');
                  }else{
                      $dadosJSON = crearJSON($resposta);

                     return  $response  -> withStatus(404)
                                        -> withHeader('Content-Type', 'application/json')
                                        -> write('{"message": "Houve um problema no processo de excluir",
                                                   "Erro": '.$dadosJSON.'
                                                 }');
            
              }
                  }

                
                 
              
          }else{

            return $response   -> withStatus(404) 
                               -> withHeader('Content-Type', 'application/json')
                               -> write('{"O ID informado não existe na base da dados"}');
          }
       
        }else{
              return $response -> withStatus(404) 
                               -> withHeader('Content-Type', 'application/json')
                               -> write('{"é obrigatório informar um ID com formato válido"}');
            }
        
        });
    
        

 
    //Executa todos os EndPoints
    $app-> run();



?>