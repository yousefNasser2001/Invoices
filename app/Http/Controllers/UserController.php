<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Activitylog\Models\Activity;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{

    public function __construct()
    {
        $this->middleware('permission:' . USERS_PERMISSION)->only('index');
        $this->middleware('permission:' . CREATE_USER_PERMISSION)->only('create', 'store');
        $this->middleware('permission:' . EDIT_USER_PERMISSION)->only('edit', 'update');
        $this->middleware('permission:' . DELETE_USER_PERMISSION)->only('destroy');
    }

    public function index(Request $request)
    {
        $data = User::orderBy('id', 'DESC')->paginate(5);
        return view('users.show_user', compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 5);
    }

    public function create()
    {
        $roles = Role::pluck('name', 'name')->all();

        return view('users.Add_user', compact('roles'));

    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles_name' => 'required',
            'working_hours_start' => 'nullable|date_format:H:i',
            'working_hours_end' => 'nullable|date_format:H:i|after:working_hours_start',

        ]);

        $input = $request->all();

        try {
            $input['password'] = Hash::make($input['password']);

            $user = User::create($input);
            $user->assignRole($request->input('roles_name'));
            flash('تم اضافة المستخدم بنجاح')->success();
            return redirect()->route('users.index');
        } catch (Exception $e) {
            return $this->error();
        }
    }

    public function show($id)
    {
        $user = User::find($id);
        return view('users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name', 'name')->all();
        return view('users.edit_user', compact('user', 'roles', 'userRole'));
    }

    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'same:confirm-password',
            'roles_name' => 'required',
            'working_hours_start' => 'nullable|date_format:H:i',
            'working_hours_end' => 'nullable|date_format:H:i|after:working_hours_start',
        ]);

        $input = $request->all();

        try {

            if (!empty($input['password'])) {
                $input['password'] = Hash::make($input['password']);
            } else {
                unset($input['password']);
            }

            $user = User::find($id);
            $user->update($input);

            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $user->assignRole($request->input('roles_name'));

            // $user->syncRoles([$request->input('roles')]);

            flash('تم تحديث بيانات المستخدم بنجاح')->success();
            return redirect()->route('users.index');
        } catch (Exception $e) {
            return $this->error();
        }

    }

    public function userActivity()
    {
        // Retrieve all users with their activities
        $usersWithActivities = User::with('activities')->orderByDesc('id')->get();

        // Join the activity_log and users tables to get user names for each activity
        $activityLogs = Activity::leftJoin('users', function ($join) {
            $join->on('activity_log.subject_id', '=', 'users.id')
                ->whereRaw('activity_log.causer_id = users.id');
        })
            ->select('activity_log.*', 'users.name as user_name')
            ->orderByDesc('id')->get();

        return view('users.user-avtivity', compact('usersWithActivities', 'activityLogs'));
    }

    public function destroy(Request $request)
    {
        User::find($request->user_id)->delete();
        return redirect()->route('users.index')->with('success', 'تم حذف المستخدم بنجاح');
    }

    public function error($message = null): RedirectResponse
    {
        flash(translate($message ?? 'messages.Wrong'))->error();
        return back();
    }
}
