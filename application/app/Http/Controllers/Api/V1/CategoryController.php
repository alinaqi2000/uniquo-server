<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Resources\CategoryResource;
use App\Jobs\ValidateCategory;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoryController extends BaseController
{
    public function request(Request $request)
    {
        $rules = ['title' => "required|min:3|max:25|unique:categories|bad_word"];
        $errors = $this->reqValidate($request->all(), $rules, ['title.unique' => "Category exists already.", 'bad_word' => 'The :attribute cannot contain a bad word.']);
        if ($errors)
            return $errors;

        $category = auth()->user()->category_suggests()->create(['title' => $request->title, "slug" => Str::slug($request->title)]);

        ValidateCategory::dispatch($category);


        return $this->resMsg(['success' => "Category creation request has been sent successfully."]);
    }
    public function all(Request $request)
    {
        $condition = [[]];
        if ($request->has("s")) {
            $condition = ['title', 'LIKE', '%' . $request->get("s") . '%'];
        }
        $cats = Category::where(...$condition)->verified()->get();
        return $this->resData(CategoryResource::collection($cats));
    }
    public function user_all(Request $request)
    {
        $condition = [[]];
        if ($request->has("s")) {
            $condition = ['title', 'LIKE', '%' . $request->get("s") . '%'];
        }
        $cats = Category::where(...$condition)->verified()->get()->merge(
            auth()->user()->category_suggests()->where(...$condition)->get()
        );

        return $this->resData(CategoryResource::collection($cats));
    }
    public function dashboard_categories(Request $request)
    {
        $condition = [[]];

        $top = Category::where(...$condition)->verified()->top()->get();
        $new = Category::where(...$condition)->verified()->new()->get();
        $recent = Category::where(...$condition)->verified()->top()->get();

        return $this->resData([
            "top" => CategoryResource::collection($top),
            "new" => CategoryResource::collection($new),
            "recent" => CategoryResource::collection($recent),
        ]);
    }
}
