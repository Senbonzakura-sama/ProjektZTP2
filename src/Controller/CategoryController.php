<?php
/**
 * Category controller.
 */

namespace App\Controller;

use AllowDynamicProperties;
use App\Entity\Category;
use App\Form\Type\CategoryType;
use App\Service\CategoryServiceInterface;
use App\Service\QuestionServiceInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class CategoryController.
 */
#[AllowDynamicProperties] #[Route('/category')]
class CategoryController extends AbstractController
{
    /**
     * Category Service.
     */
    private CategoryServiceInterface $categoryService;

    /**
     * Translator.
     */
    private TranslatorInterface $translator;

    /**
     * Constructor.
     * @param CategoryServiceInterface $categoryService
     * @param QuestionServiceInterface $questionService
     * @param TranslatorInterface      $translator
     */
    public function __construct(CategoryServiceInterface $categoryService, QuestionServiceInterface $questionService, TranslatorInterface $translator)
    {
        $this->categoryService = $categoryService;
        $this->questionService = $questionService;
        $this->translator = $translator;
    }

    /**
     * Index action.
     * @param Request $request
     *
     * @return Response
     */
    #[Route(name: 'category_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $pagination = $this->categoryService->getPaginatedList(
            $request->query->getInt('page', 1)
        );

        return $this->render('category/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     * @param Category $category
     * @param Request  $request
     *
     * @return Response
     */
    #[Route(
        '/{id}',
        name: 'category_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Category $category, Request $request): Response
    {
        $pagination = $this->questionService->getPaginatedListByCategory(
            $request->query->getInt('page', 1),
            $category,
        );

        return $this->render('category/show.html.twig', [
            'pagination' => $pagination,
            'category' => $category,
        ]);
    }

    /**
     * Create action.
     * @param Request $request
     *
     * @return Response
     */
    #[Route(
        '/create',
        name: 'category_create',
        methods: 'GET|POST',
    )]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $category = new Category();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category->setSlug($this->generateSlugFromTitle($category->getTitle()));
            $this->categoryService->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/create.html.twig',
            ['form' => $form->createView()]
        );
    }

    /**
     * Edit action.
     * @param Request  $request
     * @param Category $category
     *
     * @return Response
     */
    #[Route('/{id}/edit', name: 'category_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Category $category): Response
    {
        $form = $this->createForm(
            CategoryType::class,
            $category,
            [
                'method' => 'PUT',
                'action' => $this->generateUrl('category_edit', ['id' => $category->getId()]),
            ]
        );
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->save($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.created_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/edit.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }

    /**
     * Delete action.
     * @param Request  $request
     * @param Category $category
     *
     * @return Response
     */
    #[Route('/{id}/delete', name: 'category_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Category $category): Response
    {
        if (!$this->categoryService->canBeDeleted($category)) {
            $this->addFlash(
                'warning',
                $this->translator->trans('message.category_contains_tasks')
            );

            return $this->redirectToRoute('category_index');
        }

        $form = $this->createForm(FormType::class, $category, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('category_delete', ['id' => $category->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->categoryService->delete($category);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('category_index');
        }

        return $this->render(
            'category/delete.html.twig',
            [
                'form' => $form->createView(),
                'category' => $category,
            ]
        );
    }

    /**
     * GenerateSLugFromTitle action.
     * @param string $title
     *
     * @return string
     */
    private function generateSlugFromTitle(string $title): string
    {
        return strtolower(str_replace(' ', '-', $title));
    }
}
