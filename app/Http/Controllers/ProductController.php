<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{

    public function index()
    {
        $products = Product::orderByDesc('id')->get();
        $sections = Section::pluck('id', 'section_name');
        return view('products.products', compact('sections', 'products'));
    }

    public function create()
    {
        return back();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'section_id' => 'required|exists:sections,id',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        try {
            Product::create([
                'product_name' => $request->product_name,
                'section_id' => $request->section_id,
                'description' => $request->description,
            ]);
            flash('تم اضافة المنتج بنجاح')->success();
            return back();
        } catch (Exception $e) {
            return $this->error();
        }

    }

    public function show(Product $product)
    {
        return back();
    }

    public function edit(Product $product)
    {
        return back();
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'product_name' => 'required',
            'section_name' => 'required|exists:sections,section_name',
            'description' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->error($validator->errors()->first());
        }

        try {

            $product = Product::findOrFail($request->pro_id);
            $section_id = Section::where('section_name', $request->section_name)->first()->id;

            $product->update([
                'product_name' => $request->product_name,
                'description' => $request->description,
                'section_id' => $section_id,
            ]);
            flash('تم تحديث المنتج بنجاح')->success();
            return back();
        } catch (Exception $e) {
            return $this->error();
        }

    }

    public function destroy(Request $request)
    {
        try {
            $product = Product::find($request->pro_id);
            $product->delete();
            flash('تم حذف المنتج بنجاح')->success();
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
