<?php
/**
 * Answer controller.
 */

namespace App\Controller;

use App\Entity\Answer;
use App\Form\Type\AnswerType;
use App\Service\AnswerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class AnswerController.
 */
#[Route('/answer')]
class AnswerController extends AbstractController
{
    private AnswerService $answerService;

    private TranslatorInterface $translator;

    /**
     * Constructor.
     * @param AnswerService       $answerService
     * @param TranslatorInterface $translator
     */
    public function __construct(AnswerService $answerService, TranslatorInterface $translator)
    {
        $this->answerService = $answerService;
        $this->translator = $translator;
    }

    /**
     * Edit action.
     * @param Request $request
     * @param Answer  $answer
     *
     * @return Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'answer_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    public function edit(Request $request, Answer $answer): Response
    {
        $form = $this->createForm(AnswerType::class, $answer, [
            'method' => 'PUT',
            'action' => $this->generateUrl('answer_edit', ['id' => $answer->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->answerService->save($answer);

            $this->addFlash(
                'success',
                $this->translator->trans('message.edited_successfully')
            );

            return $this->redirectToRoute('question_show', ['id' => $answer->getQuestion()->getId()]);
        }

        return $this->render('answer/edit.html.twig', [
            'form' => $form->createView(),
            'answer' => $answer,
        ]);
    }

    /**
     * Delete action.
     * @param Request $request
     * @param Answer  $answer
     *
     * @return Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/delete', name: 'answer_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    public function delete(Request $request, Answer $answer): Response
    {
        $id = $answer->getQuestion()->getId();
        $form = $this->createForm(FormType::class, $answer, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('answer_delete', ['id' => $answer->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->answerService->delete($answer);

            $this->addFlash(
                'success',
                $this->translator->trans('message.deleted_successfully')
            );

            return $this->redirectToRoute('question_show', ['id' => $id]);
        }

        return $this->render('answer/delete.html.twig', [
            'form' => $form->createView(),
            'answer' => $answer,
        ]);
    }

    /**
     * markAsBest action.
     * @param Answer        $answer
     * @param AnswerService $answerService
     *
     * @return Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/mark-best', name: 'answer_mark_best', methods: ['PUT'])]
    public function markAsBest(Answer $answer, AnswerService $answerService): Response
    {
        $answerService->markAsBest($answer);
        $this->addFlash('success', $this->translator->trans('message.best_answer_marked'));

        return $this->redirectToRoute('question_show', ['id' => $answer->getQuestion()->getId()]);
    }

    /**
     * unmarkBest action.
     * @param Answer        $answer
     * @param AnswerService $answerService
     *
     * @return Response
     */
    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/unmark-best', name: 'answer_unmark_best', methods: ['PUT'])]
    public function unmarkBest(Answer $answer, AnswerService $answerService): Response
    {
        $answerService->unmarkAsBest($answer);
        $this->addFlash('warning', $this->translator->trans('message.best_answer_unmarked'));

        return $this->redirectToRoute('question_show', ['id' => $answer->getQuestion()->getId()]);
    }
}
