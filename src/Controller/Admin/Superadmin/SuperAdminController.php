<?php

namespace App\Controller\Admin\Superadmin;

use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin/su")
 */
class SuperAdminController extends AbstractController
{
    /**
     * @Route("/upload-video", name="upload_video")
     */
    public function uploadVideo() {

        return $this->render('admin/upload_video.html.twig');
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
