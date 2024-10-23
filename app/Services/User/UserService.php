<?php

namespace App\Services\User;

use App\Models\EmailVerification;
use App\Models\User;
use App\Enums\UserRoleEnums;
use App\Notifications\VerifyEmailNotification;
use App\Repositories\User\UserRepositoryContract;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class UserService implements UserServiceContract
{
    protected $userRepository;

    public function __construct(UserRepositoryContract $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getAllUsers(): array
    {
        return $this->userRepository->all()->toArray();
    }

    public function create(array $data): array
    {
        $data['password'] = bcrypt($data['password']);

        try {
            $user = $this->userRepository->create($data);

            $token = EmailVerification::generateToken($user->id);
            $user->notify(new VerifyEmailNotification($token));

            return $user->toArray();
        } catch (\Exception $e) {
            Log::error('Error creating user or sending verification email: ' . $e->getMessage());
            throw new \Exception(__('exception.create_user'));
        }
    }

    public function getUser(): User
    {
        return Auth::guard('api')->user();
    }

    public function getUserByRole(int $pageSize): LengthAwarePaginator
    {
        $user = Auth::guard('api')->user();

        if (!$user) {
            return $this->userRepository->getUsersByRoles([]);
        }

        $findUserByRole = match ($user->role) {
            'ADMINISTRATOR' => UserRoleEnums::getAllRoles(),
            'TEACHER'       => UserRoleEnums::rolesPermissionTeacher(),
            'SUPPORT'       => UserRoleEnums::rolesPermissionSupport(),
            default         => [],
        };

        return $this->userRepository->getUsersByRoles($findUserByRole, $pageSize);
    }
}