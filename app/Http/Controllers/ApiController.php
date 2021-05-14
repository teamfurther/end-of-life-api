<?php

namespace App\Http\Controllers;

use App\Actions\ConvertServerResponseToResponseAction;
use App\Actions\GetFromServerAction;
use App\Router;
use Exception;
use Pecee\Http\Input\InputHandler;

class ApiController extends AbstractController
{
    private InputHandler $inputHandler;

    public function __construct()
    {
        $this->inputHandler = Router::request()->getInputHandler();
    }

    public function index()
    {
        $category = $this->inputHandler->post('category');
        $subCategory = $this->inputHandler->post('sub_category');
        $item = $this->inputHandler->post('item');
        $version = $this->inputHandler->post('version');

        $this->validate([
            'category' => $category,
            'sub_category' => $subCategory,
            'item' => $item,
            'version' => $version,
        ]);

        // Get from server
        try {
            $getFromServerAction = new GetFromServerAction($category, $subCategory, $item, $version);
            $serverResponse = $getFromServerAction->execute();

            // Convert server response to response
            $convertServerResponseToResponseAction = new ConvertServerResponseToResponseAction($serverResponse, $version);
            $response = $convertServerResponseToResponseAction->execute();
            $this->sendResponse($response);
        } catch (Exception $exception) {
            $this->sendResponse($exception->getMessage(), $exception->getCode());
        }
    }
}
