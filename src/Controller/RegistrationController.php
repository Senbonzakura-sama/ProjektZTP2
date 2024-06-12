<?php
/**
 * Registration controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\Type\RegistrationFormType;
use App\Service\RegistrationService;
use App\Service\UserService;
use Doctrine\ORM\NonUniqueResultException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class RegistrationController.
 */
class RegistrationController extends AbstractController
{
    private UserService $userService;

    private RegistrationService $registrationService;

    private TranslatorInterface $translator;

    /**
     * Constructor.
     * @param RegistrationService $registrationService
     * @param UserService         $userService
     * @param TranslatorInterface $translator
     */
    public function __construct(RegistrationService $registrationService, UserService $userService, TranslatorInterface $translator)
    {
        $this->registrationService = $registrationService;
        $this->userService = $userService;
        $this->translator = $translator;
    }

    /**
     * Create action.
     * @param Request $request
     *
     * @return Response
     *
     * @throws NonUniqueResultException
     */
    #[Route('/register', name: 'app_register', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $user->setNickname($data->getNickname());

            if (null !== $this->userService->findOneByEmail($data->getEmail())) {
                $this->addFlash(
                    'danger',
                    $this->translator->trans('message.email_already_exists')
                );

                return $this->redirectToRoute('app_register');
            }

            $this->registrationService->register([
                'email' => $data->getEmail(),
                'nickname' => $data->getNickname(),
                'password' => $data->getPassword(),
            ]);

            $this->addFlash(
                'success',
                $this->translator->trans('message.registered_successfully')
            );

            return $this->redirectToRoute('question_index');
        }

        return $this->render(
            'registration/index.html.twig',
            [
                'form' => $form->createView(),
            ]
        );
    }
}
