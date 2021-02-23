<?php
// ruta
// http://localhost/tpUTN/public/

//Slim
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;
use Slim\Routing\RouteCollectorProxy;
use Slim\Middleware\ErrorMiddleware;
//JWT
use \Firebase\JWT\JWT;
//Propias
use Clases\Usuario;
//Middleware
use App\Middlewares\JsonMiddleware;
use App\Middlewares\AuthMiddleware;
use App\Middlewares\AdminMiddleware;
use App\Middlewares\SocioMiddleware;
use App\Middlewares\MozoMiddleware;
use App\Middlewares\MozoSocioMiddleware;
use App\Middlewares\NoMozoMiddleware;
use App\Middlewares\ClienteMiddleware;
//Controller
use App\Controllers\UsuariosController;
use App\Controllers\EmpleadoController;
use App\Controllers\LoginController;
use App\Controllers\EmpleadoSistemaController;
use App\Controllers\OperacionSectorController;
use App\Controllers\OperacionSectorEmpleadoController;
use App\Controllers\OperacionEmpleadoController;
use App\Controllers\PedidoController;
use App\Controllers\PedidoDetalleController;
use App\Controllers\PedidosCanceladosController;
use App\Controllers\PedidosNoEntregadosController;
use App\Controllers\MesaController;
use App\Controllers\MesaMasUsadaController;
use App\Controllers\MesaMenosUsadaController;
use App\Controllers\MesaMasFacturoController;
use App\Controllers\MesaMenosFacturoController;
use App\Controllers\MesaMayorImporteController;
use App\Controllers\MesaMenorImporteController;
use App\Controllers\MesaFacturoFechaController;
use App\Controllers\MesaMejorComentarioController;
use App\Controllers\MesaPeorComentarioController;
use App\Controllers\MenuController;
use App\Controllers\MenuMasVendidoController;
use App\Controllers\MenuMenosVendidoController;
use App\Controllers\SocioController;
use App\Controllers\EncuestaController;
use App\Controllers\ClienteController;

//Base de Datos
use Config\Database;
//definimos la zona horaria
date_default_timezone_set('America/Argentina/Buenos_Aires');

require __DIR__ . '/vendor/autoload.php'; // siempre que utilicemos composer hay que poner esto

$app = AppFactory::create();
$app->setBasePath('/tpUTN/public');
new Database;

//Usuario
$app->group('/users', function (RouteCollectorProxy $group) {
    
    //$group->get('/{id}', UserController::class . ":getOne")->add(new UserMiddleware);  // obtener uno solo

    //$group->get('[/]', UserController::class . ":getAll")->add(new UserMiddleware);  // Para obtener todos los registros

    $group->post('[/]', UsuariosController::class . ":add");
    
    $group->put('/{id},{suspension},{baja}', UsuariosController::class . ":update");  //original
}); 

