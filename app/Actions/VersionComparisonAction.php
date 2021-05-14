<?php

namespace App\Actions;

class VersionComparisonAction implements ActionInterface
{
    private string $version1;
    private string $version2;

    public function __construct(string $version1, string $version2)
    {
        $this->version1 = $version1;
        $this->version2 = $version2;
    }

    public function execute(): bool
    {
        return ($this->optimizedVersion($this->version1) == $this->optimizedVersion($this->version2));
    }

    private function optimizedVersion(string $version): string
    {
        $versionArray = explode('.', $version);

        $versionArray = array_pad($versionArray, 3, '0');

        $versionArray = array_slice($versionArray, 0, 3);

        return implode('.', $versionArray);
    }
}
