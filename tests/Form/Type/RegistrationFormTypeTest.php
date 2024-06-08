<?php
/**
 * RegistrationFormTypeTest.
 */
namespace App\Tests\Form\Type;

use App\Form\Type\RegistrationFormType;
use Symfony\Component\Form\Extension\Validator\ValidatorExtension;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\Validator\Validation;

/**
 * Class RegistrationFormTypeTest.
 */
class RegistrationFormTypeTest extends TypeTestCase
{
    /**
     * testSubmitValidData.
     * @return void
     */
    public function testSubmitValidData()
    {
        $formData = [
            'email' => 'test@example.com',
            'password' => [
                'first' => 'password123',
                'second' => 'password123',
            ],
            'nickname' => 'testuser',
        ];

        $model = [];
        $form = $this->factory->create(RegistrationFormType::class, $model);

        $expected = [
            'email' => 'test@example.com',
            'password' => 'password123',
            'nickname' => 'testuser',
        ];

        $form->submit($formData);

        $this->assertTrue($form->isSynchronized());
        $this->assertEquals($expected, $form->getData());

        $view = $form->createView();
        $children = $view->children;

        foreach (array_keys($formData) as $key) {
            $this->assertArrayHasKey($key, $children);
        }
    }

    /**
     * testBuildForm.
     * @return void
     */
    public function testBuildForm()
    {
        $form = $this->factory->create(RegistrationFormType::class);

        $this->assertTrue($form->has('email'));
        $this->assertTrue($form->has('password'));
        $this->assertTrue($form->has('nickname'));
    }
    /**
     * testGetBlockPrefix.
     * @return void
     */
    public function testGetBlockPrefix()
    {
        $type = new RegistrationFormType();
        $this->assertEquals('user', $type->getBlockPrefix());
    }
    /**
     * getExtensions.
     * @return ValidatorExtension[]
     */
    protected function getExtensions(): array
    {
        $validator = Validation::createValidator();

        return [
            new ValidatorExtension($validator),
        ];
    }
}
