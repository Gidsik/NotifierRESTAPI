<?php
namespace App\Controller\Component;

require_once("W:\domains\NotifierRESTAPI_Diplom/vendor/iCalendar/zapcallib.php");
use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
use ZapCalLib\ZCiCal;
use ZapCalLib\ZCiCalNode;
use ZapCalLib\ZCiCalDataNode;


class NotificationsPawsComponent extends Component
{

  public $components = ['UIDGenerator','ZapCalICal','EventsPaws','NotificatorWorker'];
  public $connection;
  public $deliveryTypesIdByName = ['email' => 1];
  public $deliveryTypesNameById = [1 => 'email'];

  public function initialize(array $config):void
  {
    parent::initialize($config);

    //$this->loadComponent('UIDGenerator');
    //someCode
    //$dsn = 'mysql://root:@localhost/notifierrestapi';
    $this->connection = ConnectionManager::get('default');
  }

  public function createNewNotification($createNotificationData):?object
  {
    try {
      //валидация входных данных
      if (!isset($createNotificationData->event_id)){
        throw new \Exception("event_id required");
      }else {
        $eventObject = $this->connection
                          ->execute('SELECT * from `events` where `id` = :id',['id'=>$createNotificationData->event_id])
                          ->fetchAll('assoc');
        if (!isset($eventObject[0])){
          throw new \Exception("event not found by this id");
        }elseif ($eventObject[0]['status'] != 0) {
          throw new \Exception("event was deleted, this id is not available anymore");
        }
      }
      if (!isset($createNotificationData->title)){
        throw new \Exception("title required");
      }
      if (!isset($createNotificationData->delivery_types)){
        throw new \Exception("delivery_types required");
      }else{
        if (count($createNotificationData->delivery_types) < 1){
          throw new \Exception("at least one delivery_type require");
        }else{
          foreach ($createNotificationData->delivery_types as $key => $value) {
            if (!isset($value->name)||!isset($value->checked)||!isset($value->destination)||!isset($value->text)){
              throw new \Exception("all fields of delivery_type must be set");
              //TODO валидация коректности адреса доставки
            }
          }
        }
      }

      $notificationObject = new NotificationObject();

      //создание объекта
      $occurenceTime = ($this->connection
        ->execute('SELECT `first_occurence` from `events` where `id` = :id',['id' => $createNotificationData->event_id])
        ->fetchAll('assoc'))[0]['first_occurence'];
      $otDate = date_create_from_format("Y-m-d H:i:s", $occurenceTime);

      $notificationObject->id = $id = $this->UIDGenerator->getNextID(2);
      $notificationObject->event = $event = $this->EventsPaws->getEvent($createNotificationData->event_id);
      $notificationObject->title = $title = $createNotificationData->title;
      if (isset($createNotificationData->notify_offset)){
        $notificationObject->notify_offset = $notify_offset = $createNotificationData->notify_offset;

        $noDate = date_create_from_format("Y-m-d H:i:s", $notify_offset);
        //echo $occurenceTime ." | ". ((new \DateTime('0000-00-00'))->diff($noDate))->format("%Y-%m-%d %H:%i:%s")." | ". date_sub($otDate, (new \DateTime('0000-00-00'))->diff($noDate))->format('Y-m-d H:i:s') ." | ". date_sub($otDate, (new \DateTime('0000-00-00'))->diff($noDate))->getTimestamp();

        $notificationObject->notify_time = $notify_time = strval(date_sub($otDate, (new \DateTime('0000-00-00'))->diff($noDate))->format('Y-m-d H:i:s'));
      }else{
        $notificationObject->notify_offset = $notify_offset = "0000-00-00 00:00:00";
        $notificationObject->notify_time = $notify_time = strval($otDate->format('Y-m-d H:i:s'));
      }

      $notificationObject->delivery_types = $createNotificationData->delivery_types;
      $notificationObject->status = $status = 0;

      //сохранение объекта в базу
      $this->connection
        ->execute(
          'INSERT INTO `notifications`
          (`id`,`event_id`,`notify_offset`,`notify_time`,`title`,`status`)
          VALUES
          (:id,:event_id,:no,:nt,:title,:status)',
          ['id' => $id, 'event_id' => $createNotificationData->event_id, 'no' => $notify_offset, 'nt' => $notify_time, 'title' => $title, 'status' => $status]
      );
      foreach ($createNotificationData->delivery_types as $key => $delivery_type) {
        $this->connection
          ->execute(
            'INSERT INTO `delivery_of_notification`
            (`notification_id`,`delivery_type_id`,`status`,`destination`,`notification_text`)
            VALUES
            (:n_id,:dt_id,:st,:dest,:txt)',
            ['n_id' => $id, 'dt_id' => $this->deliveryTypesIdByName[$delivery_type->name], 'st' => $delivery_type->checked == false ? 0 : 1, 'dest' => $delivery_type->destination, 'txt' => $delivery_type->text]
        );
      }
      return $notificationObject;

    } catch (\Exception $e) {
      echo $e;
      return null;
    }
  }

