<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\QueryException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    /**
     * @OA\Get(
     *     path="/reservas360-backend/public/api/logout",
     *     tags={"Authentication"},
     *     summary="Logout",
     *     description="Log out user.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Successful logout",
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal Server Error",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="An error occurred while trying to log out. Please try again later.")
     *         )
     *     )
     * )
     */
    public function logout()
    {
        try {
            if (Auth::check()) {
                // $accessToken = auth()->user()->currentAccessToken();

                // if ($accessToken) {
                //     $accessToken->delete();
                // }
                $accessToken = auth()->user()->currentAccessToken();

                if ($accessToken) {
                    // Establecer una nueva fecha de expiración (por ejemplo, 30 días a partir de ahora)
                    $accessToken->expires_at = Carbon::now()->addDays(30);
                    $accessToken->save();
                }
            } else {
                return response()->json([
                    "msg" => "Unable to logout. User is not authenticated.",
                ], JsonResponse::HTTP_UNAUTHORIZED);
            }
        } catch (QueryException $e) {
            // Captura la excepción de la base de datos (por ejemplo, si hay un problema al eliminar el token)
            return response()->json([
                "msg" => "An error occurred while logging out.",
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
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
     *          ),

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

        $user = User::where("email", $request->email)->first();

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
     * @OA\Get(
     *     path="/reservas360-backend/public/api/authenticate",
     *     summary="Get Profile user",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     description="Get user",
     *     @OA\Response(
     *         response=200,
     *         description="User authenticated successfully",
     *         @OA\JsonContent(
     *             @OA\Property(
     *             property="user",
     *             type="object",
     *             description="User",
     *             ref="#/components/schemas/User"
     *              ),

     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="error", type="string", example="The given data was invalid.")
     *         )
     *     ),
     *        @OA\Response(
     *         response=401,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="Unauthenticated.")
     *         )
     *     ),
     * )
     */

    public function authenticate()
    {
        try {

            $userAuth = auth()->user();

            return response()->json([
                'user' => User::with('person')->find($userAuth->id),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                "message" => "Error interno del servidor: " . $e,
            ], 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/reservas360-backend/public/api/user",
     *     summary="Store a new user",
     *     tags={"User"},
     *     description="Create a new user",
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         description="User data",
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="guevaracajusolmiguel@gmail.com", description="email"),
     *             @OA\Property(property="password", type="string", example="password123", description="Password"),
     *             @OA\Property(property="person_id", type="integer", example=1, description="Person ID"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="User created successfully",
     *         @OA\JsonContent(
     *            ref="#/components/schemas/User"
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Validation error",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Some fields are required.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Unauthenticated.",
     *         @OA\JsonContent(
     *             @OA\Property(property="msg", type="string", example="Unauthenticated.")
     *         )
     *     ),
     * )
     */

    public function store(Request $request)
    {

        $validator = validator()->make($request->all(), [
            'email' => [
                'required',
                'email',
                'regex:/^[\w\-\.]+@([\w\-]+\.)+[a-zA-Z]{2,7}$/',
                'string',
                Rule::unique('users')->whereNull('deleted_at'),
            ],
            'password' => 'required',
            'person_id' => [
                'required',
                'numeric',
                'exists:people,id',
                function ($attribute, $value, $fail) {
                    if (User::where('person_id', $value)->exists()) {
                        $fail('La persona ya tiene un usuario asociado.');
                    }
                },
            ],

        ], [
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El formato del correo electrónico no es válido.',
            'email.regex' => 'El correo electrónico debe cumplir con el formato correcto.',
            'email.string' => 'El correo electrónico debe ser una cadena de texto.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'person_id.required' => 'Campo Persona es obligatorio.',
            'person_id.exists' => 'Id de la Persona no Existe',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $hashedPassword = Hash::make($request->input('password'));

        $data = [

            'email' => $request->input('email') ?? null,
            'password' => $hashedPassword ?? null,
            'person_id' => $request->input('person_id') ?? null,
        ];

        $object = User::create($data);
        return User::with(['person'])->find($object->id);

    }
}
