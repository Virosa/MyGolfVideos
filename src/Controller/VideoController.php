<?php

namespace App\Controller;

use App\Entity\Video;
use App\Form\VideoType;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use DateTimeImmutable;


#[Route('/video', name: '')]
class VideoController extends AbstractController
{
    #[Route('/', name: 'app_video')]
    public function index(VideoRepository $videoRepository): Response
    {
        $videos = $videoRepository->findAll();

        return $this->render('video/Video.html.twig', [
            'videos' => $videos,
        ]);
    }

    #[Route('/new', name: 'upload_video')]
    public function new(
        Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, $newFileName): Response
    {
        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $videoFile = $form->get('file')->getData();
            $imageFile = $form->get('image')->getData();
            $currentDateTime = new DateTimeImmutable();
            // Assigner la date et l'heure actuelles à la propriété $datetime de l'objet Video
            $video->setDatetime($currentDateTime);

            $video->setSlug($slugger->slug($video->getTitle())->lower());
            // this condition is needed because the 'category' field is not required
            // so the file must be processed only when a file is uploaded
            if ($videoFile) {
                $originalFilename = pathinfo($videoFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $videoFile->guessExtension();

                // Move the file to the directory where videos are stored
                try {
                    $videoFile->move($this->getParameter('/public/upload/videos'), $newFileName);

                } catch (FileException $e) {
                    return $this->redirectToRoute('app_video');
                }
                $video->setCategory($newFileName);
            }
            $entityManager->persist($video);
            $entityManager->flush();
            return $this->redirectToRoute('app_video');
        }
        return $this->render('video/newVideo.html.twig', [
            'form' => $form,
            'videos' => $video,
        ]);
    }

}