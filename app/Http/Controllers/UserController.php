<?php
namespace App\Http\Controllers;

use App\Http\Requests\AuthenticateRequest\LoginRequest;
use App\Http\Requests\UserRequest\SendTokenAppRequest;
use App\Http\Requests\UserRequest\StoreUserAppRequest;
use App\Http\Requests\UserRequest\StoreUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Person;
use App\Models\User;
use App\Services\AuthService;
use App\Services\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{

    protected $authService;
    protected $userService;

    public function __construct(AuthService $authService, UserService $userService)
    {
        $this->authService = $authService;
        $this->userService = $userService;
    }

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
    public function logout(): JsonResponse
    {
        return $this->authService->logout();
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
     *             required={"username", "password", "branch_id"},
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
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Message Response"
     *             )
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

    public function login(LoginRequest $request): JsonResponse
    {

        try {

            $data = $request->only(['email', 'password']);
            // Llama al servicio de autenticación
            $authData = $this->authService->login($request->email, $request->password);

            // Verifica si el usuario es null
            if (! $authData['user']) {
                return response()->json([
                    'error' => $authData['message'],
                ], 422);
            }

            // Retorna la respuesta con el token y el recurso del usuario
            return response()->json([
                'token'   => $authData['token'],
                'user'    => new UserResource($authData['user']),
                'message' => $authData['message'],
            ]);
        } catch (\Exception $e) {
            // Captura cualquier excepción y retorna el mensaje de error
            return response()->json([
                'error' => $e->getMessage(),
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
     *                 property="token",
     *                 type="string",
     *                 description="Bearer token"
     *             ),
     *             @OA\Property(
     *             property="user",
     *             type="object",
     *             description="User",
     *             ref="#/components/schemas/User"
     *              ),
     *             @OA\Property(
     *                 property="message",
     *                 type="string",
     *                 description="Message Response"
     *             )
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

    public function authenticate(Request $request)
    {
        // Llama al servicio de autenticación
        $result = $this->authService->authenticate();

        // Si la autenticación falla, devuelve el mensaje de error
        if (! $result['status']) {
            return response()->json(['error' => $result['message']], 422);
        }
        $token = $request->bearerToken();

        // Si la autenticación es exitosa, devuelve el token, el usuario y la persona
        return response()->json([
            'token'   => $token,
            'user'    => new UserResource($result['user']),
            'message' => 'Autenticado',
        ]);
    }

    // public function authenticate(Request $request)
    // {
    //     try {

    //         $userAuth = auth()->user();

    //         $token = $userAuth->currentAccessToken()->plainTextToken;
    //         $token = $request->bearerToken();
    //         return response()->json([
    //             'user' => User::with('person')->find($userAuth->id), // Datos del usuario
    //             'token' => $token, // Token actual
    //         ]);
    //     } catch (\Exception $e) {
    //         return response()->json([
    //             "message" => "Error interno del servidor: " . $e,
    //         ], 500);
    //     }
    // }

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

    public function store(StoreUserRequest $request)
    {

        $validator = validator()->make($request->all(), [
            'email'          => [
                'required',
                'email',
                'regex:/^[\w\-\.]+@([\w\-]+\.)+[a-zA-Z]{2,7}$/',
                'string',
                Rule::unique('users')->whereNull('deleted_at'),
            ],
            'password'       => 'required',

            'names'          => 'nullable|string|max:255|regex:/^[\pL\s]+$/u',
            'fathersurname'  => 'nullable|string|max:255|regex:/^[\pL\s]+$/u',
            'mothersurname'  => 'nullable|string|max:255|regex:/^[\pL\s]+$/u',
            'address'        => 'nullable|string|max:255',
            'phone'          => 'nullable|string|max:15|regex:/^\+?[0-9\s\-]+$/',

            'documentNumber' => [
                'required',
                'exists:people,documentNumber,deleted_at,NULL',
                function ($attribute, $value, $fail) {
                    $person = Person::where('documentNumber', $value)
                        ->whereNull('deleted_at') // Ignora los eliminados
                        ->first();

                    if ($person && User::where('person_id', $person->id)->whereNull('deleted_at')->exists()) {
                        $fail('La persona ya tiene un usuario asociado.');
                    }
                },
            ],

        ], [
            'email.required'     => 'El correo electrónico es obligatorio.',
            'email.email'        => 'El formato del correo electrónico no es válido.',
            'email.regex'        => 'El correo electrónico debe cumplir con el formato correcto.',
            'email.string'       => 'El correo electrónico debe ser una cadena de texto.',
            'email.unique'       => 'El correo electrónico ya está registrado.',
            'password.required'  => 'La contraseña es obligatoria.',
            'person_id.required' => 'Campo Persona es obligatorio.',
            'person_id.exists'   => 'Id de la Persona no Existe',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        $hashedPassword = Hash::make($request->input('password'));

        $data = [

            'email'     => $request->input('email') ?? null,
            'password'  => $hashedPassword ?? null,
            'person_id' => $request->input('person_id') ?? null,
        ];

        $object = User::create($data);
        return User::with(['person'])->find($object->id);

    }

    /**
     * @OA\Post(
     *     path="/reservas360/public/api/send-token",
     *     summary="Envía un código de verificación por correo",
     *     tags={"Authentication"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(required=true, @OA\JsonContent(
     *         @OA\Property(property="names", type="string", example="Miguel Guevara"),
     *         @OA\Property(property="email", type="string", format="email", example="guevaracajusolmiguel@gmail.com"),
     *         @OA\Property(property="phone", type="string", example="903017426")
     *     )),
     *     @OA\Response(response=200, description="Código enviado", @OA\JsonContent(@OA\Property(property="message", type="string", example="Código Enviado Exitosamente"))),
     *     @OA\Response(response=401, description="No autorizado", @OA\JsonContent(@OA\Property(property="status", type="string", example="unauthorized")))
     * )
     */
    public function send_token_sign_up(SendTokenAppRequest $request)
    {
        if ($request->header('UUID') !== 'ZXCV-CVBN-VBNM') {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        $this->authService->sendToken(array_merge($request->validated(), ['send_by' => "email"]));
        return response()->json(['message' => 'Código Enviado Exitosamente'], 200);
    }

/**
 * @OA\Post(
 *     path="/reservas360/public/api/validate-mail",
 *     summary="Valida token y crea usuario",
 *     tags={"Authentication"},
 *     security={{"bearerAuth":{}}},
 *     @OA\RequestBody(required=true, @OA\JsonContent(
 *         @OA\Property(property="names", type="string", example="Miguel Guevara"),
 *         @OA\Property(property="email", type="string", format="email", example="guevaracajusolmiguel@gmail.com"),
 *         @OA\Property(property="phone", type="string", example="903017426"),
 *         @OA\Property(property="password", type="string", format="password", example="#MiguelMiguel123"),
 *         @OA\Property(property="token_form", type="string", example="6800")
 *     )),
 *     @OA\Response(response=200, description="Usuario creado", @OA\JsonContent(ref="#/components/schemas/User")),
 *     @OA\Response(response=401, description="No autorizado", @OA\JsonContent(@OA\Property(property="status", type="string", example="unauthorized"))),
 *     @OA\Response(response=422, description="Token inválido", @OA\JsonContent(@OA\Property(property="message", type="string", example="Su token ha vencido, Debe generar nuevo token")))
 * )
 */
    public function validate_mail(StoreUserAppRequest $request): JsonResponse
    {
        if ($request->header('UUID') !== 'ZXCV-CVBN-VBNM') {
            return response()->json(['status' => 'unauthorized'], 401);
        }

        if (! $this->authService->validate_token($request->email, $request->token_form)) {
            return response()->json(['message' => 'Su token ha vencido, Debe generar nuevo token'], 422);
        }

        $data = array_merge($request->validated(), ['typeofDocument' => "DNI", 'ocupation' => "USUARIO", 'email' => $request->email]);

        $user = $this->userService->createUser($data);

        if ($user) {                         // Verifica si la creación del usuario fue exitosa
            Cache::forget("{$request->email}");  // Elimina el cache
            return response()->json($user, 200); // Retorna el usuario creado
        }

        return response()->json(['error' => 'No se pudo crear el usuario'], 500);

    }
    public function view_token_email(Request $request)
    {
        $token           = "1234";
        $name_aplication = "Reservas 360";
        return view('emails.token', ['token' => $token,
            'name_aplication'                    => $name_aplication]);
    }

}
