<?php

namespace Core\File;

class ImageUploader extends Uploader
{
    protected string $rootUpload = ROOTPATH . 'assets/images/';
    private array $allowed_image_exts = [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'webp',
        'bmp',
        'tiff',
        'tiff',
        'svg',
        'ico',
    ];

    public function set_allowed_types(array $allowedFileTypes = [])
    {
        foreach ($allowedFileTypes as $type) {
            if (!$this->is_valid_image($type)) throw new \Exception("MIME type of `$type` is not allowed as Image");
        }
        $this->allowedFileTypes = $allowedFileTypes;
    }

    public function is_valid_image(string $mime_type): bool
    {
        return in_array($mime_type, $this->allowed_image_exts);
    }
}
