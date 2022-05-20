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
        
        $id = $args['id'];
        if($dados = buscarContato($id))
        {
            if($dadosJSON = crearJSON($id))
            {
                $return    -> withStatus(200)
                           -> withHeader('Content-Type', 'application/json')
                           -> write($dadosJSON);
            }
        
        }else
        {
             //Retorna um statusCode que significa que a requisição foi aceita, porém sem conteúdo de retorno
             $return   -> withStatus(404) //Esse numero é apenas para dizer o tipo de erro (status code)
                       -> withHeader('Content-Type', 'application/json')
                       -> write('{"message": "Item não encontrado"}');
        }
     
        // echo($id);
        // die;
    });

    //EndPoint: Requisição para inserir um novo contato
    $app->post('/contatos', function($request, $response, $args){

    });
    
    //Executa todos os EndPoints
    $app-> run() ;



?>