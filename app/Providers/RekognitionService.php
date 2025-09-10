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

    public function compareFacesLocalVsS3($sourcePathOrBase64, $bucket, $targetKey, $similarityThreshold = 80)
    {
        // Check if input looks like base64 (long string, often contains "/9j/" etc.)
        if (preg_match('/^data:image\/\w+;base64,/', $sourcePathOrBase64) || strlen($sourcePathOrBase64) > 200) {
            $base64String = preg_replace('/^data:image\/\w+;base64,/', '', $sourcePathOrBase64);
            $imageBytes   = base64_decode($base64String);
        } elseif (file_exists($sourcePathOrBase64)) {
            $imageBytes   = file_get_contents($sourcePathOrBase64);
        } else {
            throw new \Exception("Invalid source image input");
        }

        $result = $this->client->compareFaces([
            'SourceImage' => [
                'Bytes' => $imageBytes,
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

    public function compareFacesLocalVsS32($sourcePath, $bucket, $targetKey, $similarityThreshold = 80)
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
