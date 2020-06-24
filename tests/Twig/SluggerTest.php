<?php

namespace App\Tests\Twig;

use App\Twig\AppExtension;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    /**
     * @dataProvider getSlugs
     *
     * @param string $string
     * @param string $slug
     * @return void
     */
    public function testSluggify(string $string, string $slug) {

        $slugger = new AppExtension;
        $this->assertSame($slug, $slugger->slugify($string));
    }

    public function getSlugs() {

        yield ['Lorem Ipsum', 'lorem-ipsum'];
        yield [' Lorem Ipsum', 'lorem-ipsum'];
        yield [' lOrEm iPsUm', 'lorem-ipsum'];
        yield ['!Lorem Ipsum!', 'lorem-ipsum'];
        yield ['lorem-ipsum', 'lorem-ipsum'];
        yield ['Children\'s Books', 'childrens-books'];
        yield ['Five star movies', 'five-star-movies'];
        yield [' Lorem Ipsum', 'lorem-ipsum'];
        yield ['Age 12+', 'age-12'];
    }
}
