<?php

use Blog\Entity\User;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app->get('/', function (Request $request, Response $response) {
    $controller = new \Blog\Controller\Post($this, $request, $response);
    return $controller->browse();
})->setName('posts');

$app->get('/post/{id:[0-9]+}', function (Request $request, Response $response, $args) {
    $controller = new \Blog\Controller\Post($this, $request, $response);
    return $controller->read($args['id']);
})->setName('post');

$app->get('/post/add', function (Request $request, Response $response) {
    $controller = new \Blog\Controller\Post($this, $request, $response);
    return $controller->add();
})->setName('post-add');

$app->post('/post/add', function (Request $request, Response $response) {
    $controller = new \Blog\Controller\Post($this, $request, $response);
    return $controller->doAdd();
});

$app->get('/post/edit/{id}', function (Request $request, Response $response, $args) {
    $controller = new \Blog\Controller\Post($this, $request, $response);
    return $controller->edit($args['id']);
})->setName('post-edit');

$app->post('/post/edit/{id}', function (Request $request, Response $response, $args) {
    $controller = new \Blog\Controller\Post($this, $request, $response);
    return $controller->doEdit($args['id']);
});

$app->get('/post/delete/{id}', function (Request $request, Response $response, $args) {
    $controller = new \Blog\Controller\Post($this, $request, $response);
    return $controller->delete($args['id']);
})->setName('post-delete');

$app->get('/tag/{id}', function (Request $request, Response $response, $args) {
    $controller = new \Blog\Controller\Tag($this, $request, $response);
    return $controller->read($args['id']);
})->setName('tag');

$app->get('/author/{id}', function (Request $request, Response $response, $args) {
    $controller = new \Blog\Controller\User($this, $request, $response);
    return $controller->read($args['id']);
})->setName('author');

$app->get('/test/{id}', function (Request $request, Response $response, $args) {

    return $response;
})->setName('tag');