<?php

namespace App\Http\Controllers\Web\Goods;

use App\Http\Controllers\Controller;

use App\Models\Dictionaries\GoodsCategory;
use Illuminate\Http\Request;

final class GoodsTypeController extends Controller
{
    //TODO Need to refactor
    public function index(): \Illuminate\Contracts\View\View
    {
        $categories = GoodsCategory::with('children')->whereNull('parent_id')->get();

        return view('type-goods.index', compact('categories'));
    }

    public function store(Request $request)
    {

        $validatedData = $this->validate($request, [
            'name'      => 'required|min:3|max:255|string',
            'parent_id' => 'sometimes|nullable|numeric'
        ]);

        $category = GoodsCategory::create($validatedData);
        $category->key = GoodsTypeController . phpTransliterationHelper::transliterate($category->name) . $category->id;
        $category->save();

        return redirect()->route('type-goods.index')->withSuccess('You have successfully created a Category!');
    }

    public function update(Request $request, $id)
    {

        $validatedData = $this->validate($request, [
            'name'  => 'required|min:3|max:255|string',
            'parent_id' => 'sometimes|nullable|numeric',
        ]);

        $skuCategory = GoodsCategory::findOrFail($id);
        $skuCategory->key = GoodsTypeController . phpTransliterationHelper::transliterate($request->name) . $id;
        $skuCategory->update($validatedData);

        return redirect()->route('type-goods.index')->withSuccess('You have successfully updated a Category!');
    }

    /**
     * @return false|string
     */
    private function slugify($string): string|false
    {
        $string = transliterator_transliterate('Any-Latin; NFD; [:Nonspacing Mark:] Remove; NFC; [:Punctuation:] Remove; Lower();', $string);
        //$string = preg_replace('/[-\s]+/', '-', $string);
        //trim($string, '-');
        return $string;
    }
}
