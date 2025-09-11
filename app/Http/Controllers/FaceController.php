<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Providers\RekognitionService;

use Aws\S3\S3Client;

class FaceController extends Controller
{
    protected $rekognition;
    protected $s3;
    protected $bucket;

    public function __construct(RekognitionService $rekognition)
    {
        $this->rekognition = $rekognition;

        // S3 client
        $this->s3 = new S3Client([
            'region'    => env('AWS_DEFAULT_REGION', 'us-east-1'),
            'version'   => 'latest',
            'credentials' => [
                'key'    => env('AWS_ACCESS_KEY_ID'),
                'secret' => env('AWS_SECRET_ACCESS_KEY'),
            ],
        ]);

        $this->bucket = env('AWS_BUCKET');
    }


    public function compare(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:4096', // 4MB
        ]);

        $sourceFile = $request->file('image');
        $sourcePath = $sourceFile->getRealPath();

        // List all images in S3 folder
        $objects = $this->s3->listObjectsV2([
            'Bucket' => $this->bucket,
            'Prefix' => 'faces/',
        ]);

        if (!isset($objects['Contents']) || empty($objects['Contents'])) {
            return response()->json(['match' => false]);
        }

        foreach ($objects['Contents'] as $obj) {
            $targetKey = $obj['Key'];

            try {
                $matches = $this->rekognition->compareFacesLocalVsS3(
                    $sourcePath,
                    $this->bucket,
                    $targetKey,
                    70 // similarity threshold, you can change to 80
                );

                if (!empty($matches)) {
                    return response()->json([
                        'match' => true,
                        'target' => $targetKey,
                        'similarity' => $matches[0]['Similarity'],
                    ]);
                }
            } catch (\Exception $e) {
                // skip errors
                continue;
            }
        }

        return response()->json(['match' => false]);
    }
    public function uploadToS3(Request $request)
    {  
        // $request->validate([
        //     'image' => 'required|image|max:2048', // 2MB max
        // ]);

        // original file name
        $fileName = time() . '_' . time();

        // upload to bucket root or specific folder
        $path = Storage::disk('s3')->putFileAs('faces', $request->file('image'), $fileName);

        // make file public (optional)
        $url = Storage::disk('s3')->url($path);


        return response()->json([
            'message' => 'Image uploaded successfully',
            'path' => $path,
            'url' => $url,
        ]);
    }



    public function compare1(Request $request)
    {
        $request->validate([
            'source' => 'required|image',
            'target' => 'required|image',
        ]);

        echo $sourceBytes = file_get_contents($request->file('source')->getPathname());
        $targetBytes = file_get_contents($request->file('target')->getPathname());

        die;
        $result = $this->rekognition->compareFaces($sourceBytes, $targetBytes);

        if (!empty($result)) {
            return response()->json([
                'match' => true,
                'similarity' => $result[0]['Similarity'],
            ]);
        }

        return response()->json(['match' => false]);
    }
}
