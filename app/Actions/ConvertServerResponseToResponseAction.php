<?php

namespace App\Actions;

use Carbon\Carbon;
use Exception;

class ConvertServerResponseToResponseAction implements ActionInterface
{
    private string $serverResponse;
    private string $version;

    public function __construct(string $serverResponse, string $version)
    {
        $this->serverResponse = $serverResponse;
        $this->version = $version;
    }

    /**
     * @throws Exception
     */
    public function execute()
    {
        $object = $this->getObjectFromResponseByVersion();
        $latest = $this->getLatestVersion();

        if (is_null($object)) {
            $error = json_encode([
                'errors' => [
                    'http' => 'Not found!'
                ]
            ]);

            throw new Exception($error, 404);
        }

        return json_encode([
            'latest' => $object->isLatest,
            'latest_version' => $latest->version,
            'active_support' => Carbon::now()->lessThanOrEqualTo(Carbon::parse($object->active_support_until)),
            'active_support_until' => $object->active_support_until,
            'security_support' => Carbon::now()->lessThanOrEqualTo(Carbon::parse($object->security_support_until)),
            'security_support_until' => $object->security_support_until,
        ]);
    }

    private function getLatestVersion(): object
    {
        $object = (array)json_decode($this->serverResponse);
        $versions = array_values((array)$object['versions']);
        $versionNames = array_keys((array)$object['versions']);

        $latest = (object)array_shift($versions);
        $latest->version = array_shift($versionNames);

        return $latest;
    }

    private function getObjectFromResponseByVersion(): ?object
    {
        $object = (array)json_decode($this->serverResponse);
        $nr = 0;

        foreach ($object['versions'] as $version => $data) {
            $nr++;
            $versionComparisonAction = new VersionComparisonAction($this->version, $version);

            if ($versionComparisonAction->execute()) {
                $data->isLatest = ($nr == 1);
                return $data;
            }
        }

        return null;
    }
}
