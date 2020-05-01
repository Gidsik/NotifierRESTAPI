<?php
namespace App\Controller;

use Cake\Event\EventInterface;
use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;


class MainController extends AppController
{
  public function beforeFilter(EventInterface $event)
  {
      parent::beforeFilter($event);

  }
  public function initialize(): void
  {
    parent::initialize();
    $this->loadComponent('TestDBThings');

  }

  function about(...$path): ?Response
  {
    // echo "<pre>";
    // print_r($this->request->getData());
    // echo "</pre>";

    return $this->render();
  }

  function applications(...$path): ?Response
  {

    return $this->render();
  }

  function docs(...$path): ?Response
  {

    return $this->render();
  }

}


 ?>
