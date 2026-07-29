<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Models\Category;
use App\Http\Requests\CreateCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use Illuminate\Http\Request;
use App\Traits\HasFileUpload;


class CategoryController extends Controller
{
        use HasFileUpload;


    public function __construct()
    {
        $this->middleware('permission:view-categories')->only(['index', 'show']);
        $this->middleware('permission:create-categories')->only(['create', 'store']);
        $this->middleware('permission:edit-categories')->only(['edit', 'update']);
        $this->middleware('permission:delete-categories')->only('destroy');
    }

    public function index()
    {
        return view('admin.categories.index');
    }

    public function create()
    {
        $category = new Category();
        $fileUrls = $this->getFileUrls(Category::class);

        return view('admin.categories.create', compact('category', 'fileUrls'));
    }

    public function store(CreateCategoryRequest $request)
    {
        $data = $request->validated();

        $this->handleFileUploads($request, $data, Category::class, 'category');

        $category = Category::create($data);

        ActivityLogService::logCreate($category);

        return redirect()->route('admin.categories.index')->with('success', 'Category created successfully.');
    }

    public function show(Category $category)
    {
        return view('admin.categories.show', compact('category'));
    }

    public function edit(Category $category)
    {
        $fileUrls = $this->getFileUrls(Category::class, $category);

        return view('admin.categories.edit', compact('category', 'fileUrls'));
    }

    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $data = $request->validated();

        $this->handleFileUploads($request, $data, Category::class, 'category', $category);

        $oldValues = $category->getOriginal();

        $category->update($data);

        ActivityLogService::logUpdate($category, $oldValues);

        return redirect()->route('admin.categories.index')->with('success', 'Category updated successfully.');
    }

    public function destroy(Category $category)
    {
        $this->deleteAssociatedFiles(Category::class, $category);

        ActivityLogService::logDelete($category);

        $category->delete();
        return redirect()->route('admin.categories.index')->with('success', 'Category deleted successfully.');
    }


}
