<?php 

namespace App\Services;

class UserService {
    public function __construct(private readonly UserRepository $userRepository) {
        
    }
}