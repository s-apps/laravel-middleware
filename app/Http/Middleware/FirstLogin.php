<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Category; 

class FirstLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (auth()->check() && auth()->user()->categories_created === 0) {
            $this->criarCategoriasParaUsuario(auth()->user());
        }
        return $next($request);
    }

    private function criarCategoriasParaUsuario($user)
    {
        $categories = [
            [
                'user_id' => $user->id,
                'name' => 'Alimentação',
                'created_at' => now()
            ],
            [
                'user_id' => $user->id,
                'name' => 'Aluguel',
                'created_at' => now()
            ],
            [
                'user_id' => $user->id,
                'name' => 'Veículo',
                'created_at' => now()
            ],
            [
                'user_id' => $user->id,
                'name' => 'Salário',
                'created_at' => now()
            ]
        ];
        
        foreach ($categories as $category) {
            $newCategory = new Category();
            $newCategory->name = $category['name'];
            $newCategory->user_id = $category['user_id'];
            $newCategory->created_at = $category['created_at'];
            $newCategory->save();
        }
    
        $user->categories_created = 1;
        $user->save();    
    } 
}  
