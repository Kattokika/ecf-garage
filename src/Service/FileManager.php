<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class FileManager
{
    public function __construct(
        private readonly string $targetDirectory,
    ) {
    }

    public function upload(UploadedFile $file): string
    {
        $fileName = uniqid('picture-').'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }


    public function remove(mixed $filenames): void
    {
        if (!\is_array($filenames)) {
            $filenames = [$filenames];
        }
        $filesystem = new Filesystem();

        foreach ($filenames as $filename) {
            $filepath = $this->getTargetDirectory() . '/' . $filename;

            if ($filesystem->exists($filepath)) {
                $filesystem->remove($filepath);
            }
        }
    }

    public function getTargetDirectory(): string
    {
        return $this->targetDirectory;
    }
}
