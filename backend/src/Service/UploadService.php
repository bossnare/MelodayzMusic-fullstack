<?php

namespace App\Service;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;
use Vich\UploaderBundle\Storage\StorageInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class UploadService 
{
    private Filesystem $filesystem;

    private string $projectDir;

    public function __construct(Filesystem $filesystem, KernelInterface $kernel) {
        $this->filesystem = $filesystem;
        $this->projectDir = $kernel->getProjectDir();
    }
    public function uploadFileWithSymlink(string $targetDir, string $symlinkDir, string $pictureName) {
        //izany hoe path source anefasana file sy path symlink
        $targetPath = $this->projectDir . '/' . $targetDir;
        $symlinkPath = $this->projectDir . '/' . $symlinkDir;

        // Ataovy azo antoka fa misy ny public folder
        $this->filesystem->mkdir($symlinkPath, 0777);
        
        // Fijerena raha ny file misy ao amin'ny sourceDir dia tena hita
        $sourceFile = $targetPath . '/' . $pictureName;
        if (!$this->filesystem->exists($sourceFile)) {
            return new JsonResponse(['error' => 'Source file does not exist: ' . $sourceFile], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
        
        // Mamarina ny public file path (symlink)
        $publicPicture = $symlinkPath . '/' . $pictureName;
        if (!$this->filesystem->exists($publicPicture)) {
            $this->filesystem->symlink($sourceFile, $publicPicture);
        }

    }

}