  public function editNotification($id, $createNotificationData):?object
  {
    try {
      $notificationDBInfo = $this->connection
                        ->execute('SELECT `id`,`event_id`,`notify_offset`,`notify_time`,`title`,`status`  from `notifications` where `id` = :id',['id'=>$id])
                        ->fetchAll('assoc');
      if (!isset($notificationDBInfo[0])){
        return new ErrorObj(404);
      }elseif ($notificationDBInfo[0]['status'] != 0) {
        return new ErrorObj(410);
      }else {
        if(array_key_exists('delivery_types', $createNotificationData)){
          if (count($createNotificationData->delivery_types) < 1){
            return new ErrorObj(400);
          }else{
            foreach ($createNotificationData->delivery_types as $key => $value) {
              if (!isset($value->name)||!isset($value->checked)||!isset($value->destination)||!isset($value->text)){
                return new ErrorObj(400);
                //TODO валидация коректности адреса доставки
                //TODO валидация значений на то что оно допустимо
                //TODO валидация text по допустимому размеру в зависимости от доставки
              }
            }
          }
        }
        $notificationObject = new NotificationObject();
        $notificationObject->id = $id;
        $notificationObject->event = $this->EventsPaws->getEvent($notificationDBInfo[0]['event_id']);
        $notificationObject->status = (int)$notificationDBInfo[0]['status'];
        $notificationObject->delivery_types = [];

        if(array_key_exists('event_id', $createNotificationData)){
          return new ErrorObj(400);
        }

        if(array_key_exists('notify_offset', $createNotificationData)){
          $notificationObject->notify_offset = $notify_offset = $createNotificationData->notify_offset;
          $noDate = date_create_from_format("Y-m-d H:i:s", $notify_offset);
          $occurenceTime = ($this->connection
            ->execute('SELECT `first_occurence` from `events` where `id` = :id',['id' => $notificationDBInfo[0]['event_id']])
            ->fetchAll('assoc'))[0]['first_occurence'];
          $otDate = date_create_from_format("Y-m-d H:i:s", $occurenceTime);
          $notificationObject->notify_time = $notify_time = strval(date_sub($otDate, (new \DateTime('0000-00-00'))->diff($noDate))->format('Y-m-d H:i:s'));
        }else {
          $notificationObject->notify_offset = $notificationDBInfo[0]['notify_offset'];
          $notificationObject->notify_time = $notificationDBInfo[0]['notify_time'];
        }

        if(array_key_exists('title', $createNotificationData)){
          $notificationObject->title = $createNotificationData->title;
        }else {
          $notificationObject->title = $notificationDBInfo[0]['title'];
        }

        if(array_key_exists('delivery_types', $createNotificationData)){
          foreach ($createNotificationData->delivery_types as $key => $delivery_type) {
            $dtDBInfo = $this->connection
                          ->execute('SELECT `delivery_type_id`,`status`,`destination`,`notification_text`
                            from `delivery_of_notification`
                            where `notification_id` = :id and `delivery_type_id` = :dtid',
                            ['id'=>$notificationObject->id, 'dtid'=>$this->deliveryTypesIdByName[$delivery_type->name]])
                          ->fetchAll('assoc');
            if (isset($dtDBInfo[0])){
              $this->connection
                ->execute(
                  'UPDATE `delivery_of_notification`
                  SET
                      `status` = :st,
                      `destination` = :dest,
                      `notification_text` = :txt
                  WHERE
                    `notification_id` = :n_id and `delivery_type_id` = :dt_id',
                  ['n_id' => $notificationObject->id, 'dt_id' => $this->deliveryTypesIdByName[$delivery_type->name],
                  'st' => $delivery_type->checked == false ? 0 : 1, 'dest' => $delivery_type->destination, 'txt' => $delivery_type->text]
              );
            }else{
              $this->connection
                ->execute(
                  'INSERT INTO `delivery_of_notification`
                  (`notification_id`,`delivery_type_id`,`status`,`destination`,`notification_text`)
                  VALUES
                  (:n_id,:dt_id,:st,:dest,:txt)',
                  ['n_id' => $id, 'dt_id' => $this->deliveryTypesIdByName[$delivery_type->name],
                  'st' => $delivery_type->checked, 'dest' => $delivery_type->destination, 'txt' => $delivery_type->text]
              );
            }
          }
        }

        $dtDBInfo = $this->connection
                      ->execute('SELECT `delivery_type_id`,`status`,`destination`,`notification_text` from `delivery_of_notification` where `notification_id` = :id',['id'=>$id])
                      ->fetchAll('assoc');
        foreach ($dtDBInfo as $key => $value) {
          $dt = new DeliveryTypeData();
          $dt->name = $this->deliveryTypesNameById[$value['delivery_type_id']];
          $dt->checked = $value['status'] > 0 ? true : false;
          $dt->destination = $value['destination'];
          $dt->text = $value['notification_text'];
          array_push($notificationObject->delivery_types, $dt);
        }




        //сохранение в базу
        $this->connection
            ->execute('UPDATE `notifications`
              SET
                `notify_offset` = :no,
                `notify_time` = :nt,
                `title` = :ttl
              WHERE `id` = :id',
              ['id' => $id, 'no' => $notificationObject->notify_offset,
              'nt' => $notificationObject->notify_time, 'ttl' => $notificationObject->title]);

        if(array_key_exists('notify_offset', $createNotificationData)){
            if ($notificationDBInfo[0]['notify_time'] < $notificationObject->notify_time){
              $this->connection
                ->execute(
                  'UPDATE `delivery_of_notification`
                  SET `status` = 1
                  WHERE `notification_id` = :id and (not `status` = 0)',
                  ['id' => $notificationDBInfo[0]['id']]
              );
            }
        }


        return $notificationObject;
      }
    } catch (\Exception $e) {
      echo $e;
      return new ErrorObj(400);
    }

  }

