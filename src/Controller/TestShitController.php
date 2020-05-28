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
class TestShitController extends AppController
{
  public function beforeFilter(EventInterface $event)
  {
      parent::beforeFilter($event);
      //$this->RequestHandler->ext = 'json';
      $this->RequestHandler->accepts(['json']);
      $this->RequestHandler->respondAs('json');
      $this->RequestHandler->requestedWith('json');
      $this->RequestHandler->prefers('json');

      $this->RequestHandler->renderAs($this, 'json'); //important
  }
  public function initialize(): void
  {
    parent::initialize();
    $this->loadComponent('TestDBThings');
    $this->loadComponent('NotificatorWorker');
  }

  function send(...$path): ?Response
  {

    $this->NotificatorWorker->checkNotifications();

    return $this->response
            ->withStatus(200,'ok');


  }

  function login(...$path): ?Response
  {
    echo "<pre>";
    print_r($this->request->getData());
    echo "</pre>";

    $isloggedin = $this->TestDBThings->tryLogin($this->request->getParam('login'),$this->request->getParam('pswd'));

    $this->set('login', $this->request->getParam('login'));
    $this->set('pswd', $this->request->getParam('pswd'));
    $this->set('islogged', $isloggedin);
    return $this->render();
  }

  function getUser(...$path): ?Response
  {

    $results = $this->TestDBThings->getUser($this->request->getParam('id'));

    $this->set('status', 'OK');
    $this->set('message', 'user info');
    $this->set('content', $results);
    $this->viewBuilder()->setOption('serialize', array('status', 'message', 'content'));

    return $this->render();
  }

  function createUser(...$path): ?Response
  {
    // echo "<pre>";
    // print_r($this->request->getData());
    // echo "</pre>";

    $id = $this->TestDBThings->createUser($this->request->getData('login'),$this->request->getData('pswd'));
    $this->set('id', $id);
    return $this->render();
  }

  function updateUser(...$path): ?Response
  {
    // echo "<pre>";
    // print_r($this->request->getData());
    // echo "</pre>";

    $this->TestDBThings->updateUser($this->request->getParam('id'),$this->request->getData('login'),$this->request->getData('pswd'));

    return $this->render();
  }

  function deleteUser(...$path): ?Response
  {
    // echo "<pre>";
    // print_r($this->request->getData());
    // echo "</pre>";

    $this->TestDBThings->deleteUser($this->request->getParam('id'));

    return $this->render();
  }
}


 ?>
