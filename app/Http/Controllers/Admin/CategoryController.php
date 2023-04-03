<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use Illuminate\Http\Request;
use PhpParser\Node\Expr\FuncCall;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CategoryStoreRequest;

class CategoryController extends Controller
{
    //
    public function category()
    {
        $category = Category::all();
        return view("admin.categories.index", compact('category'));
    }
    public function create()
    {
        return view("admin.categories.create");
    }
    public function store(CategoryStoreRequest $request)
    {
        $image = $request->file('image')->store('public/categories');
        Category::create([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image
        ]);

        return redirect('admin/categories')->with('success','Category created successfully');
    }
    public function destroy(Category $id)
    {
        Storage::delete($id->image);
        $id->menus()->detach();
        $id->delete();
        return back()->with('danger','Category deleted successfully');
    }
    public function edit(Category $category)
    {
        return view('admin.categories.edit', compact('category'));
    }
    public function update(Request $request, Category $category)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required'
        ]);
        $image = $category->image;
        if ($request->hasFile('image')) {
            Storage::delete($category->image);
            $image = $request->file('image')->store('public/categories');
        }

        $category->update([
            'name' => $request->name,
            'description' => $request->description,
            'image' => $image
        ]);
        return redirect('admin/categories')->with('success','Category edited successfully');
    }
}
