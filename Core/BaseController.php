<?php

namespace Core;

/**
 * Base Controller.
 *
 * @author  Ozhan Duran <ozhan@hotmail.com>
 *
 * @version 1.0.0
 *
 * @since 24.12.2017
 */
abstract class BaseController
{
    private $errorData = null;
    protected $requestData = null;

    public function __construct()
    {
        // Convert Json to Array and set requestData
        if (preg_match("/application\/json/i", $_SERVER['CONTENT_TYPE'])) {
            if (!empty(file_get_contents('php://input'))) {
                $this->requestData = json_decode(file_get_contents('php://input'), 1);

                if (JSON_ERROR_NONE !== json_last_error()) {
                    $this->displayError(10102, 'Incorrect json data!');
                }
            }
        }
    }

    /**
     * HTML Render.
     *
     * @param  $view [View name]
     * @param  $data [View data]
     */
    protected function render($view, $data = null)
    {
        define('ASSETS', '/psr4/public/assets');
        if (func_num_args() > 2) {
            $data = func_get_args();
            array_shift($data);
        }

        $viewPath = $_SERVER['DOCUMENT_ROOT'] . '/psr4/App/Views/' . $view . '.phtml';

        ob_start();
        require_once $viewPath;
        ob_end_flush();
    }

    /**
     * JSON Render For REST.
     *
     * @param  $data [Render data]
     */
    public function rest($data = null)
    {
        header('Content-Type: application/json; charset=UTF-8');

        if (count($this->errorData) > 0) {
            $renderData['error'] = $this->errorData;
        } else {
            $renderData['error'] = ['status' => false];
        }

        if (null !== $data) {
            $renderData['data'] = $data;
        }

        echo json_encode($renderData);
        exit;
    }

    protected function displayError($errorCode, $errorDescription = null)
    {
        if (!isset($this->errorCodes[$errorCode])) {
            $data = [
                'status' => true,
                'code' => $errorCode,
                'description' => 'Could not found errorCode: ' . $errorCode,
            ];
        }

        if (isset($this->errorCodes[$errorCode]) && null === $errorDescription) {
            $data = [
                'status' => true,
                'code' => $errorCode,
                'description' => $this->errorCodes[$errorCode],
            ];
        }

        $this->errorData = $data;
        $this->rest();
    }

    /**
     * Auto Generated Doc.
     *
     * @method("GET")
     */
    public function doc()
    {
        echo '<pre>';
        $reflect = new \ReflectionClass(get_called_class());
        $className = substr($reflect->getShortName(), 0, -10);
        foreach ($reflect->getMethods() as $m) {
            if ($m->class == get_called_class()) {
                $doc = $reflect->getMethod($m->name)->getDocComment();
                preg_match_all("#@(description|method)\((.*\"*)\)#", $doc, $docMatches, PREG_PATTERN_ORDER);

                if (!empty($docMatches[2])) {
                    echo '<li>' . $docMatches[2][0];
                    echo '<li>URL: ' . mb_strtolower($className . '/' . $m->name);
                    echo '<li>Method: ' . $docMatches[2][1];
                    echo '<hr>';
                }
            }
        }
        echo '</pre>';
    }

    /**
     * InputSanitize.
     */
    private function inputSanitize($var)
    {
        return htmlspecialchars(strip_tags($var), ENT_QUOTES, 'UTF-8');
    }
}
