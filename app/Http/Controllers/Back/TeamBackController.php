<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Team;
use Alert;
use App\Models\RefDivisi;
use Illuminate\Support\Facades\Storage;

class TeamBackController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data['team'] = Team::all();
        $data['ref_divisi'] = RefDivisi::where('status', '=', 'Y')->get();
        $data['allTeam'] = Team::all();
        return view('back.team.index', $data);
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
        $photo = ($request->team_photo) ? $request->file('team_photo')->store("/public/input/teams") : null;
        
        $data = [
            'fullname' => $request->team_fullname,
            'photo' => $photo,
            'ref_divisi_id' => $request->ref_divisi_id,
            // 'division_id' => $request->team_division_id,
            // 'sub_division_id' => $request->team_sub_division_id,
        ];

        Team::create($data)
        ? Alert::success('Berhasil', 'Team telah berhasil ditambahkan!')
        : Alert::error('Error', 'Team gagal ditambahkan!');

        return redirect()->back();
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
        $team = Team::findOrFail($id);
        if($request->hasFile('edit_team_photo')) {
            if(Storage::exists($team->photo) && !empty($team->photo)) {
                Storage::delete($team->photo);
            }

            $edit_photo = $request->file('edit_team_photo')->store('/public/input/teams');
        }
        $data = [
            'fullname' => $request->edit_team_fullname ? $request->edit_team_fullname : $team->fullname, 
            'photo' => $request->hasFile('edit_team_photo') ? $edit_photo : $team->photo, 
            'ref_divisi_id' => $request->edit_ref_divisi_id ? $request->edit_ref_divisi_id : $team->ref_divisi_id, 
            // 'division_id' => $request->edit_team_division_id ? $request->edit_team_division_id : $team->division_id, 
            // 'sub_division_id' => $request->edit_team_sub_division_id ? $request->edit_team_sub_division_id : $team->sub_division_id, 
        ];

        $team->update($data)
        ? Alert::success('Berhasil', "Team telah berhasil diubah!")
        : Alert::error('Error', "Team gagal diubah!");

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $team = Team::find($id);

        $team->delete() 
        ? Alert::success('Berhasil', "Team telah berhasil dihapus!")
        : Alert::error('Error', "Team gagal dihapus!");

        return redirect()->back();
    }

    
}
