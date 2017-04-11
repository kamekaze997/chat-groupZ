<?php

//Turn on the errors on PHP (just in case they were off)
error_reporting(E_ALL);
ini_set('display_errors', '1');

//autoload for the PHP classes from composer
require 'vendor/autoload.php';
//autoload for the PHP classes from our model
require 'model/autoload.php';

//Start the Slim application
$app = new \Slim\Slim(array(
    'debug' => true
));

/**
 * Create a new ChatMessage
 * Mehod: POST
 * URI: /message/
 * URI Parameters: No parameters needed
 * Request JSON: 
 * {
 *      "message": "message content written by the user",
 *      "author_id": 2,
 *      "timeline_id": 2
 * }
 * */
$createMesageFunction = function() use ($app){

    $requestString = $app->request->getBody();
    //decoding the request body from a json-string into a $resquest object.
    $request = json_decode($requestString);
    //here we validate that the POST request came with the poper body json
    if(!$request or !isset($request->message) or !isset($request->author_id) or !isset($request->timeline_id))
    {
        throwError($app,"Unable to get the request body, the method expects: message, author_id, timeline_id");
        return;
    }
    
    //In order to create a ChatMessage, we have to first get the ChatUser
    //because the only way to set the author of a message is with the setter 
    //function of the ChatMessage class, and that function receives a ChatUser
    $author = new ChatUser();
    $author = $author->select($request->author_id);
    //if nothin came from the DN
    if(!$author){
        //call the throwError function to set the reponse object
        throwError($app,"Unable to get the author (".$request->author_id.") from the DB");
        //break the execution of this function
        return;
    } 

    //In order to create a ChatMessage, we have to first get the ChatTimeline
    //were it belongs because the timline setter for the ChatMessage receives
    //a ChatTimeline object, not just the id   
    $timeline = new ChatTimeline();
    $timeline = $timeline->select($request->timeline_id);
    
    //if no timeline was found with that id
    if(!$timeline){
        //call the throwError function to set the reponse object
        throwError($app,"Unable to get the timeline (".$request->timeline_id.") from the DB");
        //break the execution of this function
        return;
    } 
    
    //Now that I have the $author and $timeline object, I can go ahead and
    //insert the chat message into the DB
    $chatMessage = new ChatMessage();
    $chatMessage->setContent($request->message);
    $chatMessage->setAuthor($author);
    $chatMessage->setTimeline($timeline);
    //if the insert had any issues
    if(!$chatMessage->insert())
    {
        throwError($app,"Error inserting the message into the DB");
        return;
    }

    //if the application gets to this point, it means everything was a success
    //is time to return the chat message to the front-end as a json
    throwSuccess($app,$chatMessage);
    
};

$getMesageFunction = function($id) use ($app){
    $message = new ChatMessage();
    $message->select($id);
    
    throwSuccess($app,$message);
};
//$getdeleteFunction = function($id) use ($app){};
    
$getChatTimelineFunction = function($id) use ($app){
    $timeline = new ChatTimeline();
    $timeline->select($id);
    
    throwSuccess($app,$timeline);
};

/**
 * My API will respond with a json object with 2 properties: -code and -msg|data
 * If everything seems fine I return a code=200 and the $data.
 * If there as an error when trying to compelte the request y return code=200
 * and a $msg with the description of the error instead of the $data itself.
 **/
function throwError($app,$msg){
    $errorArray = array(
        "code"=> 500,
        "msg"=>  $msg
        );
    $jsonResult = json_encode($errorArray);
    
    $app->response->setBody($jsonResult);
    
    //set the reponse code to 200, that way the jquery $.ajax function 
    //on the front-end side will trigger the "error"
    $app->response->setStatus(500);
    //All the API reponses are going to be JSON
    $app->response->headers->set('Content-Type', 'application/json');
    //finalize the app
    $app->response->finalize();
    
    //run the application
}

function throwSuccess($app,$data){
    //if the data is not an array, I need to convert it to an array
    //because the json_encode function receives an array
    if(!is_array($data)) $data = $data->toArray();
    
    $successArray = array(
        "code"=> 200,
        "data"=> $data 
        );
        
    $jsonResult = json_encode($successArray);
    if(!$jsonResult) return throwError($app,"Unable to parse the success array");
    
    $app->response->setBody($jsonResult);
    //All the API reponses are going to be JSON
    $app->response->headers->set('Content-Type', 'application/json');
    //set the reponse code to 200, that way the jquery $.ajax function will success
    $app->response->setStatus(200);
    $app->response->finalize();
    
}

$app->post('/message/', $createMesageFunction);
$app->get('/message/:id', $getMesageFunction);
//$app->get('/chat_timeline/:id', $getTimelineFunction);
//$app->post('/message/:id', $getMeesageFunction($id));

$app->run();