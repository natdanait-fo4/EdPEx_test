<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class AdminUserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $role = $request->get('role');
        $query = User::orderBy('id', 'desc');

        if ($role && in_array($role, ['admin', 'user'])) {
            $query->where('role', $role);
        }

        $users = $query->get();
        return view('admin.users.index', compact('users', 'role'));
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:users'],
            'password' => ['required', 'string', Password::min(8)],
            'role' => ['required', 'in:admin,user'],
            'fullname' => ['required', 'string', 'max:255'],
            'student_id' => ['nullable', 'string', 'max:50'],
            'major' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ], [
            'username.unique' => 'ชื่อผู้ใช้งานนี้มีอยู่ในระบบแล้ว',
            'fullname.required' => 'กรุณากรอกชื่อ-นามสกุล',
        ]);

        User::create([
            'username' => $request->username,
            'password' => $request->password, // Password casting will hash it automatically
            'role' => $request->role,
            'fullname' => $request->fullname,
            'student_id' => $request->student_id,
            'major' => $request->major,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'เพิ่มบัญชีผู้ใช้ใหม่เรียบร้อยแล้ว');
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $rules = [
            'username' => ['required', 'string', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => ['required', 'in:admin,user'],
            'fullname' => ['required', 'string', 'max:255'],
            'student_id' => ['nullable', 'string', 'max:50'],
            'major' => ['nullable', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
        ];

        // If password is provided, validate it
        if ($request->filled('password')) {
            $rules['password'] = ['string', Password::min(8)];
        }

        $request->validate($rules, [
            'username.unique' => 'ชื่อผู้ใช้งานนี้มีอยู่ในระบบแล้ว',
            'fullname.required' => 'กรุณากรอกชื่อ-นามสกุล',
        ]);

        $user->username = $request->username;
        $user->role = $request->role;
        $user->fullname = $request->fullname;
        $user->student_id = $request->student_id;
        $user->major = $request->major;
        $user->phone = $request->phone;
        $user->address = $request->address;

        if ($request->filled('password')) {
            $user->password = $request->password; // Will be hashed by model cast
        }

        $user->save();

        return redirect()->route('admin.users.index')->with('success', 'อัปเดตข้อมูลผู้ใช้งานเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);

        // Prevent deleting the currently logged-in admin to avoid locking themselves out
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')->with('error', 'ไม่สามารถลบบัญชีที่กำลังล็อกอินอยู่ได้');
        }

        $user->delete();

        return redirect()->route('admin.users.index')->with('success', 'ลบบัญชีผู้ใช้เรียบร้อยแล้ว');
    }
}
