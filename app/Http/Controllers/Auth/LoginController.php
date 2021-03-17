<?php
// ----------------------------------------------------------------------------
namespace App\Http\Controllers\Auth;
// ----------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Validator;
// ----------------------------------------------------------------------------
use Auth;
// ----------------------------------------------------------------------------
class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    // ------------------------------------------------------------------------
    use AuthenticatesUsers;
    // ------------------------------------------------------------------------
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // ------------------------------------------------------------------------
    protected $redirectTo = RouteServiceProvider::HOME;
    // ------------------------------------------------------------------------
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    // ------------------------------------------------------------------------
    public function __construct(){
        $this->middleware('guest')->except('logout');
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function index(){
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title    = 'Login';
        // --------------------------------------------------------------------
        return view('auth.login', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function login(Request $request){
        // --------------------------------------------------------------------
        // Set validation
        // --------------------------------------------------------------------
        Validator::make($request->all(), [
            'username'  => 'required',
            'password'  => 'required',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Set cridentials
        // --------------------------------------------------------------------
        $cridentials = [
            "username" => $request->username,
            "password" => $request->password,
            "status"   => 1,
        ];
        // --------------------------------------------------------------------
        if(Auth::attempt($cridentials)){
            return redirect()->route('dashboard.index')->with('success', 'Selamat datang '.Auth::user()->nama.', selamat bekerja.');
        }
        // --------------------------------------------------------------------
        return redirect()->route('login')->with('danger', 'Username dan password tidak valid!');
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    // ------------------------------------------------------------------------
    public function logout(){
        // --------------------------------------------------------------------
        Auth::logout();
        return redirect()->route('login')->with('success', 'Terima kasih telah menggunakan sistem '.replaceUnderscore(env('APP_NAME')));
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------