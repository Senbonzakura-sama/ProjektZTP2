<?php
/**
 * TagTypeTest.
 */

namespace App\Tests\Form\Type;

use App\Entity\Tag;
use App\Form\Type\TagType;
use Symfony\Component\Form\Test\TypeTestCase;

/**
 * Class TagTypeTest.
 */
class TagTypeTest extends TypeTestCase
{
    /**
     * testSubmitValidDate.
     * @return void
     */
    public function testSubmitValidDate()
    {

        $formatData = [
            'title' => 'TestTag'
        ];

        $model = new Tag();
        $form = $this->factory->create(TagType::class, $model);

        $expected = new Tag();
        $expected->setTitle('TestTag');

        $form->submit($formatData);
        $this->assertTrue($form->isSynchronized());

        $this->assertEquals($expected, $model);
    }
}
