<?php
/**
 * QuestionTypeTest.
 */

namespace App\Tests\Form\Type;

use App\Entity\Question;
use App\Form\DataTransformer\TagsDataTransformer;
use App\Form\Type\QuestionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Test\TypeTestCase;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * QuestionTypeTest.
 */
class QuestionTypeTest extends TypeTestCase
{
    /**
     * testBuildForm.
     *
     * @return void
     */
    public function testBuildForm()
    {
        $transformer = $this->createMock(TagsDataTransformer::class);
        $builder = $this->createMock(FormBuilderInterface::class);

        $builder->expects($this->exactly(3))
            ->method('add')
            ->withConsecutive(
                ['title', TextType::class, $this->isType('array')],
                ['category', EntityType::class, $this->isType('array')],
                ['tags', TextType::class, $this->isType('array')]
            )
            ->willReturnSelf();

        $builder->expects($this->once())
            ->method('get')
            ->with('tags')
            ->willReturn($builder);

        $builder->expects($this->once())
            ->method('addModelTransformer')
            ->with($transformer);

        $type = new QuestionType($transformer);
        $type->buildForm($builder, []);

        $this->addToAssertionCount(1); // Suppress PHPUnit warning about no assertions
    }

    /**
     * testConfigureOptions.
     *
     * @return void
     */
    public function testConfigureOptions()
    {
        $resolver = $this->createMock(OptionsResolver::class);
        $resolver->expects($this->once())
            ->method('setDefaults')
            ->with(['data_class' => Question::class]);

        $type = new QuestionType($this->createMock(TagsDataTransformer::class));
        $type->configureOptions($resolver);

        $this->addToAssertionCount(1); // Suppress PHPUnit warning about no assertions
    }

    /**
     * testGetBlockPrefix.
     *
     * @return void
     */
    public function testGetBlockPrefix()
    {
        $type = new QuestionType($this->createMock(TagsDataTransformer::class));
        $prefix = $type->getBlockPrefix();

        $this->assertSame('question', $prefix);
    }
}
