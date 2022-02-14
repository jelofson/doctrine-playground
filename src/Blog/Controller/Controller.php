<?php
namespace Blog\Controller;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Container\ContainerInterface;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpForbiddenException;
use Slim\Exception\HttpBadRequestException;
use Slim\Psr7\Response;
use Doctrine\ORM\Mapping\ClassMetadata;
use Blog\Entity\EntityInterface;

// Type hints for some methods available via __get (in the container)
/**
 * @property \Doctrine\ORM\EntityManager $entityManager
 */

/**
 * @property \League\Plates\Engine $view
 */

abstract class Controller
{
    const ACTION_ADD = 'add';
    const ACTION_EDIT = 'edit';

    /**
     * The PSR-11 container interface
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * The PSR 7 request
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * The PSR 7 response
     * @var \Psr\Http\Message\ResponseInterface
     */
    protected $response;


    /**
     * Constructor
     *
     * @param ContainerInterface $container
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     */
    public function __construct(ContainerInterface $container, ServerRequestInterface $request, ResponseInterface $response)
    {
        $this->container = $container;
        $this->request = $request;
        $this->response = $response;
    }

    public function toArray(ClassMetadata $metadata, EntityInterface $entity)
    {
        $fieldNames = $metadata->getFieldNames();
        $columnNames = $metadata->getColumnNames();

        $data = [];
        foreach ($fieldNames as $key=>$name) {
            $value = $metadata->getFieldValue($entity, $name);
            if (is_object($value) && get_class($value) == \DateTime::class) {
                $value = $value->format('Y-m-d H:i:s');
            }
            $data[$columnNames[$key]] = $value;
        }
        return $data;
    }

    /**
     * Magic method to retrive properties of the class, or services in the $services array if they exist
     *
     * @param string $property The property name
     */
    public function __get($property)
    {
        if (property_exists($this, $property)) {
            return $this->$property;
        }

        if ($this->container->has($property)) {
            return $this->container->get($property);
        }

        trigger_error('Undefined property ' . $property . ' in ' . get_class($this));
    }

    protected function asHtml(string $content): Response
    {
        $this->response->getBody()->write($content);
        return $this->response;
    }

    protected function asJson(array $data, $status = 200): Response
    {
        $this->response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT));
        return $this->response->withHeader('Content-type', 'application/json')->withStatus($status);
    }

    /**
     * Set a 404 not found status with an appropriate view template.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function notFound()
    {
        
        throw new HttpNotFoundException($this->request, 'Item Not Found');
    }

    /**
     * Set a 403 not allowed status with an appropriate view template.
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function notAllowed()
    {
        throw new HttpForbiddenException($this->request, 'You are not allowed to perform this action');
    }

    /**
     * Set a 400 bad request status with an appropriate view template.
     *
     * @return \Psr\Http\Message\ResponseInterface
     * @throws HttpBadRequestException
     */
    protected function badRequest($note = null)
    {
        $message = 'Bad Request';
        if ($note) {
            $message .= ' ' . $note;
        }
        throw new HttpBadRequestException($this->request, $message);

    }

    /**
     * Redirect to a differnet url
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    protected function redirect($url, $status = 301)
    {
        return $this->response->withStatus($status)->withHeader('Location', $url);
    }


    /**
     * Sanitize the posted data. Override as necessary. This only works on strings
     * 
     * @return array Array of cleaned elements from post
     */
    protected function sanitizePost()  
    {
        $fields = array_keys($this->request->getParsedBody());
        $clean = [];


        foreach ($fields as $field) {
            $clean[$field] = filter_input(INPUT_POST, $field, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        }

        return $clean;
    }
}