<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Jobs\SendResetPasswordLinkJob;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserService
{
    public function __construct(
        protected  UserRepositoryInterface $userRepository,
        protected Carbon $carbon
    ) {}

    public function createNewUser(array $data): User
    {
        return $this->userRepository->createNewUser($data);
    }

    public function sendLinkResetPassword(Request $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'message' =>  trans('passwords.user')
            ], 404);
        }

        do {
            $code = mt_rand(100000, 999999);  // Gerar um código aleatório de 6 dígitos
        } while (User::where('code', $code)->exists());

        $user->code_created_at = now()->addHours(24)->format('Y-m-d H:i:s');
        $user->code = mt_rand(100000, 999999);
        $user->save();   

        SendResetPasswordLinkJob::dispatch($user);
        return response()->json([], 204);
    }

    public function passwordUpdate(Request $request)
    {
        // Verificar se o usuário existe e se o código corresponde
        $user = User::where('email', $request->email)
            ->where('code', $request->code)
            ->first();

        if (!$user) {
            return back()->withErrors(['code' => 'Código inválido.']);
        }

        $codeCreatedAt = $this->carbon->parse($user->code_created_at);

        if ($codeCreatedAt->lte(now()->format('Y-m-d H:i:s'))) {
            return back()->withErrors(['code' => 'O código expirou. Solicite um novo.']);
        }

        if ($user->code_created_at) {
            $codeCreatedAt = $this->carbon->parse($user->code_created_at);
            if ($codeCreatedAt->diffInHours(now()) > 24) {
                return back()->withErrors(['code' => 'O código expirou. Solicite um novo.']);
            }
        }

        // Se o código for válido e não expirou, prosseguir com a redefinição da senha
        $user->password = Hash::make($request->password);
        $user->code = null;  // Limpar o código após a redefinição
        $user->code_created_at = null;
        $user->save();

        return view('auth.password_reset_success');
    }
}
