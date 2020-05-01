<?php
namespace App\Controller;

use Cake\Core\Configure;
use Cake\Http\Exception\ForbiddenException;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Response;
use Cake\View\Exception\MissingTemplateException;
use Cake\ORM\TableRegistry;

/**
 *
 */
class TestZapCalController extends AppController
{

  public function initialize(): void
  {
    parent::initialize();
    $this->loadComponent('TestDBThings');
    $this->loadComponent('ZapCalICal');
  }

  function simpleIcalTest(...$path): ?Response
  {
    $iCalendar = $this->ZapCalICal->createSimpleICal();


    $this->set('iCalendar',$iCalendar);
    return $this->render();
  }

  function index(...$path): ?Response
  {
    $iCalendar = $this->ZapCalICal->createSimpleICal();


    $this->set('iCalendar',$iCalendar);
    return $this->render();
  }

}


?>
