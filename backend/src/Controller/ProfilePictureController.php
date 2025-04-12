<?php

namespace App\Controller;

use App\Document\ProfilePicture;
use App\Service\UploadService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
// use Symfony\Component\Routing\Attribute\Route;
// use Symfony\Component\Filesystem\Filesystem; //mba hamorona symlink
// // use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

final class ProfilePictureController extends AbstractController
{

    public function __construct(private DocumentManager $dm) {}

    public function __invoke(Request $request, UploadService $uploadService): JsonResponse
    {
        $user = $this->getUser();

        if (!$user) {
            return new JsonResponse(['Tsy authorized']);
        }

        $file = $request->files->get('file');
        $pictureType = $request->get('pictureType');

        if (!$file) {
            return new JsonResponse(["tsy misy file"]);
        }

        try {
            $profilePicture = new ProfilePicture();
            // ampidirina ao amin'ny File mba ho hitan'ny Vich
            $profilePicture->setPictureFile($file);
            $profilePicture->setPictureType($pictureType);
            $profilePicture->setIsActive(true);
            $profilePicture->setUser($user);
            // manao desactivation ilay taloha
            $profilePicture->deactiveActivePicture();

            $this->dm->persist($profilePicture);
            $this->dm->flush();

            $pictureName = $profilePicture->getPictureName();

            $uploadService->uploadFileWithSymlink('var/uploads/profiles', 'public/uploads/profiles', $pictureName);

            return new JsonResponse(['tonga'], 200);
        } catch (\Exception $e) {
            return new JsonResponse(['message' => $e->getMessage()], 500);
        }
    }
}
