<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Validator;

class CreateAdmin extends Command
{
    protected $signature = 'erp:create-admin';

    protected $description = 'Buat akun administrator ERP';

    public function handle(): int
    {
        $data = ['name' => $this->ask('Nama'), 'email' => $this->ask('Email'), 'password' => $this->secret('Kata sandi (minimal 8 karakter)')];
        $validator = Validator::make($data, ['name' => 'required|string|max:255', 'email' => 'required|email|max:255|unique:users,email', 'password' => 'required|string|min:8']);
        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return self::FAILURE;
        }
        User::create([...$data, 'role' => 'admin']);
        $this->info('Administrator berhasil dibuat.');

        return self::SUCCESS;
    }
}
