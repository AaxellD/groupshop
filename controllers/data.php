<?php
include_once __DIR__ . '/../models/joke.php';

header('Content-Type: application/json');


if ($_REQUEST['action'] === 'index') {
    // simple get request ... just load al the class factory objects
  echo json_encode(DataGroup::all());
}

elseif ($_REQUEST['action'] === 'post') {
    // file_get_contents loads document as string
  $request_body = file_get_contents('php://input');
    // json_decode renders it as a JSON Object
  $body_object = json_decode($request_body);
    // uses model to create new class object
  $new_data = new Data(null, $body_object->name, $body_object->info);
    // adds the new data to the datagroup class factory object
  $all_data = DataGroup::create($new_data);
    // Finally --- we render it back in json string format
  echo json_encode($all_jokes);
}

else if ($_REQUEST['action'] === 'update') {
  $request_body = file_get_contents('php://input');
  $body_object = json_decode($request_body);
  $updated_data = new Data($_REQUEST['id'], $body_object->setup, $body_object->delivery);
  $all_data = DataGroup::update($updated_joke);
  echo json_encode($all_data);
}

else if ($_REQUEST['action'] === 'delete') {
  $all_data = DataGroup::delete($_REQUEST['id']);
  echo json_encode($all_data);
}
?>
