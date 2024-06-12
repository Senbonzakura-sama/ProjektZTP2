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
     * Test submitting valid data.
     *
     * @return void
     */
    public function testSubmitValidData()
    {
        $formData = [
            'title' => 'TestTag',
        ];
        $model = new Tag();
        $form = $this->factory->create(TagType::class, $model);
        $expected = new Tag();
        $expected->setTitle('TestTag');
        $form->submit($formData);
        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $model);
        $errors = $form->getErrors(true);
        $this->assertCount(0, $errors);
    }
}
