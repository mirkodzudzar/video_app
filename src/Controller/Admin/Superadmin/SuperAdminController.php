<?php

namespace App\Controller\Admin\Superadmin;

use App\Entity\User;
use App\Entity\Video;
use App\Form\VideoType;
use App\Entity\Category;
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
     * @Route("/upload-video-by-vimeo", name="upload_video_by_vimeo")
    */
    public function uploadVideoByVimeo(Request $request) {

        $vimeo_id = preg_replace('/^\/.+\//','',$request->get('video_uri'));
        if($request->get('videoName') && $vimeo_id)
        {
            $entityManager = $this->getDoctrine()->getManager();
            $video = new Video();
            $video->setTitle($request->get('videoName'));
            $video->setPath(Video::VimeoPath.$vimeo_id);

            $entityManager->persist($video);
            $entityManager->flush();

            return $this->redirectToRoute('videos');
        }
        return $this->render('admin/upload_video_vimeo.html.twig');
    }

    /**
     * @Route("/delete-video/{video}/{path}", name="delete_video", requirements={"path"=".+"})
     */
    public function deleteVideo(Video $video, $path, UploaderInterface $fileUploader) {

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($video);
        $entityManager->flush();

        if ($fileUploader->delete($path)) {
            $this->addFlash(
                'success',
                'The video was successfully deleted.'
            );
        } else {
            $this->addFlash(
                'danger',
                'We were not able to delete, check the video.'
            );
        }

        return $this->redirectToRoute('videos');
    }

    /**
     * @Route("/update-video-category/{video}", methods={"POST"}, name="update_video_category")
    */
    public function updateVideoCategory(Request $request, Video $video)
     {

        $em = $this->getDoctrine()->getManager();

        $category = $this->getDoctrine()->getRepository(Category::class)->find($request->request->get('video_category'));

        $video->setCategory($category);

        $em->persist($video);
        $em->flush();

        return $this->redirectToRoute('videos');
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
