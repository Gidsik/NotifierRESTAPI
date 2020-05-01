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
class ApplicationController extends AppController
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
    $this->loadComponent('TestDBThings');
  }

  public function getAppInfo(): ?Response
  {

    return $this->render();
  }

  public function getAppEvents(): ?Response
  {

    return $this->render();
  }

  public function getAppPeople(): ?Response
  {

    return $this->render();
  }

  public function createApp(): ?Response
  {

    return $this->render();
  }

  public function editAppInfo(): ?Response
  {

    return $this->render();
  }

  public function deleteApp(): ?Response
  {

    return $this->render();
  }

}

 ?>
