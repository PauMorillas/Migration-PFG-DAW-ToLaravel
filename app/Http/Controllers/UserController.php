<?php 

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\UserService;

class UserController extends Controller
{

    public function __construct(private readonly UserService $userService)
    {
        
    }
    public function findById(int $userId): User
    {
        
    }
}