  public function getNotification($id):?object
  {
    $notificationDBInfo = $this->connection
                      ->execute('SELECT `id`,`event_id`,`notify_offset`,`notify_time`,`title`,`status`  from `notifications` where `id` = :id',['id'=>$id])
                      ->fetchAll('assoc');
    if (!isset($notificationDBInfo[0])){
      return new ErrorObj(404);
    }elseif ($notificationDBInfo[0]['status'] > 0) {
      return new ErrorObj(410);
    }else {
      $notificationObject = new NotificationObject();
      $notificationObject->id = $notificationDBInfo[0]['id'];
      $notificationObject->event = $this->EventsPaws->getEvent($notificationDBInfo[0]['event_id']);
      $notificationObject->notify_offset = $notificationDBInfo[0]['notify_offset'];
      $notificationObject->notify_time = $notificationDBInfo[0]['notify_time'];
      $notificationObject->title = $notificationDBInfo[0]['title'];
      $notificationObject->status = (int)$notificationDBInfo[0]['status'];
      $notificationObject->delivery_types = [];
      $dtDBInfo = $this->connection
                    ->execute('SELECT `delivery_type_id`,`status`,`destination`,`notification_text` from `delivery_of_notification` where `notification_id` = :id',['id'=>$id])
                    ->fetchAll('assoc');
      foreach ($dtDBInfo as $key => $value) {
        $dt = new DeliveryTypeData();
        $dt->name = $this->deliveryTypesNameById[$value['delivery_type_id']];
        $dt->checked = $value['status'] > 0 ? true : false;
        $dt->destination = $value['destination'];
        $dt->text = $value['notification_text'];
        array_push($notificationObject->delivery_types, $dt);
      }
    }

    return $notificationObject;
  }

  public function closeNotification($id):?object
  {

    $notificationDBInfo = $this->connection
                      ->execute('SELECT * from `notifications` where `id` = :id',['id'=>$id])
                      ->fetchAll('assoc');
    if (isset($notificationDBInfo[0])){
      $notificationDBInfo = $this->connection
                        ->execute('UPDATE `notifications` SET `status` = 1 where `id` = :id and not `status` = 1',['id'=>$id])
                        ->rowCount();
      if ($notificationDBInfo == 0){
        return new ErrorObj(410);
      }else {
        return null;
      }
    }else {
      return new ErrorObj(404);
    }
  }

}

/**
 *
 */
class NotificationObject
{
  public $id;
  public $event;
  public $notify_offset;
  public $notify_time;
  public $title;
  public $delivery_types;
  public $status;

  function __construct()
  {
    $this->id = null;
    $this->event = null;
    $this->notify_offset = null;
    $this->notify_time = null;
    $this->title = null;
    $this->delivery_types = null;
    $this->status = null;
  }
}

/**
 *
 */
class DeliveryTypeData
{
  public $name;
  public $checked;
  public $destination;
  public $text;

  function __construct()
  {
    $this->name = null;
    $this->checked = null;
    $this->destination = null;
    $this->text = null;
  }
}
  /**
   *
   */
class ErrorObj
{
  public $errno;
  function __construct($no)
  {
    $this->errno = $no;
  }
}

 ?>
