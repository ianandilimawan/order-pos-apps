<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\ActivityLogService;
use App\Models\Product;
use App\Http\Requests\CreateProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\Request;
use App\Traits\HasFileUpload;


class ProductController extends Controller
{
        use HasFileUpload;


    public function __construct()
    {
        $this->middleware('permission:view-products')->only(['index', 'show']);
        $this->middleware('permission:create-product')->only(['create', 'store']);
        $this->middleware('permission:edit-product')->only(['edit', 'update']);
        $this->middleware('permission:delete-product')->only('destroy');
    }

    public function index()
    {
        return view('admin.products.index');
    }

    public function create()
    {
        $product = new Product();
        $fileUrls = $this->getFileUrls(Product::class);
        $categories = \App\Models\Category::pluck('name', 'id');

        return view('admin.products.create', compact('product', 'fileUrls', 'categories'));
    }

    public function store(CreateProductRequest $request)
    {
        $data = $request->validated();

        $this->handleFileUploads($request, $data, Product::class, 'product');

        $product = Product::create($data);

        ActivityLogService::logCreate($product);

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully.');
    }

    public function show(Product $product)
    {
        return view('admin.products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        $fileUrls = $this->getFileUrls(Product::class, $product);
        $categories = \App\Models\Category::pluck('name', 'id');

        return view('admin.products.edit', compact('product', 'fileUrls', 'categories'));
    }

    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();

        $this->handleFileUploads($request, $data, Product::class, 'product', $product);

        $oldValues = $product->getOriginal();

        $product->update($data);

        ActivityLogService::logUpdate($product, $oldValues);

        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $this->deleteAssociatedFiles(Product::class, $product);

        ActivityLogService::logDelete($product);

        $product->delete();
        return redirect()->route('admin.products.index')->with('success', 'Product deleted successfully.');
    }


}
