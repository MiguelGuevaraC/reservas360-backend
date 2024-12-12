<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/login",
     *     summary="Obtiene el usuario autenticado",
     *     tags={"Usuario"},
     *     @OA\Response(
     *         response=200,
     *         description="Usuario encontrado",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     )
     * )
     */

    public function index()
    {
        //
    }

    /**
     * @OA\Post(
     *     path="/reservas360-backend/public/api/login",
     *     summary="Login user",
     *     tags={"Authentication"},
     *     description="Authenticate user and generate access token",
     * security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User credentials",
     *         @OA\JsonContent(
     *             required={"username", "password", "branchOffice_id"},
     *             @OA\Property(property="email", type="string", example="miguel@gmail.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password"),
     
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User authenticated successfully",
     *         @OA\JsonContent(
     *              @OA\Property(property="token", type="string", description="token del usuario"),
     *             @OA\Property(
     *             property="user",
     *             type="object",
     *             description="User",
     *             ref="#/components/schemas/User"
     *              ),
     *          @OA\Property(
     *          property="menu",
     *          type="array",
     *              @OA\Items(
     *              type="object",
     *               description="Menú"
     *              )
     *),
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="User not found or password incorrect",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", description="Error message")
     *         )
     *     ),
     *       @OA\Response(
     *         response=401,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="Unauthenticated.")
     *         )
     *     ),
     * )
     */

    public function login(Request $request)
    {
        $request->validate([
            "email" => "required|email|regex:/^[\w\-\.]+@([\w\-]+\.)+[a-zA-Z]{2,7}$/", // Validación de correo electrónico con expresión regular
            "password" => "required|regex:/^[a-zA-Z0-9!@#$%^&*()_+=-]*$/", // Validación de contraseña con caracteres seguros
        ], [
            "email.required" => "El correo electrónico es obligatorio.",
            "email.email" => "El correo electrónico debe ser válido.",
            "email.regex" => "El correo electrónico no es válido.",
            "password.required" => "La contraseña es obligatoria.",
            "password.regex" => "La contraseña contiene caracteres no permitidos.",
        ]);
        

        $user = User::where("email", $request->email)
        ->first();

        if (!$user) {

            return response()->json([
                "error" => "Usuario No Encontrado",
                // "error" => "SISTEMA EN MANTENIMIENTO",
            ], 422);
        }

        if (Hash::check($request->password, $user->password)) {
            // Autenticar al usuario

            Auth::login($user);

            $token = $user->createToken('auth_token', ['expires' => now()->addHour()])->plainTextToken;

            $user->makeHidden('password');

            // -------------------------------------------------
            return response()->json([
                'token' => $token,
                'user' => User::find($user->id),
            ]);
        } else {

            return response()->json([
                "error" => "Password Not Correct",
            ], 422);
        }
    }


    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
