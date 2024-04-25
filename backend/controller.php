<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

	function optionsCatalogue (Request $request, Response $response, $args) {
	    
	    // Evite que le front demande une confirmation à chaque modification
	    $response = $response->withHeader("Access-Control-Max-Age", 600);
	    
	    return addHeaders ($response);
	}

	function hello(Request $request, Response $response, $args) {
	    $array = [];
	    $array ["nom"] = $args ['name'];
	    $response->getBody()->write(json_encode ($array));
	    return $response;
	}
	
	function  getSearchCalatogue (Request $request, Response $response, $args) {
	    $flux = '[{"titre":"linux","ref":"001","prix":"20"},{"titre":"java","ref":"002","prix":"21"},{"titre":"windows","ref":"003","prix":"22"},{"titre":"angular","ref":"004","prix":"23"},{"titre":"unix","ref":"005","prix":"25"},{"titre":"javascript","ref":"006","prix":"19"},{"titre":"html","ref":"007","prix":"15"},{"titre":"css","ref":"008","prix":"10"}]';
		
	   $response->getBody()->write($flux);
	   
	    return addHeaders ($response);
	}

	// API Nécessitant un Jwt valide
	function getCatalogue (Request $request, Response $response, $args) {
	    //$flux = '[{"titre":"linux","ref":"001","prix":"20"},{"titre":"java","ref":"002","prix":"21"},{"titre":"windows","ref":"003","prix":"22"},{"titre":"angular","ref":"004","prix":"23"},{"titre":"unix","ref":"005","prix":"25"},{"titre":"javascript","ref":"006","prix":"19"},{"titre":"html","ref":"007","prix":"15"},{"titre":"css","ref":"008","prix":"10"}]';
	    
	    //$response->getBody()->write($flux);
	    
	    //return addHeaders ($response);


        $path = "../frontend/src/app/assets/mock/products.json";

        if (file_exists($path)) {
            $jsonContent = file_get_contents($path);

            $data = json_decode(
            $jsonContent,
            true
        );

        if ($data !== null) {
            $jsonData = json_encode($data);
      
            $response = $response->withHeader('Content-Type', 'application/json');
      
            $response->getBody()->write($jsonData);
      
            return $response;
          }
        }
        return $response->withStatus(500)->getBody()->write("Erreur lors de la récupération du catalogue.");
    }

	function optionsUtilisateur (Request $request, Response $response, $args) {
	    
	    // Evite que le front demande une confirmation à chaque modification
	    $response = $response->withHeader("Access-Control-Max-Age", 600);
	    
	    return addHeaders ($response);
	}

	// API Nécessitant un Jwt valide
	function getUtilisateur (Request $request, Response $response, $args) {
	    
	    $payload = getJWTToken($request);
	    $login  = $payload->userid;
	    
		$flux = '{"nom":"martin","prenom":"louis"}';
	    
	    $response->getBody()->write($flux);
	    
	    return addHeaders ($response);
	}

	// APi d'authentification générant un JWT
	function postLogin (Request $request, Response $response, $args) {   
	    
		$body = $request->getParsedBody();

        if (isset($body['login']) && isset($body['password'])) {
            $username = $body['login'];
            $password = $body['password'];

            if ($username === 'emma' && $password === 'derya') {
                $token = createJWT($response);

                $userData = [
                    'nom' => 'Nom',
                    'prenom' => 'Emma',
                ];
                
                $flux = json_encode($userData);
                $response = createJwt($response, $token);

                $response->getBody()->write($flux);
        
                return addHeaders ($response);
            }
        }   
        $response->getBody()->write(json_encode(['error' => 'Identifiants incorrects']));
        return $response->withStatus(401)->withHeader('Content-Type', 'application/json');
    }