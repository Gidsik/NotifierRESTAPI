<?php
/**
 * Routes configuration.
 *
 * In this file, you set up routes to your controllers and their actions.
 * Routes are very important mechanism that allows you to freely connect
 * different URLs to chosen controllers and their actions (functions).
 *
 * It's loaded within the context of `Application::routes()` method which
 * receives a `RouteBuilder` instance `$routes` as method argument.
 *
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

use Cake\Http\Middleware\CsrfProtectionMiddleware;
use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;
use Cake\Routing\Router;

/*
 * The default class to use for all routes
 *
 * The following route classes are supplied with CakePHP and are appropriate
 * to set as the default:
 *
 * - Route
 * - InflectedRoute
 * - DashedRoute
 *
 * If no call is made to `Router::defaultRouteClass()`, the class used is
 * `Route` (`Cake\Routing\Route\Route`)
 *
 * Note that `Route` does not do any inflections on URLs which will result in
 * inconsistently cased URLs when used with `:plugin`, `:controller` and
 * `:action` markers.
 */
/** @var \Cake\Routing\RouteBuilder $routes */
$routes->setRouteClass(DashedRoute::class);

$routes->scope('/', function (RouteBuilder $builder) {
    // Register scoped middleware for in scopes.
    $builder->registerMiddleware('csrf', new CsrfProtectionMiddleware([
        'httpOnly' => true,
    ]));

    /*
     * Apply a middleware to the current route scope.
     * Requires middleware to be registered through `Application::routes()` with `registerMiddleware()`
     */
    //$builder->applyMiddleware('csrf'); //важно, нужно будет узнать как это влияет на безопасность

    /*
     * Here, we are connecting '/' (base path) to a controller called 'Pages',
     * its action called 'display', and we pass a param to select the view file
     * to use (in this case, templates/Pages/home.php)...
     */
    // $builder->connect(
    //   '/',
    //   ['controller' => 'Pages', 'action' => 'display', 'home']
    // );
    //
    // $builder->connect(
    //   '/pages/*',
    //   ['controller' => 'Pages', 'action' => 'display']
    // );

//------------------------------------------------------------------------------
//______________________________TestShitController______________________________

    $builder->connect(
      '/api/testShit/users/:id',
      ['controller' => 'TestShit', 'action' => 'getUser', 'login']
    )
    ->setPass(['id'])
    ->setMethods(['GET'])
    ->setExtensions(['json']);

    $builder->connect(
      '/api/testShit/users',
      ['controller' => 'TestShit', 'action' => 'createUser', 'login']
    )
    ->setMethods(['POST'])
    ->setExtensions(['json']);

    $builder->connect(
      '/api/testShit/users/:id',
      ['controller' => 'TestShit', 'action' => 'updateUser', 'login']
    )
    ->setPass(['id'])
    ->setMethods(['PUT'])
    ->setExtensions(['json']);

    $builder->connect(
      '/api/testShit/users/:id',
      ['controller' => 'TestShit', 'action' => 'deleteUser', 'login']
    )
    ->setPass(['id'])
    ->setMethods(['DELETE'])
    ->setExtensions(['json']);

    $builder->connect(
      '/api/testShit/users/:login/:pswd',
      ['controller' => 'TestShit', 'action' => 'login']
    )
    //->setPatterns(['id' => '\d+'])
    ->setPass(['login','pswd'])
    ->setExtensions(['json']);

//------------------------------------------------------------------------------
//_____________________________TestZapCalController_____________________________

    $builder->connect(
      '/api/testShit/zapcal/simple',
      ['controller' => 'TestZapCal', 'action' => 'simpleIcalTest']
    )
    ->setMethods(['GET']);


//------------------------------------------------------------------------------
//_______________________________EventsController_______________________________

    $builder->connect(
      '/api/events/:eventid',
      ['controller' => 'Events', 'action' => 'getEvent']
    )
    ->setPass(['eventid'])
    ->setMethods(['GET'])
    ->setExtensions(['json']);

    $builder->connect(
      '/api/events/:eventid/notifications',
      ['controller' => 'Events', 'action' => 'getEventNotifications']
    )
    ->setPass(['eventid'])
    ->setMethods(['GET'])
    ->setExtensions(['json']);

    $builder->connect(
      '/api/events/new',
      ['controller' => 'Events', 'action' => 'createEvent']
    )
    ->setPass(['appid'])
    ->setMethods(['POST'])
    ->setExtensions(['json']);

    $builder->connect(
      '/api/events/:eventid',
      ['controller' => 'Events', 'action' => 'editEvent']
    )
    ->setPass(['eventid'])
    ->setMethods(['PUT'])
    ->setExtensions(['json']);

    $builder->connect(
      '/api/events/:eventid',
      ['controller' => 'Events', 'action' => 'closeEvent']
    )
    ->setPass(['eventid'])
    ->setMethods(['DELETE'])
    ->setExtensions(['json']);

//------------------------------------------------------------------------------
//____________________________NotificationsController___________________________

    $builder->connect(
      '/api/notifications/:notificationid',
      ['controller' => 'Notifications', 'action' => 'getNotification']
    )
    ->setPass(['eventid'])
    ->setMethods(['GET'])
    ->setExtensions(['json']);

    $builder->connect(
      '/api/notifications/new',
      ['controller' => 'Notifications', 'action' => 'createNotification']
    )
    ->setMethods(['POST'])
    ->setExtensions(['json']);

    $builder->connect(
      '/api/notifications/:notificationid',
      ['controller' => 'Notifications', 'action' => 'editNotification']
    )
    ->setPass(['eventid'])
    ->setMethods(['PUT'])
    ->setExtensions(['json']);

    $builder->connect(
      '/api/notifications/:notificationid',
      ['controller' => 'Notifications', 'action' => 'deleteNotification']
    )
    ->setPass(['eventid'])
    ->setMethods(['DELETE'])
    ->setExtensions(['json']);

//------------------------------------------------------------------------------

    $builder->fallbacks();
});

//------------------------------------------------------------------------------
//_____________________________BuiltInCakeAPIRoutes_____________________________
//______________________________not cool to use...______________________________

//Router::extensions(['json']);
//Router::scope('/api/testShit/', function ($routes) {
//	$routes->resources('Testzapcal');
//});

/*
 * If you need a different set of middleware or none at all,
 * open new scope and define routes there.
 *
 * ```
 * $routes->scope('/api', function (RouteBuilder $builder) {
 *     // No $builder->applyMiddleware() here.
 *     // Connect API actions here.
 * });
 * ```
 */