<?php
namespace App\Controller\Component;

require_once("W:\domains\NotifierRESTAPI_Diplom/vendor/iCalendar/zapcallib.php");
use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
use ZapCalLib\ZCiCal;
use ZapCalLib\ZCiCalNode;
use ZapCalLib\ZCiCalDataNode;


class EventsPawsComponent extends Component
{

  public $components = ['UIDGenerator','ZapCalICal'];
  public $connection;

  public function initialize(array $config):void
  {
    parent::initialize($config);

    //$this->loadComponent('UIDGenerator');
    //someCode
    //$dsn = 'mysql://root:@localhost/notifierrestapi';
    $this->connection = ConnectionManager::get('default');
  }

  public function createNewEvent($createEventData):?array
  {
    try {
      //валидация входных данных кроме rrule
      if (!isset($createEventData->event_details_json->name)){
        throw new \Exception("name required");
      }
      if ($createEventData->event_details_json->dateStart >= time()){ //TODO >= switch to <=
        throw new \Exception("dateStart must be more or equal to NOW");
      }
      if (isset($createEventData->event_details_json->dateEnd)){
        if ($createEventData->event_details_json->dateEnd < $createEventData->event_details_json->dateStart){
          throw new \Exception("dateEnd must be more or equal to dateStart");
        }
      }

      //создание объекта
      $id = $this->UIDGenerator->getNextID(1);
      $name = $createEventData->name;
      $event_details = json_decode(json_encode(array('name' => null, 'desc' => null, 'dateStart' => null, 'dateEnd' => null, 'rrule' => null )));
      $event_details->name = $createEventData->event_details_json->name;
      if (isset($createEventData->event_details_json->desc)){
        $event_details->desc = $createEventData->event_details_json->desc;
      }
      $event_details->dateStart = $createEventData->event_details_json->dateStart;
      if (isset($createEventData->event_details_json->dateEnd)){
        $event_details->dateEnd = $createEventData->event_details_json->dateEnd;
      }
      if (isset($createEventData->event_details_json->rrule)){
        $event_details->rrule = $createEventData->event_details_json->rrule;
      }
      $ical_raw = $this->ZapCalICal->createICalFromJson($id,$createEventData->event_details_json);

      $status = 0;


      //сохранение объекта в базу
      $this->connection
        ->execute(
          'INSERT INTO `events`
          (`id`,`name`,`event_details_json`,`ical_raw`)
          VALUES
          (:id,:name,:edj,:ical)', ['id' => $id, 'name' => $name, 'edj' => json_encode($event_details), 'ical' => $ical_raw]
      );

      //$eventObject = $this->connection
      //                  ->execute('SELECT `id`,`name`,`event_details_json`,`ical_raw`,`status` from `events` where `id` = :id',['id'=>$id])
      //                  ->fetchAll('assoc');


      //формирование объекта на выход
      $eventObject = array('id' => $id, 'name' => $name, 'event_details_json' => $event_details, 'ical_raw' => $ical_raw, 'status' => $status );

      return $eventObject;

    } catch (\Exception $e) {
      //echo $e;
      return null;
    }
  }

