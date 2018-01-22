<?php

namespace App\Http\Controllers;

use App\Division;
use Illuminate\Http\Request;
use DataTables;
use Validator;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public $judul = 'Division';
    public $controller = 'DivisionController';

    public function dt(){

        return DataTables::of(Division::all())
            ->addIndexColumn()
            ->addColumn('option', function($data){
                return view('admin.part.tombol.division')
                    ->with('data',$data)
                    ->with('controller',$this->controller);
            })
            ->rawColumns(['option'])
            ->make();
    }


    public function index()
    {
        return view('admin.division.index')
            ->with('judul', $this->judul)
            ->with('subjudul', $this->judul.' Data')
            ->with('breadcrumbs', ['setting', 'data', 'division', route('division.i')=>'index' ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.division.create')
            ->with('judul', $this->judul)
            ->with('subjudul', 'Add new '. $this->judul)
            ->with('breadcrumbs', ['setting', 'data', route('division.i')=>'division', route('division.c')=>'create' ])
            ->with('formAction', $this->controller.'@store');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(),[
            'division_id'=>'required',
            'division_name'=>'required'
        ],[
            'division_id.required'=>'Please fill division id field',
            'division_name.required'=>'Please fill division name field',
        ]);

        if($validator->fails()){
            return redirect()->route('division.c')
                ->WithErrors($validator)
                ->with('alert_type', 'danger')
                ->with('alert_msg', 'Cant submit your form, please recheck your input.');
        }

        Division::create($request->all());

        return redirect()->route('division.i')
            ->with('success', '<strong>Success.</strong> New division data has been saved.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function show(Division $division)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function edit(Division $division)
    {
        return view('admin.division.create')
            ->with('judul', $this->judul)
            ->With('edit', Division::find($division->id))
            ->with('subjudul', 'Update '. $this->judul.' Data')
            ->with('formAction', [$this->controller.'@update', $division->id])
            ->with('breadcrumbs', ['setting', 'data', route('division.i')=>'division', route('division.e', $division->id)=>'update' ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Division $division)
    {
        $validator = Validator::make($request->all(),[
            'division_id'=>'required',
            'division_name'=>'required'
        ],[
            'division_id.required'=>'Please fill division id field',
            'division_name.required'=>'Please fill division name field',
        ]);

        if($validator->fails()){
            return redirect()->back()
                ->WithErrors($validator)
                ->with('alert_type', 'danger')
                ->with('alert_msg', 'Cant submit your form, please recheck your input.');
        }

        $division->fill($request->all());
        $division->save();
        return redirect()->route('division.i')
            ->with('success', '<strong>Success.</strong> Division data has been updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Division  $division
     * @return \Illuminate\Http\Response
     */
    public function destroy(Division $division)
    {
        Division::find($division->id)->delete();

        return redirect()->route('division.i')
            ->with('success', '<strong>Success !</strong> Data has been deleted.');
    }
}
