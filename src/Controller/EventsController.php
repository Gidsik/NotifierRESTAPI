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
class EventsController extends AppController
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
    $this->loadComponent('EventsPaws');
  }

  function getEvent(...$path): ?Response
  {

    $id = $this->request->getParam('eventid');

    $eventObject = $this->EventsPaws->getEvent($id);

    if (isset($eventObject['errno'])){
      switch ($eventObject['errno']) {
        case 404:
          return $this->response
                  ->withStatus(404,'event not found by this id');
          break;
        case 410:
          return $this->response
                  ->withStatus(410,'event was deleted, this id is not available anymore');
          break;
      }
    }else {
      return $this->response
                ->withStatus(200,'success')
                ->withStringBody(json_encode($eventObject));
    }
  }

  function getEventNotifications(...$path): ?Response
  {
    $id = $this->request->getParam('eventid');

    $this->EventsPaws->getEventNotifications($id);

    $eventObject = "";

    if (isset($eventObject['errno'])){
      switch ($eventObject['errno']) {
        case 404:
          return $this->response
                  ->withStatus(404,'event not found by this id');
          break;
        case 410:
          return $this->response
                  ->withStatus(410,'event was deleted, this id is not available anymore');
          break;
      }
    }else {
      return $this->response
                ->withStatus(200,'success')
                ->withStringBody(json_encode($eventObject));
    }
  }

  function createEvent(...$path): ?Response
  {
    //$this->request->input('json_decode'); //already json_decoded request body
    //$this->request->getBody(); //raw request body

    $createEventData = $this->request->input('json_decode');

    $eventObject = $this->EventsPaws->createNewEvent($createEventData);

    if (isset($eventObject)){
      return $this->response
                ->withStatus(201,'new object successfully created')
                ->withStringBody(json_encode($eventObject));
    }else {
      return $this->response
                ->withStatus(400,'creation error occured');
    }
  }

  function editEvent(...$path): ?Response
  {
    $id = $this->request->getParam('eventid');
    $createEventData = $this->request->input('json_decode');

    $eventObject = $this->EventsPaws->editEvent($id, $createEventData);

    if (isset($eventObject['errno'])){
      switch ($eventObject['errno']) {
        case 400:
          return $this->response
                  ->withStatus(400,'update event error occured');
          break;
        case 404:
          return $this->response
                  ->withStatus(404,'event not found by this id');
          break;
        case 410:
          return $this->response
                  ->withStatus(410,'event was deleted, this id is not available anymore');
          break;
      }
    }else {
      return $this->response
                ->withStatus(200,'success')
                ->withStringBody(json_encode($eventObject));
    }
  }

  function closeEvent(...$path): ?Response
  {
    $id = $this->request->getParam('eventid');

    $eventObject = $this->EventsPaws->closeEvent($id);

    if (isset($eventObject['errno'])){
      switch ($eventObject['errno']) {
        case 404:
          return $this->response
                  ->withStatus(404,'event not found by this id');
          break;
        case 410:
          return $this->response
                  ->withStatus(410,'event was deleted, this id is not available anymore');
          break;
      }
    }else {
      return $this->response
                ->withStatus(204,'success');
    }
  }

}

 ?>
