<?php

namespace App\Providers;

use Aws\Rekognition\RekognitionClient;

class RekognitionService
{
    protected $client;

    public function __construct()
    {
        $this->client = new RekognitionClient([
            'region'    => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'version'   => 'latest',
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);
    }

    /**
     * Compare a local image with S3 target image
     */
    public function compareFacesLocalVsS3($sourcePath, $bucket, $targetKey, $similarityThreshold = 80)
    {
        $result = $this->client->compareFaces([
            'SourceImage' => [
                'Bytes' => file_get_contents($sourcePath), // local file
            ],
            'TargetImage' => [
                'S3Object' => [
                    'Bucket' => $bucket,
                    'Name'   => $targetKey,
                ],
            ],
            'SimilarityThreshold' => $similarityThreshold,
        ]);

        return $result['FaceMatches'] ?? [];
    }
}
