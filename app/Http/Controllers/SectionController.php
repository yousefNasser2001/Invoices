<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{

    public function index()
    {
        $sections = Section::orderByDesc('id')->get();
        return view('sections.sections' , compact('sections'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'section_name' => 'required|unique:sections|max:255',
            'description' => 'required|unique:sections|max:255',
        ]);
        // هاد طريقة ترجمة رسائل الخطا دون استخدام دوال الترجمة
        // ] , [
        //     'section_name.required' => 'يرجى ادخال اسم القسم',
        //     'section_name.unique' => 'اسم القسم موجود مسبقا',
        //     'description.required' => 'يرجى ادخال الوصف',
        // ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }


        Section::create([
            'section_name' => $request->section_name,
            'description' => $request->description,
            'created_by' => (Auth::user()->name),

        ]);
        flash('تم اضافة القسم بنجاح')->success();
        return redirect()->route('sections.index');
    }

    public function show(Section $section)
    {
        //
    }

    public function edit(Section $section)
    {
        //
    }

    public function update(Request $request, Section $section)
    {
        //
    }

    public function destroy(Section $section)
    {
        //
    }

    public function error($message = null): RedirectResponse
    {
        flash(translate($message ?? 'messages.Wrong'))->error();
        return back();
    }
}
