<?php

namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\User;
use App\Models\Repair_Cer;
use App\Models\Image;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Symfony\Polyfill\Intl\Idn\Info;
use Illuminate\Support\Facades\DB;

class InformController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('test');
        //
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


    //ตรวจสอบการ login
    public function __construct()
    {
        $this->middleware('auth');
    }

    //ค้นหารหัสครุภัณฑ์
    public function autocomplete(Request $request)
    {

        $data = Equipment::where('STATUS_KEY', '=', 1)
            ->where('EQUIPMENT_ID', 'like', "%" . $request->term . "%")
            ->select(['EQUIPMENT_ID as label', 'EQUIPMENT_ID as value'])
            ->get();

        return response()->json($data);
    }

    //ดึงรายละเอียดครุภัณฑ์จากเลขครุภัณฑ์
    public function getequipment(Request $request)
    {
        $EQUIPMENT = $request->EQUIPMENT_ID;
        $data = Equipment::where('EQUIPMENT_ID', '=', $EQUIPMENT)
            ->first();
        return view('inform.informnext', compact(['data']));
    }


    //เช็คสิทธิการเข้าหน้าแจ้งซ่อม
    public function inform()
    {

        if (auth()->user()->hasRole(['user', 'admin', 'technician', 'manager'])) {
            return view('inform.inform');
        } else {
            return abort('403');
        }
    }

    //เพิ่มข้อมูลเพื่อสร้างใบแจ้งซ่อม
    public function addrepair_cer(Request $request)
    {

        $EQUIPMENT_KEY = $request->equipment_key;
        $subject = $request->subject;
        $symptom = $request->symptom;
        $place = $request->place;
        $phone_number = $request->phone_number;
        $noti_personid = $request->noti_personid;
        $noti_date = $request->noti_date;
        $status_repair = $request->status_repair;
        $imageFile = $request->imageFile;

        if ($imageFile == "") {

            $data = Repair_Cer::create(["EQUIPMENT_KEY" => $EQUIPMENT_KEY, "subject" => $subject, "symptom" => $symptom, "place" => $place, "phone_number" => $phone_number, "noti_personid" => $noti_personid, "noti_date" => $noti_date, "status_repair" => $status_repair]);
            return redirect('repairnoti');
        } else {


            $data = Repair_Cer::create(["EQUIPMENT_KEY" => $EQUIPMENT_KEY, "subject" => $subject, "symptom" => $symptom, "place" => $place, "phone_number" => $phone_number, "noti_personid" => $noti_personid, "noti_date" => $noti_date, "status_repair" => $status_repair]);
            $repar_id = $data->id;
            $request->validate([
                'imageFile' => 'required',
                'imageFile.*' => 'mimes:jpeg,jpg,png,gif,csv,txt,pdf|max:2048'
            ]);

            if ($request->hasfile('imageFile')) {
                foreach ($request->file('imageFile') as $file) {
                    $name = $file->getClientOriginalName();
                    $file->move(public_path() . '/uploads/', $name);
                    $imgData[] = $name;
                    $img = Image::create(["image_path" => $name, "repair_cer_id" => $repar_id]);
                }

                return redirect('repairnoti');
            }
        }
    }
}
