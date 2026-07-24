<?php

declare(strict_types=1);

namespace PortalCMS\Features\Users\Controller;

use PortalCMS\Core\Controller\AbstractController;
use PortalCMS\Core\Http\RequestInputMapper;
use PortalCMS\Core\View\TemplateRenderer;
use PortalCMS\Features\Users\Authorization\Authorization;
use PortalCMS\Features\Users\Entity\Permission;
use PortalCMS\Features\Users\Entity\Role;
use PortalCMS\Features\Users\Entity\User;
use PortalCMS\Features\Users\Input\CreateUserInput;
use PortalCMS\Features\Users\Password;
use PortalCMS\Features\Users\Repository\PermissionRepository;
use PortalCMS\Features\Users\Repository\RoleRepository;
use PortalCMS\Features\Users\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final class UserManagementController extends AbstractController
{
    public function __construct(
        TemplateRenderer $templates,
        RequestStack $requestStack,
        UrlGeneratorInterface $urlGenerator,
        private readonly UserRepository $users,
        private readonly RoleRepository $roles,
        private readonly PermissionRepository $permissions,
        private readonly RequestInputMapper $inputMapper,
        private readonly Authorization $authorization,
    ) {
        parent::__construct($templates, $requestStack, $urlGenerator);
    }

    #[Route('/UserManagement/Users', name: 'users.admin_users', methods: [ 'GET' ])]
    #[Route('/UserManagement/Users/', name: 'users.admin_users_slash', methods: [ 'GET' ])]
    public function users(): Response
    {
        return $this->canManageUsers()
            ? $this->render('Users::Administration/Users/UserListPage', [
                'users' => $this->users->findAllOrdered(),
            ])
            : $this->forbiddenResponse();
    }

    #[Route('/UserManagement/Profile', name: 'users.admin_profile', methods: [ 'GET' ])]
    #[Route('/UserManagement/Profile/', name: 'users.admin_profile_slash', methods: [ 'GET' ])]
    public function profile(Request $request): Response
    {
        if (!$this->canManageUsers()) {
            return $this->forbiddenResponse();
        }
        $user = $this->users->find($request->query->getInt('id'));
        if (!$user instanceof User) {
            return $this->notFoundResponse();
        }
        $assignedRoles = $this->users->findRoles($user->user_id);
        $assignedIds = array_map(static fn (Role $role): int => $role->role_id, $assignedRoles);
        $availableRoles = array_values(array_filter(
            $this->roles->findAllOrdered(),
            static fn (Role $role): bool => !in_array($role->role_id, $assignedIds, true),
        ));

        return $this->render('Users::Administration/Users/UserDetailsPage', [
            'user' => $user,
            'assignedRoles' => $assignedRoles,
            'availableRoles' => $availableRoles,
            'userPermissions' => $this->permissions->findByUserId($user->user_id),
        ]);
    }

    #[Route('/UserManagement/Roles', name: 'users.admin_roles', methods: [ 'GET' ])]
    #[Route('/UserManagement/Roles/', name: 'users.admin_roles_slash', methods: [ 'GET' ])]
    public function roles(): Response
    {
        return $this->canManageRoles()
            ? $this->render('Users::Administration/Roles/RoleListPage', [
                'roles' => $this->roles->findAllOrdered(),
            ])
            : $this->forbiddenResponse();
    }

    #[Route('/UserManagement/Role', name: 'users.admin_role', methods: [ 'GET' ])]
    #[Route('/UserManagement/Role/', name: 'users.admin_role_slash', methods: [ 'GET' ])]
    public function role(Request $request): Response
    {
        if (!$this->canManageRoles()) {
            return $this->forbiddenResponse();
        }
        $role = $this->roles->find($request->query->getInt('id'));
        if (!$role instanceof Role) {
            return $this->notFoundResponse();
        }

        return $this->render('Users::Administration/Roles/RoleDetailsPage', [
            'role' => $role,
            'activePermissions' => $this->roles->findPermissions($role),
            'selectablePermissions' => $this->roles->findSelectablePermissions($role),
        ]);
    }

    #[Route('/UserManagement/AddUser', name: 'users.admin_add', methods: [ 'GET' ])]
    public function addUser(): Response
    {
        return $this->canManageUsers()
            ? $this->render('Users::Administration/Users/CreateUserPage')
            : $this->forbiddenResponse();
    }

    #[Route('/UserManagement/AddUser', name: 'users.admin_create', methods: [ 'POST' ])]
    public function createUser(Request $request): Response
    {
        if (!$this->canManageUsers()) {
            return $this->forbiddenResponse();
        }
        /** @var CreateUserInput $input */
        $input = $this->inputMapper->map($request, CreateUserInput::class);
        if (
            $this->users->usernameExists($input->user_name)
            || $this->users->emailExists($input->user_email)
        ) {
            $this->addFlash('danger', 'Gebruikersnaam of e-mailadres bestaat al.');
            return $this->redirectToRoute('users.admin_add');
        }

        $user = User::create(
            $input->user_name,
            $input->user_email,
            Password::hash($input->user_password),
        );
        $this->users->save($user);
        $this->users->flush();
        $this->addFlash('success', 'Gebruiker toegevoegd.');

        return $this->redirectToRoute('users.admin_profile', [ 'id' => $user->user_id ]);
    }

    #[Route('/UserManagement/Users/Delete', name: 'users.admin_delete', methods: [ 'POST' ])]
    public function deleteUser(Request $request): Response
    {
        if (!$this->canManageUsers()) {
            return $this->forbiddenResponse();
        }
        $user = $this->users->find($request->request->getInt('user_id'));
        if (!$user instanceof User) {
            return $this->notFoundResponse();
        }
        if ($user->user_id === (int) $this->session()->get('user_id')) {
            $this->addFlash('danger', 'Je kunt je eigen account niet verwijderen.');
            return $this->redirectToRoute('users.admin_profile', [ 'id' => $user->user_id ]);
        }

        $this->users->remove($user);
        $this->users->flush();
        $this->addFlash('success', 'Gebruiker verwijderd.');

        return $this->redirectToRoute('users.admin_users');
    }

    #[Route('/UserManagement/Roles/Create', name: 'users.admin_role_create', methods: [ 'POST' ])]
    public function createRole(Request $request): Response
    {
        if (!$this->canManageRoles()) {
            return $this->forbiddenResponse();
        }
        $name = trim($request->request->getString('role_name'));
        if ($name === '' || $this->roles->findOneBy([ 'role_name' => $name ]) instanceof Role) {
            $this->addFlash('danger', 'Rolnaam is ongeldig of bestaat al.');
        } else {
            $this->roles->save(Role::create($name));
            $this->roles->flush();
            $this->addFlash('success', 'Nieuwe rol aangemaakt.');
        }

        return $this->redirectToRoute('users.admin_roles');
    }

    #[Route('/UserManagement/Roles/Delete', name: 'users.admin_role_delete', methods: [ 'POST' ])]
    public function deleteRole(Request $request): Response
    {
        if (!$this->canManageRoles()) {
            return $this->forbiddenResponse();
        }
        $role = $this->roles->find($request->request->getInt('role_id'));
        if (!$role instanceof Role) {
            return $this->notFoundResponse();
        }
        if ($this->roles->isAssignedToUsers($role)) {
            $this->addFlash('danger', 'Deze rol is nog aan gebruikers toegewezen.');
        } else {
            $this->roles->remove($role);
            $this->roles->flush();
            $this->addFlash('success', 'Rol verwijderd.');
        }

        return $this->redirectToRoute('users.admin_roles');
    }

    #[Route('/UserManagement/Profile/Role/Assign', name: 'users.admin_role_assign', methods: [ 'POST' ])]
    public function assignRole(Request $request): Response
    {
        return $this->changeUserRole($request, true);
    }

    #[Route('/UserManagement/Profile/Role/Unassign', name: 'users.admin_role_unassign', methods: [ 'POST' ])]
    public function unassignRole(Request $request): Response
    {
        return $this->changeUserRole($request, false);
    }

    #[Route('/UserManagement/Role/Permission/Assign', name: 'users.admin_permission_assign', methods: [ 'POST' ])]
    public function assignPermission(Request $request): Response
    {
        return $this->changePermission($request, true);
    }

    #[Route('/UserManagement/Role/Permission/Unassign', name: 'users.admin_permission_unassign', methods: [ 'POST' ])]
    public function unassignPermission(Request $request): Response
    {
        return $this->changePermission($request, false);
    }

    private function changeUserRole(Request $request, bool $assign): Response
    {
        if (!$this->canManageUsers()) {
            return $this->forbiddenResponse();
        }
        $user = $this->users->find($request->request->getInt('user_id'));
        $role = $this->roles->find($request->request->getInt('role_id'));
        if (!$user instanceof User || !$role instanceof Role) {
            return $this->notFoundResponse();
        }

        $assigned = $user->hasRole($role);
        if ($assign && !$assigned) {
            $user->addRole($role);
            $this->users->flush();
            $this->addFlash('success', 'Rol toegewezen.');
        } elseif (!$assign && $assigned) {
            $user->removeRole($role);
            $this->users->flush();
            $this->addFlash('success', 'Rol voor gebruiker verwijderd.');
        } else {
            $this->addFlash('warning', $assign ? 'Rol was reeds toegewezen.' : 'Rol was niet toegewezen.');
        }

        return $this->redirectToRoute('users.admin_profile', [ 'id' => $user->user_id ]);
    }

    private function changePermission(Request $request, bool $assign): Response
    {
        if (!$this->canManageRoles()) {
            return $this->forbiddenResponse();
        }
        $role = $this->roles->find($request->request->getInt('role_id'));
        $permission = $this->permissions->find($request->request->getInt('perm_id'));
        if (!$role instanceof Role || !$permission instanceof Permission) {
            return $this->notFoundResponse();
        }

        $assigned = $role->hasPermission($permission);
        if ($assign && !$assigned) {
            $role->addPermission($permission);
            $this->roles->flush();
            $this->addFlash('success', 'Permissie toegewezen.');
        } elseif (!$assign && $assigned) {
            $role->removePermission($permission);
            $this->roles->flush();
            $this->addFlash('success', 'Permissie verwijderd.');
        } else {
            $this->addFlash('warning', $assign ? 'Permissie was reeds toegewezen.' : 'Permissie was niet toegewezen.');
        }

        return $this->redirectToRoute('users.admin_role', [ 'id' => $role->role_id ]);
    }

    private function canManageUsers(): bool
    {
        return $this->authorization->hasPermission('user-management');
    }

    private function canManageRoles(): bool
    {
        return $this->authorization->hasPermission('role-management');
    }
}
