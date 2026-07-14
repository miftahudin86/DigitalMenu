<?php

namespace App\Libraries;

use Aws\S3\S3Client;
use Aws\Exception\AwsException;

class S3Library
{
    protected S3Client $s3;
    protected string $bucket;
    protected string $region;
    protected bool $configured = false;

    public function __construct()
    {
        $key    = env('aws.s3.key', '');
        $secret = env('aws.s3.secret', '');
        $this->region = env('aws.s3.region', 'ap-southeast-1');
        $this->bucket = env('aws.s3.bucket', 'digital-menu-bucket');

        // Only initialize S3 if real credentials are provided
        if ($key && $key !== 'YOUR_AWS_ACCESS_KEY' && $secret && $secret !== 'YOUR_AWS_SECRET_KEY') {
            $this->s3 = new S3Client([
                'version'     => 'latest',
                'region'      => $this->region,
                'credentials' => [
                    'key'    => $key,
                    'secret' => $secret,
                ],
            ]);
            $this->configured = true;
        }
    }

    /**
     * Upload a file to S3 and return the public URL.
     * Returns null if S3 is not configured (local dev mode).
     *
     * @param \CodeIgniter\HTTP\Files\UploadedFile $file
     * @param string $folder  Subfolder inside the bucket (e.g. 'menus')
     * @return string|null
     */
    public function upload($file, string $folder = 'menus'): ?string
    {
        if (!$this->configured) {
            return null;
        }

        $filename  = time() . '_' . $file->getRandomName();
        $key       = $folder . '/' . $filename;
        $mimeType  = $file->getMimeType();

        try {
            $result = $this->s3->putObject([
                'Bucket'      => $this->bucket,
                'Key'         => $key,
                'Body'        => file_get_contents($file->getTempName()),
                'ContentType' => $mimeType,
                'ACL'         => 'public-read',
            ]);

            return $result['ObjectURL'] ?? $this->buildUrl($key);
        } catch (AwsException $e) {
            log_message('error', '[S3Library] Upload failed: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Delete an object from S3 by its URL or key.
     */
    public function delete(string $urlOrKey): bool
    {
        if (!$this->configured) {
            return false;
        }

        // Extract key from full URL if needed
        $key = $this->extractKey($urlOrKey);

        try {
            $this->s3->deleteObject([
                'Bucket' => $this->bucket,
                'Key'    => $key,
            ]);
            return true;
        } catch (AwsException $e) {
            log_message('error', '[S3Library] Delete failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Build a public S3 URL from a key.
     */
    public function buildUrl(string $key): string
    {
        return "https://{$this->bucket}.s3.{$this->region}.amazonaws.com/{$key}";
    }

    /**
     * Extract the S3 key from a full URL.
     */
    protected function extractKey(string $url): string
    {
        // If it already looks like a key (no http), return as-is
        if (!str_starts_with($url, 'http')) {
            return $url;
        }

        $parsed = parse_url($url);
        return ltrim($parsed['path'] ?? $url, '/');
    }
}
