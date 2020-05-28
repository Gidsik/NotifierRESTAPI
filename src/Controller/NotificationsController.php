<?php
namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;

/**
 *
 */
class NotificationsController extends AppController
{
  public function beforeFilter(EventInterface $event)
  {
      parent::beforeFilter($event);
      $this->RequestHandler->accepts(['json']);
      $this->RequestHandler->respondAs('json');
      $this->RequestHandler->requestedWith('json');
      $this->RequestHandler->prefers('json');

      $this->RequestHandler->renderAs($this, 'json'); //important
  }
  public function initialize(): void
  {
    parent::initialize();
    $this->loadComponent('NotificationsPaws');
  }

  function getNotification(...$path): ?Response
  {
    $id = $this->request->getParam('notificationid');

    $notificationObject = $this->NotificationsPaws->getNotification($id);

    if (isset($notificationObject->errno)){
      switch ($notificationObject->errno) {
        case 404:
          return $this->response
                  ->withStatus(404,'notification not found by this id');
          break;
        case 410:
          return $this->response
                  ->withStatus(410,'notification was deleted, this id is not available anymore');
          break;
      }
    }else {
      return $this->response
                ->withStatus(200,'success')
                ->withStringBody(json_encode($notificationObject));
    }
  }

  function createNotification(...$path): ?Response
  {
    $createNotificationData = $this->request->input('json_decode');

    $notificationObject = $this->NotificationsPaws->createNewNotification($createNotificationData);

    if (isset($notificationObject)){
      return $this->response
                ->withStatus(201,'new object successfully created')
                ->withStringBody(json_encode($notificationObject));
    }else {
      return $this->response
                ->withStatus(400,'creation error occured');
    }
  }

  function editNotification(...$path): ?Response
  {
    $id = $this->request->getParam('notificationid');
    $createNotificationData = $this->request->input('json_decode');

    $notificationObject = $this->NotificationsPaws->editNotification($id, $createNotificationData);

    if (isset($notificationObject->errno)){
      switch ($notificationObject->errno) {
        case 400:
          return $this->response
                  ->withStatus(400,'update notification error occured');
          break;
        case 404:
          return $this->response
                  ->withStatus(404,'notification not found by this id');
          break;
        case 410:
          return $this->response
                  ->withStatus(410,'notification was deleted, this id is not available anymore');
          break;
      }
    }else {
      return $this->response
                ->withStatus(200,'success')
                ->withStringBody(json_encode($notificationObject));
    }
  }

  function deleteNotification(...$path): ?Response
  {
    $id = $this->request->getParam('notificationid');

    $notificationObject = $this->NotificationsPaws->closeNotification($id);

    if (isset($notificationObject->errno)){
      switch ($notificationObject->errno) {
        case 404:
          return $this->response
                  ->withStatus(404,'notification not found by this id');
          break;
        case 410:
          return $this->response
                  ->withStatus(410,'notification was deleted, this id is not available anymore');
          break;
      }
    }else {
      return $this->response
                ->withStatus(204,'success');
    }
  }



}

 ?>
