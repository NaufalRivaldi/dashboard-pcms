<?php
// ----------------------------------------------------------------------------
namespace App\Http\Controllers\Backend\Password;
// ----------------------------------------------------------------------------
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
// ----------------------------------------------------------------------------
use Auth;
// ----------------------------------------------------------------------------
use App\Models\User;
// ----------------------------------------------------------------------------
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    // ------------------------------------------------------------------------
    public function index()
    {
        // --------------------------------------------------------------------
        $data = new \stdClass;
        $data->title        = "Ubah Password";
        // --------------------------------------------------------------------
        return view('backend.password.index', (array) $data);
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // ------------------------------------------------------------------------
    public function update(Request $request)
    {
        // --------------------------------------------------------------------
        // Set validation
        // --------------------------------------------------------------------
        Validator::make($request->all(), [
            'password_old'          => 'required|max:191',
            'password_new'          => 'required|max:191',
            'password_confirm'      => 'required|max:191|same:password_new',
        ])->validate();
        // --------------------------------------------------------------------

        // --------------------------------------------------------------------
        // Use try catch
        // --------------------------------------------------------------------
        try {
            // ----------------------------------------------------------------
            $data = $request->all();
            // ----------------------------------------------------------------
            // Check old password same or not
            // ----------------------------------------------------------------
            $userPassword = Auth::user()->password;
            if(!Hash::check($data['password_old'], $userPassword)){
                return redirect()->route('password-user.index')->with('danger', __('label.old_password_not_match'));
            }
            // ----------------------------------------------------------------
            $user                 = User::findOrFail(Auth::user()->id);
            $user->password       = bcrypt($data['password_new']);
            $user->save();
            // ----------------------------------------------------------------
            Auth::logout();
            return redirect()->route('login')->with('success', 'Password berhasil diubah, silahkan login kembali');
            // ----------------------------------------------------------------
        } catch (\Throwable $th) {
            return redirect()->route('password-user.index')->with('success', __('label.FAIL_UPDATE_MESSAGE'));
        }
        // --------------------------------------------------------------------
    }
    // ------------------------------------------------------------------------
}
// ----------------------------------------------------------------------------