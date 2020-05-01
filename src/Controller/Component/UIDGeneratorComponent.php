<?php
namespace App\Controller\Component;

use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;

class UIDGeneratorComponent extends Component
{

  protected $ID_template = "0000000000000000000000000000000000000000000000000000000000000000"; //64
  protected $IDnum_template = "0000000000000000"; //16
  protected $IDentity_template = "000000"; //6
  protected $IDdate_template = "000000000000000000000000000000000000000000"; //42

  public function initialize(array $config):void
  {
    parent::initialize($config);
    //someCode
    //$dsn = 'mysql://root:@localhost/notifierrestapi';
    $this->connection = ConnectionManager::get('default');
  }

  public function getNextID($entity):String
  {
    $lastID = '';
    switch ($entity) {
      case 1: //events
        $lastID = ($this->connection
          ->execute('SELECT `id` from `events` ORDER BY `id` DESC LIMIT 1')
          ->fetchAll('assoc'));
        break;

      case 2: //notifications
        $lastID = ($this->connection
          ->execute('SELECT `id` from `notifications` ORDER BY `id` DESC LIMIT 1')
          ->fetchAll('assoc'));
        break;

      default:
        // code...
        break;
    }

    //$lastID = "6654056524236783617";
    if (isset($lastID[0])){
      $lastID = $lastID[0]['id'];
    }else {
      $lastID = "0";
    }

    $lastID_bin = substr_replace($this->ID_template, decbin($lastID), -strlen(decbin($lastID)));
    $lastID_date_bin = substr($lastID_bin, 0, 42);
    $lastID_date = bindec($lastID_date_bin);
    $lastID_entity_bin = substr($lastID_bin, 42, 6);
    $lastID_entity = $entity;
    $lastID_num_bin = substr($lastID_bin, -16);
    $lastID_num = bindec($lastID_num_bin);

    $newID_date = round(microtime(true)*1000);
    $newID_date_bin = substr_replace($this->IDdate_template, decbin($newID_date), -strlen(decbin($newID_date)));
    $newID_entity = $entity;
    $newID_entity_bin = substr_replace($this->IDentity_template, decbin($newID_entity), -strlen(decbin($newID_entity)));
    $newID_num = $newID_date==$lastID_date ? $lastID_num + 1 : 1;
    $newID_num_bin = substr_replace($this->IDnum_template, decbin($newID_num), -strlen(decbin($newID_num)));
    $newID_bin = $newID_date_bin . $newID_entity_bin . $newID_num_bin;
    $newID = bindec($newID_bin);

    return $newID;
  }

  public function parseID($ID):array
  {

    $ID_bin = substr_replace($this->ID_template, decbin($ID), -strlen(decbin($ID)));
    $ID_date_bin = substr($ID_bin, 0, 42);
    $ID_date = bindec($ID_date_bin);
    $ID_entity_bin = substr($ID_bin, 42, 6);
    $ID_entity = bindec($ID_entity_bin);
    $ID_num_bin = substr($ID_bin, -16);
    $ID_num = bindec($ID_num_bin);

    return [
      'dec' => [
        'id' => $ID,
        'date' => $ID_date,
        'entity' => $ID_entity,
        'num' => $ID_num
      ],
      'bin' => [
        'id' => $ID_bin,
        'date' => $ID_date_bin,
        'entity' => $ID_entity_bin,
        'num' => $ID_num_bin
      ]
    ];
  }
}

?>
