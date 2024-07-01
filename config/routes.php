<?php

use Cake\Routing\Route\DashedRoute;
use Cake\Routing\RouteBuilder;

return function (RouteBuilder $routes): void {
    $routes->setRouteClass(DashedRoute::class);
    $routes->scope('/api', function (RouteBuilder $builder): void {
        $builder->setExtensions('json', 'xml', 'html');
        $builder->connect('/', ['controller' => 'Pages', 'action' => 'display', 'home']);
        // $builder->connect('/categories/add', ['controller' => 'Categories', 'action' => 'add', '_method' => 'POST']);
        // $builder->connect('/categories', ['controller' => 'Categories', 'action' => 'index']);
        $builder->resources('categories');
        $builder->connect('/pages/*', 'Pages::display');
        $builder->fallbacks();
    });   

    /*
     * If you need a different set of middleware or none at all,
     * open new scope and define routes there.
     *
     * ```
     * $routes->scope('/api', function (RouteBuilder $builder): void {
     *     // No $builder->applyMiddleware() here.
     *
     *     // Parse specified extensions from URLs
     *     // $builder->setExtensions(['json', 'xml']);
     *
     *     // Connect API actions here.
     * });
     * ```
     */
};

// return function(RouteBuilder $routes){
//     $routes->setRouteClass(DashedRoute::class);
//     $routes->scope('/api', function(RouteBuilder $builder){
//         $builder->connect('/categories/add', 
//                             [
//                             'controller' => 'Categories', 
//                             'action' => 'add', 
//                             '_method' => 'POST'
//                         ]);
//     });
//     $routes->fallbacks(DashedRoute::class);
// };
