<?php

namespace App\Http\Controllers;

use App\Models\Section;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SectionController extends Controller
{

    public function index()
    {
        $sections = Section::orderByDesc('id')->get();
        return view('sections.sections', compact('sections'));
    }

    public function create()
    {
        return back();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_name' => 'required|unique:sections|max:255',
            'description' => 'nullable',
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

        try {
            Section::create([
                'section_name' => $request->section_name,
                'description' => $request->description,
                'created_by' => (Auth::user()->name),

            ]);
            flash('تم اضافة القسم بنجاح')->success();
            return redirect()->route('sections.index');
        } catch (Exception $e) {
            return $this->error();
        }
    }

    public function show(Section $section)
    {
        return back();
    }

    public function edit(Section $section)
    {
        return back();
    }

    public function update(Request $request)
    {
        $id = $request->id();
        $validator = Validator::make($request->all(), [
            'section_name' => 'required|max:255|unique:sections,section_name,' . $id,
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        try {
            $section = Section::find($id);
            $section->update([
                'section_name' => $request->section_name,
                'description' => $request->description,
            ]);
            flash('تم تحديث القسم بنجاح')->success();

            return back();
        } catch (Exception $e) {
            return $this->error();
        }
    }

    public function destroy(Request $request)
    {
        try {
            $section = Section::find($request->id);
            $section->delete();
            flash('تم حذف القسم بنجاح')->success();
            return back();
        } catch (Exception) {
            return $this->error();
        }

    }

    public function error($message = null): RedirectResponse
    {
        flash(translate($message ?? 'messages.Wrong'))->error();
        return back();
    }
}
