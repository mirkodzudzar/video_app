<?php

namespace App\Tests\Utils;

use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Twig\AppExtension;

class CategoryTest extends KernelTestCase
{
    protected $mockedCategoryTreeFrontPage;

    protected function setUp() {

        $kernel = self::bootKernel();
        $urlGenerator = $kernel->getContainer()->get('router');
        $this->mockedCategoryTreeFrontPage = $this->getMockBuilder('\App\Utils\CategoryTreeFrontPage')
            ->disableOriginalConstructor()
            // if no, all methods return null unless mocked
            ->setMethods()
            ->getMock();

        $this->mockedCategoryTreeFrontPage->urlGenerator = $urlGenerator;
    }

    /**
     * @dataProvider dataForCategoryTreeFrontPage
     */
    public function testCategoryTreeFrontPage($string, $array, $id) {

        $this->mockedCategoryTreeFrontPage->categoriesArrayFromDb = $array;
        $this->mockedCategoryTreeFrontPage->slugger = new AppExtension;
        $main_parent_id = $this->mockedCategoryTreeFrontPage->getMainParent($id)['id'];
        $array = $this->mockedCategoryTreeFrontPage->buildTree($main_parent_id);

        $this->assertSame($string, $this->mockedCategoryTreeFrontPage->getCategoryList($array));
    }

    public function dataForCategoryTreeFrontPage() {

        yield [
            '<ul><li><a href="/video-list/category/computers,6">Computers</a><ul><li><a href="/video-list/category/laptops,8">Laptops</a><ul><li><a href="/video-list/category/hp,14">HP</a></li></ul></li></ul></li></ul>',
            [
                ["id" => "1", "parent_id" => null, "name" => "Electronics"],
                ["id" => "6", "parent_id" => 1, "name" => "Computers"],
                ["id" => "8", "parent_id" => 6, "name" => "Laptops"],
                ["id" => "14", "parent_id" => 8, "name" => "HP"],
            ],
            1
        ];

        yield [
            '<ul><li><a href="/video-list/category/computers,6">Computers</a><ul><li><a href="/video-list/category/laptops,8">Laptops</a><ul><li><a href="/video-list/category/hp,14">HP</a></li></ul></li></ul></li></ul>',
            [
                ["id" => "1", "parent_id" => null, "name" => "Electronics"],
                ["id" => "6", "parent_id" => 1, "name" => "Computers"],
                ["id" => "8", "parent_id" => 6, "name" => "Laptops"],
                ["id" => "14", "parent_id" => 8, "name" => "HP"],
            ],
            6
        ];

        yield [
            '<ul><li><a href="/video-list/category/computers,6">Computers</a><ul><li><a href="/video-list/category/laptops,8">Laptops</a><ul><li><a href="/video-list/category/hp,14">HP</a></li></ul></li></ul></li></ul>',
            [
                ["id" => "1", "parent_id" => null, "name" => "Electronics"],
                ["id" => "6", "parent_id" => 1, "name" => "Computers"],
                ["id" => "8", "parent_id" => 6, "name" => "Laptops"],
                ["id" => "14", "parent_id" => 8, "name" => "HP"],
            ],
            8
        ];

        yield [
            '<ul><li><a href="/video-list/category/computers,6">Computers</a><ul><li><a href="/video-list/category/laptops,8">Laptops</a><ul><li><a href="/video-list/category/hp,14">HP</a></li></ul></li></ul></li></ul>',
            [
                ["id" => "1", "parent_id" => null, "name" => "Electronics"],
                ["id" => "6", "parent_id" => 1, "name" => "Computers"],
                ["id" => "8", "parent_id" => 6, "name" => "Laptops"],
                ["id" => "14", "parent_id" => 8, "name" => "HP"],
            ],
            14
        ];
    }

}
