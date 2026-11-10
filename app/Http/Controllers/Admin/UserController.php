<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    /**
     * แสดงรายการผู้ใช้งานทั้งหมด
     */
    public function index()
    {
        $users = User::with('roles')->orderBy('name', 'asc')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * แสดงฟอร์มเพิ่มผู้ใช้งานใหม่
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * บันทึกข้อมูลผู้ใช้งานใหม่
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'personid' => 'required|string|max:50|unique:users,personid',
            'role' => 'required|string',
        ], [
            'name.required' => 'กรุณากรอกชื่อ-สกุล',
            'username.required' => 'กรุณากรอก Username',
            'username.unique' => 'Username นี้ถูกใช้งานในระบบแล้ว',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้งานในระบบแล้ว',
            'personid.required' => 'กรุณากรอกรหัสบุคลากร',
            'personid.unique' => 'รหัสบุคลากรนี้ถูกใช้งานในระบบแล้ว',
            'role.required' => 'กรุณาเลือกสิทธิการใช้งานระบบ',
        ]);

        try {
            DB::beginTransaction();

            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'personid' => $request->personid,
                'password' => Hash::make('12345678'),
            ]);

            if ($request->role) {
                $user->syncRoles([$request->role]);
            }

            DB::commit();

            Log::info('User Created: ' . $user->username, ['admin_id' => auth()->id()]);

            return redirect()->route('admin.users.index')
                ->with('success', 'เพิ่มผู้ใช้งานสำเร็จ!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User Store Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการบันทึกข้อมูล: ' . $e->getMessage());
        }
    }

    /**
     * แสดงฟอร์มแก้ไขข้อมูลผู้ใช้งาน
     */
    public function edit($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $roles = Role::all();
        $firstRole = $user->roles->first();
        $userRole = $firstRole ? $firstRole->name : '';
        return view('admin.users.edit', compact('user', 'roles', 'userRole'));
    }

    /**
     * อัปเดตข้อมูลผู้ใช้งาน
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,' . $id,
            'email' => 'required|email|max:255|unique:users,email,' . $id,
            'personid' => 'required|string|max:50',
            'role' => 'required|string',
            'password' => 'nullable|string|min:6',
        ], [
            'name.required' => 'กรุณากรอกชื่อ-สกุล',
            'username.required' => 'กรุณากรอก Username',
            'username.unique' => 'Username นี้ถูกใช้งานในระบบแล้ว',
            'email.required' => 'กรุณากรอกอีเมล',
            'email.email' => 'รูปแบบอีเมลไม่ถูกต้อง',
            'email.unique' => 'อีเมลนี้ถูกใช้งานในระบบแล้ว',
            'personid.required' => 'กรุณากรอกรหัสบุคลากร',
            'role.required' => 'กรุณาเลือกสิทธิการใช้งานระบบ',
            'password.min' => 'รหัสผ่านต้องมีอย่างน้อย 6 ตัวอักษร',
        ]);

        try {
            DB::beginTransaction();

            $user = User::findOrFail($id);

            $data = [
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'personid' => $request->personid,
            ];

            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            $user->update($data);

            if ($request->role) {
                $user->syncRoles([$request->role]);
            }

            DB::commit();

            Log::info('User Updated: ' . $user->username, ['admin_id' => auth()->id()]);

            return redirect()->route('admin.users.index')
                ->with('success', 'อัปเดตข้อมูลผู้ใช้งานสำเร็จ!');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('User Update Error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล: ' . $e->getMessage());
        }
    }

    /**
     * ลบผู้ใช้งาน
     */
    public function destroy($id)
    {
        try {
            $user = User::findOrFail($id);

            // ป้องกันไม่ให้ลบตัวเอง
            if ($user->id === auth()->id()) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'ไม่สามารถลบบัญชีผู้ใช้งานของตนเองได้');
            }

            // ป้องกันไม่ให้ลบผู้ใช้งานที่เคยสร้างโครงการแล้ว
            $hasProjects = DB::table('academic_projects')->where('created_by', $id)->exists();
            if ($hasProjects) {
                return redirect()->route('admin.users.index')
                    ->with('error', 'ไม่สามารถลบผู้ใช้งานนี้ได้ เนื่องจากมีประวัติการสร้างโครงการในระบบแล้ว');
            }

            $username = $user->username;
            $user->delete();

            Log::info('User Deleted: ' . $username, ['admin_id' => auth()->id()]);

            return redirect()->route('admin.users.index')
                ->with('success', 'ลบผู้ใช้งานสำเร็จ!');
        } catch (\Exception $e) {
            Log::error('User Delete Error: ' . $e->getMessage());
            return redirect()->route('admin.users.index')
                ->with('error', 'เกิดข้อผิดพลาดในการลบผู้ใช้งาน');
        }
    }
}
