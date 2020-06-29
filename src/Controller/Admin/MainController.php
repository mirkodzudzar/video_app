<?php

namespace App\Controller\Admin;

use App\Entity\Video;
use App\Utils\CategoryTreeAdminOptionList;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/admin")
 */
class MainController extends AbstractController
{
    /**
     * @Route("/", name="admin_main_page")
     */
    public function index() {

        return $this->render('admin/my_profile.html.twig');
    }

    public function getAllCategories(CategoryTreeAdminOptionList $categories, $editedCategory = null) {

        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $categories->getCategoryList($categories->buildTree());

        return $this->render('admin/includes/_all_categories.html.twig', [
            'categoreis' => $categories,
            'editedCategory' => $editedCategory,
        ]);
    }

    /**
     * @Route("/videos", name="videos")
     */
    public function videos() {

        if ($this->isGranted('ROLE_ADMIN')){
            $videos = $this->getDoctrine()->getRepository(Video::class)->findAll();
        } else {
            $videos = $this->getUser()->getLikedVideos();
        }

        return $this->render('admin/videos.html.twig', [
            'videos' => $videos,
        ]);
    }
}
