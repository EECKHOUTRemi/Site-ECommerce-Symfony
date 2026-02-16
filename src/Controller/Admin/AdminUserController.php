<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user")
 * @IsGranted("ROLE_ADMIN")
 */
class AdminUserController extends AbstractController
{
    /**
     * @Route("/", name="app_admin_user_index", methods={"GET"})
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('admin/admin_user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_admin_user_new", methods={"GET", "POST"})
     */
    public function new(Request $request, UserRepository $userRepository): Response
    {
        $user = new User();
        $uniqueRoles = $userRepository->getUniqueRoles();
        $form = $this->createForm(UserType::class, $user, [
            'roles' => $uniqueRoles
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get roles data from form (not entity to avoid getRoles() error)
            $rolesData = $form->get('roles')->getData();
            $finalRoles = [];

            // Add selected checkbox roles (choiceType)
            if (isset($rolesData['choiceType']) && !empty($rolesData['choiceType'])) {
                foreach ($rolesData['choiceType'] as $role) {
                    $finalRoles[] = $role;
                }
            }

            // Add custom text input role (choiceInput)
            if (isset($rolesData['choiceInput']) && !empty($rolesData['choiceInput'])) {
                $finalRoles[] = $rolesData['choiceInput'];
            }

            // Set the flattened roles array
            if (!empty($finalRoles)) {
                $user->setRoles($finalRoles);
            }

            $userRepository->add($user, true);

            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_user/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('admin/admin_user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_admin_user_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $uniqueRoles = $userRepository->getUniqueRoles();

        // Get current roles before creating the form
        $currentRoles = $user->getRoles();
        $availableRoles = array_values($uniqueRoles);
        $selectedRoles = [];
        $customRole = null;

        foreach ($currentRoles as $role) {

            // Check if role is in available choices
            if (in_array($role, $availableRoles)) {
                $selectedRoles[] = $role;
            } else {
                // Custom role (not in checkboxes)
                $customRole = $role;
                break; // Only handle one custom role for now
            }
        }

        // Create form first
        $form = $this->createForm(UserType::class, $user, [
            'roles' => $uniqueRoles
        ]);
        $form->remove('password');

        // Set the roles form field data separately (not on entity)
        $form->get('roles')->setData([
            'choiceType' => $selectedRoles,
            'choiceInput' => $customRole
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Get roles data from form (not entity to avoid getRoles() error)
            $rolesData = $form->get('roles')->getData();
            $finalRoles = [];

            // Add selected checkbox roles (choiceType)
            if (isset($rolesData['choiceType']) && !empty($rolesData['choiceType'])) {
                foreach ($rolesData['choiceType'] as $role) {
                    $finalRoles[] = $role;
                }
            }

            // Add custom text input role (choiceInput)
            if (isset($rolesData['choiceInput']) && !empty($rolesData['choiceInput'])) {
                $finalRoles[] = $rolesData['choiceInput'];
            }

            // Set the flattened roles array
            if (!empty($finalRoles)) {
                $user->setRoles($finalRoles);
            }

            $userRepository->add($user, true);


            return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('admin/admin_user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_admin_user_delete", methods={"POST"})
     */
    public function delete(Request $request, User $user, UserRepository $userRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $userRepository->remove($user, true);
        }

        return $this->redirectToRoute('app_admin_user_index', [], Response::HTTP_SEE_OTHER);
    }
}
