<?php
require 'vendor/autoload.php';
use Jajo\JSONDB;

$handsDB = new JSONDB( __DIR__ . '/myDatabase');

$uri = $_SERVER['REQUEST_URI'];
$uri = explode('?', $uri)[0];
$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
  case 'GET':
    if($uri == '/new-db') {
      $hands = $handsDB->delete()
      ->from( 'hands.json' )
      ->trigger();

      echo json_encode(['message'=>'new db created']);
      return;
    } 

    if($uri == '/hands') {
      $hands = $handsDB->select( '*' )
      ->from( 'hands.json' )
      ->get();

      echo json_encode(['data'=>$hands]);
      return;
    } 

    if($uri == '/hand') {
      $user = $_GET['user'];
      $computer = $_GET['computer'];
      $result = $_GET['result'];
      $date = new DateTime();
      $time = $date->getTimeStamp();

      $handsDB->insert( 'hands.json',
      [
        'user'=>$user,
        'computer'=>$computer,
        'result'=>$result,
        'time'=>$time
      ]);

      echo json_encode(['message'=>'hand inserted']);
      return;
    } 

    echo json_encode(['data'=>null, 'error'=>'Endpoint not available']);
    return;
    break;
    
  default:
    echo json_encode(['data'=>null, 'error'=>'Method not available']);
    return;
}

?>