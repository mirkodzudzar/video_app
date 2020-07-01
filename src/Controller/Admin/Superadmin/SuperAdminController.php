<?php

namespace App\Controller\Admin\Superadmin;

use App\Entity\User;
use App\Entity\Video;
use App\Form\VideoType;
use App\Utils\Interfaces\UploaderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/su")
 */
class SuperAdminController extends AbstractController
{
    // /**
    //  * @Route("/upload-video", name="upload_video")
    //  */
    // public function uploadVideo() {

    //     return $this->render('admin/upload_video.html.twig');
    // }

    /**
     * @Route("/upload-video-locally", name="upload_video_locally")
     */
    public function uploadVideoLocally(Request $request, UploaderInterface $fileUploader) {

        $video = new Video();
        $form = $this->createForm(VideoType::class, $video);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $file = $video->getUploadedVideo();
            // $fileName = $request->get('uploaded_video');
            $fileName = $fileUploader->upload($file);

            $base_path = Video::uploadFolder;
            $video->setPath($base_path.$fileName[0]);
            $video->setTitle($fileName[1]);

            $entityManager->persist($video);
            $entityManager->flush();

            return $this->redirectToRoute('videos');
        }

        return $this->render('admin/upload_video_locally.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/users", name="users")
     */
    public function users() {

        $users = $this->getDoctrine()->getRepository(User::class)->findBy([], ['name' => 'ASC']);

        return $this->render('admin/users.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/delete-user/{user}", name="delete_user")
     */
    public function deleteUser(User $user) {

        $entityManager = $this->getDoctrine()->getManager();
        $name = $user->getName();
        $last_name = $user->getLastName();
        $entityManager->remove($user);
        $entityManager->flush();

        $this->addFlash(
            'success',
            "User $name $last_name has been deleted."
        );

        return $this->redirectToRoute('users');
    }
}
