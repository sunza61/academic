<?php

namespace App\Http\Controllers\Projects;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Academic\AcademicProject;
use App\Models\MasterData\ProjectType;
use App\Models\AcademicProjectLog;
use Carbon\Carbon;

class LecturerProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $typeId = $request->query('type_id', 4); // ค่าเริ่มต้นคือ 4 (วิทยากร)
        
        $projectType = ProjectType::findOrFail($typeId);

        $query = AcademicProject::select(
            'academic_projects.*',
            'users.name as name',
            'overall_statuses.name_th as overall_statuses_name_th'
        )
            ->leftJoin('users', 'academic_projects.created_by', '=', 'users.id')
            ->leftJoin('overall_statuses', 'academic_projects.overall_status', '=', 'overall_statuses.code')
            ->where('academic_projects.project_type_id', $typeId)
            ->where(function ($q) {
                $q->whereNull('academic_projects.del_status')
                    ->orWhere('academic_projects.del_status', 0);
            });

        $user = auth()->user();
        $isAdminOrStaff = $user->hasAnyRole(['admin', 'staff']);

        if (!$isAdminOrStaff && !$user->hasRole('manager')) {
            $query->where('academic_projects.created_by', $user->id);
        }

        $projects = $query->with('latestLog.user')->orderBy('academic_projects.id', 'desc')->get();

        $projects->map(function ($item) use ($user, $isAdminOrStaff) {
            $isOwner = isset($item->created_by) && $item->created_by == $user->id;
            $status = $item->overall_status;

            $item->can_edit = ($status != 800) && ($isAdminOrStaff || $isOwner);
            $item->show_delete_btn = ($isAdminOrStaff || $isOwner);
            $item->can_report = ($status >= 600 && $status != 900) && ($isAdminOrStaff || $isOwner);

            $canCancel = false;
            if ($status != 800 && $status != 900) {
                if ($isAdminOrStaff) {
                    $canCancel = true;
                } elseif ($isOwner && $status <= 700) {
                    $canCancel = true;
                }
            }
            $item->can_cancel = $canCancel;

            if ($item->latestLog) {
                $item->log_reason = $item->latestLog->comment;
                $item->log_action_by = $item->latestLog->user->name ?? 'ไม่ระบุ';
                $item->log_action_date = Carbon::parse($item->latestLog->created_at)->addYears(543)->format('d/m/Y H:i');
            } else {
                $item->log_reason = 'ไม่พบเหตุผลที่ระบุไว้';
                $item->log_action_by = '-';
                $item->log_action_date = '-';
            }

            return $item;
        });

        return view('lecturers.projects.index', compact('projectType', 'projects'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // จะพัฒนาต่อในอนาคต
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
