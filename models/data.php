<?php
// Establish database Connection
$dbconn = null;
    // Access data configurations
if(getenv('DATABASE_URL')){
    $connectionConfig = parse_url(getenv('DATABASE_URL'));
    $host = $connectionConfig['host'];
    $user = $connectionConfig['user'];
    $password = $connectionConfig['pass'];
    $port = $connectionConfig['port'];
    $dbname = trim($connectionConfig['path'],'/');
    $dbconn = pg_connect(
        "host=".$host." ".
        "user=".$user." ".
        "password=".$password." ".
        "port=".$port." ".
        "dbname=".$dbname
    );
} else {
    // Local Host Database
    $dbconn = pg_connect("host=localhost dbname=datagroup user=postgres password=blackdra9891");
}

// Data Object Model
class Data {
  public $name;
  public $info;
  public $id;
    // Construct object properties
  public function __construct($id, $name, $info){
    $this->id = $id;
    $this->name = $name;
    $this->info = $info;
  }
}

// Create Class Factory to build out each object
class DataGroup {
  static function all(){
    $datagroup = array();
    // Select all
    $results = pg_query("SELECT * FROM data");

    $row_object = pg_fetch_object($results);

    // Build out class for each object in the query.
    while($row_object){
      $new_data = new Data(
        intval($row_object->id),
        $row_object->name,
        $row_object->info
      );
    //   add new data row to data group
      $datagroup[] = $new_data;
      $row_object = pg_fetch_object($results);
    }
        // return the group object
    return $datagroup;
  }

//   Handle create requests
  static function create($data){
    $query = "INSERT INTO data (name, info) VALUES ($1, $2)";
    $query_params = array($data->name, $data->info);
    pg_query_params($query, $query_params);
    return self::all();
  }

//   Update / Put Request
  static function update($updated_data){ 
      $query = "UPDATE data SET name = $1, info = $2 WHERE id = $3";
      $query_params = array($updated_data->name, $updated_data->info, $updated_data->id);
      $result = pg_query_params($query, $query_params);

      return self::all();
    }
//  Delete by ID request
    static function delete($id){
      $query = "DELETE FROM data WHERE id = $1";
      $query_params = array($id);
      $result = pg_query_params($query, $query_params);

      return self::all();
    }
}
?>