  public function editEvent($id, $createEventData):array
  {
    try {
      $eventObject = $this->connection
                        ->execute('SELECT `id`,`name`,`event_details_json`,`ical_raw`,`status` from `events` where `id` = :id',['id'=>$id])
                        ->fetchAll('assoc');
      if (!isset($eventObject[0])){
        return ['errno' => 404];
      }elseif ($eventObject[0]['status'] != 0) {
        return ['errno' => 410];
      }else {
        //name
        if(isset($createEventData->name)){
          $name = $createEventData->name;
        }else {
          $name = $eventObject[0]['name'];
        }
        //event_details edited
        $event_details = json_decode(json_encode(array('name' => null, 'desc' => null, 'dateStart' => null, 'dateEnd' => null, 'rrule' => null )));
        $event_details_old = json_decode($eventObject[0]['event_details_json']);

        if(array_key_exists('name', $createEventData->event_details_json)){
          $event_details->name = $createEventData->event_details_json->name;
        }else {
          $event_details->name = $event_details_old->name;
        }
        if(array_key_exists('desc', $createEventData->event_details_json)){
          $event_details->desc = $createEventData->event_details_json->desc;
        }else {
          $event_details->desc = $event_details_old->desc;
        }
        if(array_key_exists('dateStart', $createEventData->event_details_json)){
          $event_details->dateStart = $createEventData->event_details_json->dateStart;
        }else {
          $event_details->dateStart = $event_details_old->dateStart;
        }
        if(array_key_exists('dateEnd', $createEventData->event_details_json)){
          $event_details->dateEnd = $createEventData->event_details_json->dateEnd;
        }else {
          $event_details->dateEnd = $event_details_old->dateEnd;
        }
        if(array_key_exists('rrule', $createEventData->event_details_json)){
          $event_details->rrule = $createEventData->event_details_json->rrule;
        }else {
          $event_details->rrule = $event_details_old->rrule;
        }
        $newIcal = $this->ZapCalICal->createICalFromJson($eventObject[0]['id'],$event_details);

        //сохранение в базу
        $this->connection
            ->execute('UPDATE `events` SET `name` = :name, `event_details_json` = :edj, `ical_raw` = :ical where `id` = :id',
            ['id'=>$eventObject[0]['id'], 'ical'=>$newIcal, 'edj'=>json_encode($event_details), 'name'=> $name]);

        //формирование объекта на выход
        $eventObject = array(
          'id' => $eventObject[0]['id'],
          'name' => $name,
          'event_details_json' => $event_details,
          'ical_raw' => $newIcal,
          'status' => $eventObject[0]['status'] );
        return $eventObject;
      }
    } catch (\Exception $e) {
      echo $e;
      return ['errno' => 400];
    }

  }

  public function getEvent($id):array
  {
    $eventObject = $this->connection
                      ->execute('SELECT `id`,`name`,`event_details_json`,`ical_raw`,`status` from `events` where `id` = :id',['id'=>$id])
                      ->fetchAll('assoc');
    if (!isset($eventObject[0])){
      return ['errno' => 404];
    }elseif ($eventObject[0]['status'] != 0) {
      return ['errno' => 410];
    }else {
      $eventObject = array(
        'id' => $eventObject[0]['id'],
        'name' => $eventObject[0]['name'],
        'event_details_json' => json_decode($eventObject[0]['event_details_json']),
        'ical_raw' => $eventObject[0]['ical_raw'],
        'status' => $eventObject[0]['status'] );
    }
    return $eventObject;
  }

  public function closeEvent($id):?array
  {

    $eventObject = $this->connection
                      ->execute('SELECT * from `events` where `id` = :id',['id'=>$id])
                      ->fetchAll('assoc');
    if (isset($eventObject[0])){
      $eventObject = $this->connection
                        ->execute('UPDATE `events` SET `status` = 1 where `id` = :id and not `status` = 1',['id'=>$id])
                        ->rowCount();
      if ($eventObject == 0){
        return ['errno' => 410];
      }else {
        return null;
      }
    }else {
      return ['errno' => 404];
    }
  }

  public function getEventNotifications($id): ?array
  {
    $this->ZapCalICal->getOccurencesByRRule((new \DateTime())->setTimestamp(1588014186), 'FREQ=HOURLY;UNTIL=20260427T190308;INTERVAL=1;BYSECOND=0,3;BYMINUTE=0,1,2,4;BYHOUR=0,5,2;  BYSETPOS=1;BYDAY=SU,MO;BYMONTHDAY=1;BYMONTH=1;WKST=SU');


    // TODO: получение массива нотификаций
    return null;
  }
}
 ?>
