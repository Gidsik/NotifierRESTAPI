<?php
namespace App\Controller\Component;

use Cake\Datasource\ConnectionManager;
use Cake\Controller\Component;

class TestDBThingsComponent extends Component
{
  //public $components = ['OtherComponent']; //добавление компонентов в компонент

  public $connection;

  public function initialize(array $config):void
  {
    parent::initialize($config);
    //someCode
    //$dsn = 'mysql://root:@localhost/notifierrestapi';
    $this->connection = ConnectionManager::get('default');
  }


  public function getCommentsOf($id): array
  {
    $results = $this->connection
      ->execute('SELECT * from test_comments WHERE id_test_user = :id', ['id' => $id])
      ->fetchAll('assoc');
    return $comments = $results;
  }

  public function tryLogin($login,$pswd): bool
  {
    $results = $this->connection
      ->execute('SELECT pswd as hash from test_users WHERE login = :login', ['login' => $login])
      ->fetchAll('assoc');
    return (password_verify($pswd, $results[0]['hash']));
  }

  public function getUser($id): ?array
  {
    $results = $this->connection
      ->execute('SELECT login, pswd from test_users WHERE id = :id', ['id' => $id])
      ->fetchAll('assoc');

    return ($results[0]);
  }

  public function createUser($login,$pswd): int
  {
    $hash = password_hash($pswd, PASSWORD_BCRYPT);
    $results = $this->connection
      ->execute('INSERT INTO test_users (`login`, `pswd`) values (:login, :pswd) ', ['login' => $login, 'pswd' => $hash]);
    $id = $this->connection
      ->execute('SELECT LAST_INSERT_ID() as id')
      ->fetchAll('assoc');

    return $id[0]['id'];
  }

  public function updateUser($id,$login,$pswd): void
  {
    if (is_null($login)&&is_null($pswd)){}
    elseif(is_null($login))
    {
      $hash = password_hash($pswd, PASSWORD_BCRYPT);
      $results = $this->connection
        ->execute('UPDATE test_users set pswd = :pswd WHERE id = :id', ['pswd' => $hash, 'id' => $id]);
    }elseif (is_null($pswd))
    {
      $results = $this->connection
        ->execute('UPDATE test_users set login = :login WHERE id = :id', ['login' => $login, 'id' => $id]);
    }else {
      $hash = password_hash($pswd, PASSWORD_BCRYPT);
      $results = $this->connection
        ->execute('UPDATE test_users set login = :login, pswd = :pswd  WHERE id = :id', ['login' => $login, 'pswd' => $hash, 'id' => $id]);
    }
  }

  public function deleteUser($id): void
  {
    $this->connection
      ->execute('DELETE from test_users WHERE id = :id', ['id' => $id]);
  }
}
