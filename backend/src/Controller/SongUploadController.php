<?php

namespace App\Controller;

use App\Document\Activity;
use App\Document\Song;
use App\Document\SongCover;
use App\Service\UploadService;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
// use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Filesystem\Filesystem; //mba hamorona symlink
use getID3;
use Symfony\Bundle\SecurityBundle\Security;

final class SongUploadController extends AbstractController
{

    public function __construct(private DocumentManager $dm) {}

    public function __invoke(Request $request, Security $security, UploadService $uploadService): JsonResponse
    {
        // Maka ilay file avy any amin'ny request FormData
        $file = $request->files->get('file');
        $type = $request->get('type');
        $title = $request->get('title');
        $artist = $request->get('artist');
        $album = $request->get('album');
        $genre = $request->get('genre');
        $description = $request->get('description');
        // maka user tafiditra avy amin'ny security
        $user = $security->getUser();

        if (!$file) {
            return new JsonResponse(['error' => 'No file uploaded'], Response::HTTP_BAD_REQUEST);
        }

        try {
            // Mamorona object Song ary apetraka ny file amin'ny audioFile field
            $song = new Song();
            $song->setAudioFile($file);
            $song->setFileType($type);
            $song->setTitle($title);
            $song->setArtist($artist);
            $song->setAlbum($album);
            $song->setGenre($genre);
            $song->setDescription($description);
            $song->setUser($user);



            // maka ilay fileDuration amin'ny GetID3
            if ($file) {
                $getID3 = new getID3();
                $metadata = $getID3->analyze($file->getPathname()); //maka path an'ilay file

                //rehefa azo metadata dia maka izay ilaina, duration no eto ilaina
                $duration = isset($metadata['playtime_seconds']) ? $metadata['playtime_seconds'] : 0;

                $song->setFileDuration($duration);
            }

            // Persist ary flush ny document; VichUploader dia tokony hanavao ny fileName ao amin'ny document
            $this->dm->persist($song);
            $this->dm->flush();

            // alaina fotsiny ilay fileName efa lasa any amin'ny vich
            $fileName = $song->getFileName();

            if (!$fileName) {
                return new JsonResponse(['error' => 'FileName is empty.'], Response::HTTP_INTERNAL_SERVER_ERROR);
            }

            $uploadService->uploadFileWithSymlink('var/uploads/songs', 'public/uploads/songs', $fileName);

            // Maka ny project directory
            // $projectDir = $this->getParameter('kernel.project_dir');
            // // Source directory (izay voatahiry ny rakitra amin'ny VichUploader configuration)
            // $sourceDir = $projectDir . '/var/uploads/songs';
            // // Public directory izay tokony ho accessible (symlink destination)
            // $publicDir = $projectDir . '/public/uploads/songs';

            // $filesystem = new Filesystem();
            // // Ataovy azo antoka fa misy ny public folder
            // $filesystem->mkdir($publicDir, 0777);

            // // Fijerena raha ny file misy ao amin'ny sourceDir dia tena hita
            // $sourceFile = $sourceDir . '/' . $fileName;
            // if (!$filesystem->exists($sourceFile)) {
            //     return new JsonResponse(['error' => 'Source file does not exist: ' . $sourceFile], Response::HTTP_INTERNAL_SERVER_ERROR);
            // }

            // // Mamarina ny public file path (symlink)
            // $publicFile = $publicDir . '/' . $fileName;
            // if (!$filesystem->exists($publicFile)) {
            //     $filesystem->symlink($sourceFile, $publicFile);
            // }


            // coverFile upload block
            $file = $request->files->get('coverFile');

            if (!$file) {
                return new JsonResponse(["tsy misy file"]);
            }

            if (!$song) {
                return new JsonResponse(["tsy misy song avy"]);
            }


            $coverSong = new SongCover();
            // ampidirina ao amin'ny File mba ho hitan'ny Vich
            $coverSong->setCoverFile($file);
            $coverSong->setSong($song);

            $this->dm->persist($coverSong);
            $this->dm->flush();

            $pictureName = $coverSong->getCoverName();

            $uploadService->uploadFileWithSymlink('var/uploads/covers', 'public/uploads/covers', $pictureName);


            // setActivity
            $activity = new Activity();
            $activity->setActivityType('type_upload');
            $activity->setSong($song);
            $activity->setUser($user);
            $this->dm->persist($activity);
            $this->dm->flush();

            return new JsonResponse(["message" => $file->getClientOriginalName()], 201);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], 500);
        }
    }
}
