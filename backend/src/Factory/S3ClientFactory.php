<?php

declare(strict_types=1);

namespace App\Factory;

use Aws\Credentials\Credentials;
use Aws\S3\S3Client;

/**
 * Class S3ClientFactory.
 */
class S3ClientFactory
{
    public function __construct(
        private string $region,
        private string $key,
        private string $secret,
        private string $endpoint
    ) {
    }

    /**
     * Creates a S3 client.
     */
    public function createClient(): S3Client
    {
        return new S3Client([
            'version' => 'latest',
            'region' => $this->region,
            'credentials' => new Credentials($this->key, $this->secret),
            'endpoint' => $this->endpoint,
            'use_path_style_endpoint' => true,
        ]);
    }
}
