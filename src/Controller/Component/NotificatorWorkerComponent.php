<?php
namespace App\Controller\Component;

require_once("W:\domains\NotifierRESTAPI_Diplom/vendor/iCalendar/zapcallib.php");
use Cake\Controller\Component;
use Cake\Datasource\ConnectionManager;
use ZapCalLib\ZCiCal;
use ZapCalLib\ZCiCalNode;
use ZapCalLib\ZCiCalDataNode;

use Cake\Mailer\Email;


class NotificatorWorkerComponent extends Component
{

  public $components = ['UIDGenerator','ZapCalICal','EventsPaws','NotificationsPaws'];
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

  public function checkNotifications():?array
  {
    try{
      $toNotificateDB = $this->connection
                        ->execute('SELECT * from `notifications` where `notify_time` <= now() and `status` = 0')
                        ->fetchAll('assoc');
      foreach ($toNotificateDB as $key => $value) {
        $this->notificate($value['id'],$value['event_id'],$value['title']);
      }

      return null;
    }catch (\Exception $e) {
      //echo $e;
      return ['err' => $e];
    }
  }

  public function notificate($id,$eventId,$title):?array
  {
    echo " <br> notificate_$id > ";
    $deliveries = $this->connection
                      ->execute('SELECT * from `delivery_of_notification` where `notification_id` = :id and `status` = 1',['id'=>$id])
                      ->fetchAll('assoc');
    foreach ($deliveries as $key => $value) {
      switch ($this->deliveryTypesNameById[$value['delivery_type_id']]) {
        case 'email':
          $result = $this->sendEmail($title,$value['destination'],$value['notification_text']);
          if (!isset($result['err'])){
            $this->connection
                ->execute('UPDATE `delivery_of_notification` SET `status` = 2
                  where `notification_id` = :id and `status` = 1 and `delivery_type_id` = :dtid',
                  ['id' => $id, 'dtid' => $value['delivery_type_id']]);
          }
          break;
      }
    }
    echo "check_deliveries > ";
    $deliveries = $this->connection
                      ->execute('SELECT * from `delivery_of_notification` where `notification_id` = :id and `status` = 1',['id'=>$id])
                      ->fetchAll('assoc');
                      echo count($deliveries);
    if (count($deliveries)==0){

      $nextNotifyTime = $this->ZapCalICal->getNextNotify($id,$eventId);

      if (is_null($nextNotifyTime)){
        echo "next_is_null > ";
        return null;
      }
echo "db_update_1 > ";
      $this->connection
          ->execute('UPDATE `notifications` SET `notify_time` = :nt
            where `id` = :id',
            ['id' => $id, 'nt' => $nextNotifyTime]);
echo "db_update_2 > ";
      $this->connection
          ->execute('UPDATE `delivery_of_notification` SET `status` = 1
            where `notification_id` = :id and `status` > 0',
            ['id' => $id]);

    }
    echo "end >";
    return null;
  }

  public function sendEmail($title,$dest,$text):?array
  {
    try{
      $email = new Email('gmail');

      $result = $email
        ->setTo($dest)
        ->setEmailFormat('html')
        ->setSubject($title)
        ->send($text);
      return $result;

    }catch (\Exception $e) {
      //echo $e;
      return ['err' => $e];
    }
  }


}

 ?>
