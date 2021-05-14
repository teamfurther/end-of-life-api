<?php

namespace App\Actions;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

class GetFromServerAction implements ActionInterface
{
    private string $category;
    private string $item;
    private string $subCategory;

    public function __construct(string $category, string $subCategory, string $item)
    {
        $this->category = $category;
        $this->subCategory = $subCategory;
        $this->item = $item;
    }

    /**
     * @throws Exception
     */
    public function execute(): string
    {
        $baseUrl = 'https://raw.githubusercontent.com/teamfurther/end-of-life/master/';
        $url = $baseUrl . $this->category . '/' . $this->subCategory . '/' . $this->item . '.json';

        $client = new Client();

        try {
            return $client->get($url)
                ->getBody();
        } catch (GuzzleException $exception) {
            $error = json_encode([
                'errors' => [
                    'http' => $exception->getMessage()
                ]
            ]);

            if ($exception->getCode() == 404) {
                $error = json_encode([
                    'errors' => [
                        'http' => 'Not found!'
                    ]
                ]);
            }

            throw new Exception($error, $exception->getCode());
        }
    }
}