//Login
$app->group('/login', function (RouteCollectorProxy $group) {
    
    $group->post('[/]', LoginController::class . ":crea_token");
    
});
//empleadosistema 7.a Los días y horarios que se Ingresaron al sistema.
$app->group('/empleadosistema', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', EmpleadoSistemaController::class . ":getAll")->add(new SocioMiddleware);
}); 
//operacionessector 7.b- Cantidad de operaciones de todos por sector.
$app->group('/operacionessector', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', OperacionSectorController::class . ":getAll")->add(new SocioMiddleware);
}); 
//operacionessectorempleado 7.c- Cantidad de operaciones de todos por sector listada por cada empleado.
$app->group('/operacionessectorempleado', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', OperacionSectorEmpleadoController::class . ":getAll")->add(new SocioMiddleware);
}); 
//operacionesempleado d- Cantidad de operaciones de cada uno por separado.
$app->group('/operacionesempleado', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', OperacionEmpleadoController::class . ":getAll")->add(new SocioMiddleware);
}); 
//Pedido Cabecera
$app->group('/pedido', function (RouteCollectorProxy $group) {
    
    $group->post('[/]', PedidoController::class . ":add")->add(new MozoMiddleware);
    //$group->put('/{id},{suspension},{baja}', EmpleadoController::class . ":update")->add(new SocioMiddleware);
    //$group->delete('/{id},', EmpleadoController::class . ":delete")->add(new SocioMiddleware);
});
//Pedido Detalle
$app->group('/pedidoDetalle', function (RouteCollectorProxy $group) {
    
    $group->post('[/]', PedidoDetalleController::class . ":add")->add(new MozoSocioMiddleware);
    $group->get('[/]', PedidoDetalleController::class . ":getAll");
    $group->put('/{id},{id_menu},{estado_pedido},{t_estimado},{email}', PedidoDetalleController::class . ":update");//->add(new NoMozoMiddleware);
    //$group->delete('/{id},', EmpleadoController::class . ":delete")->add(new SocioMiddleware);
});
//menumasvendido 8-De las pedidos: a- lo que más se vendió .
$app->group('/menumasvendido', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', MenuMasVendidoController::class . ":getAll")->add(new SocioMiddleware);
}); 
//menumenosvendido 8-De las pedidos: b- lo que menos se vendió .
$app->group('/menumenosvendido', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', MenuMenosVendidoController::class . ":getAll")->add(new SocioMiddleware);
}); 
//pedidoscancelados 8-De las pedidos: d- los cancelados.
$app->group('/pedidoscancelados', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', PedidosCanceladosController::class . ":getAll")->add(new SocioMiddleware);
}); 
//pedidosnoentregados 8-De las pedidos: c- los que no se entregaron en el tiempo estipulado.
$app->group('/pedidosnoentregados', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', PedidosNoEntregadosController::class . ":get")->add(new SocioMiddleware);
}); 
//Mesa
$app->group('/mesa', function (RouteCollectorProxy $group) {
    
    $group->post('[/]', MesaController::class . ":add")->add(new MozoMiddleware);
    $group->put('/{id},{estado_Mesa}', MesaController::class . ":update")->add(new MozoSocioMiddleware);
});
// Mesa mesamasusada 9- De las mesas :a- La más usada.
$app->group('/mesamasusada', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', MesaMasUsadaController::class . ":getAll")->add(new SocioMiddleware);
}); 
// Mesa mesamenosusada 9- De las mesas :b- La menos usada.
$app->group('/mesamenosusada', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', MesaMenosUsadaController::class . ":getAll")->add(new SocioMiddleware);
}); 
// Mesa mesamasfacturo 9- De las mesas :c- La que más facturó.
$app->group('/mesamasfacturo', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', MesaMasFacturoController::class . ":getAll")->add(new SocioMiddleware);
}); 
// Mesa mesamenosfacturo 9- De las mesas :c- La que más facturó.
$app->group('/mesamenosfacturo', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', MesaMenosFacturoController::class . ":getAll")->add(new SocioMiddleware);
}); 
// Mesa mesamayorimporte e- la/s que tuvo la factura con el mayor importe.
$app->group('/mesamayorimporte', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', MesaMayorImporteController::class . ":getAll")->add(new SocioMiddleware);
}); 
// Mesa mesamenorimporte f- la/s que tuvo la factura con el menor importe.
$app->group('/mesamenorimporte', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', MesaMenorImporteController::class . ":getAll")->add(new SocioMiddleware);
}); 
// Mesa mesafacturofecha g- Lo que facturó entre dos fechas una mesa .
$app->group('/mesafacturofecha', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin},{id_mesa}', MesaFacturoFechaController::class . ":getAll")->add(new SocioMiddleware);
}); 
// Mesa mesamejorcomentario h- Mejores comentarios.
$app->group('/mesamejorcomentario', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', MesaMejorComentarioController::class . ":getAll")->add(new SocioMiddleware);
}); 
// Mesa mesapeorcomentario i- Peores comentarios.
$app->group('/mesapeorcomentario', function (RouteCollectorProxy $group) {
    
    $group->get('/{fechainicio},{fechafin}', MesaPeorComentarioController::class . ":getAll")->add(new SocioMiddleware);
}); 
//Menu
$app->group('/menu', function (RouteCollectorProxy $group) {
    
    $group->post('[/]', MenuController::class . ":add")->add(new SocioMiddleware);
});
//Socio
$app->group('/socio', function (RouteCollectorProxy $group) {
    
    $group->put('/{id},{estado_Mesa},{nro_pedido}', socioController::class . ":update")->add(new SocioMiddleware);
    $group->get('[/]', socioController::class . ":getAll");//->add(new SocioMiddleware);
});
//Encuesta
$app->group('/encuesta', function (RouteCollectorProxy $group) {
    
    $group->post('[/]', EncuestaController::class . ":add");
});
//Cliente
$app->group('/cliente', function (RouteCollectorProxy $group) {
    
    $group->get('/{nro_pedido}', ClienteController::class . ":get");
});


$app->any('/{route:.*}', function(Request $request, Response $response) {
    $response = $response->withStatus(404, 'page not found');
    return $response;
});

$app->add(new JsonMiddleware); //Aca agrego mi Middleware - istancio mi clase

$app->addErrorMiddleware(true, true, true);

$app->run();