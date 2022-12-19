<?php

use App\Models\Db as ModelsDB;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Selective\BasePath\BasePathMiddleware;
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../vendor/autoload.php';

$app = AppFactory::create();

$app->addRoutingMiddleware();
$app->add(new BasePathMiddleware($app));
$app->addErrorMiddleware(true, true, true);
$app->addBodyParsingMiddleware();


 $app->post('/change-password', function (Request $request, Response $response, array $args) {
     $data = $request->getParsedBody();
     $user_email = $data["user_email"];
     $user_pass = $data["user_pass"];


     try {
         $db = new ModelsDB();
         $conn = $db->connect();

        //  $changePasswordRepository = new ChangePasswordRepository();
        //  $hash_password = $changePasswordRepository->wp_hash_password($user_pass);

         $sql = "UPDATE wp_users SET user_pass = :user_pass WHERE user_email = :user_email";

         $stmt = $conn->prepare($sql);
         $stmt->bindParam(':user_email', $user_email);
         $stmt->bindParam(':user_pass', $user_pass);

         //check if email exists in database
         $stmt->execute();
         $result = $stmt->rowCount();

         if ($result == 0) {
             $db = null;

             $error = array(
                 "message" => "Email does not exist"
             );

             $response->getBody()->write(json_encode($error));
             return $response
                 ->withHeader('content-type', 'application/json')
                 ->withStatus(422);
         } else {
             $db = null;

             $response->getBody()->write(json_encode($result));

             //show success message
             $success = array(
                 "message" => "Password changed successfully"
             );

             $response->getBody()->write(json_encode($success));

             return $response
                 ->withHeader('content-type', 'application/json')
                 ->withStatus(200);
         }

     } catch (\PDOException $e) {
         $error = array(
             "message" => $e->getMessage()
         );

         $response->getBody()->write(json_encode($error));
         return $response
             ->withHeader('content-type', 'application/json')
             ->withStatus(500);
     }
 });

$app->run();
