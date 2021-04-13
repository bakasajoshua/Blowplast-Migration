<?php

namespace App\Imports;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Category;
use App\GLAccounts;
use App\SubCategory;

class GLCategoryImport implements ToCollection, WithHeadingRow, WithProgressBar 
{
    use Importable;
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
    	foreach ($collection as $key => $item) {
    		$category = $this->getCategory($item);
    		$subcategory = $this->getSubCategory($item, $category);
    		$account = GLAccounts::where('GL_Account_Name', $item['coa_name'])->where('Company_Code', 'BPL')->get();
    		if (!$account->isEmpty()) {
    			$account = $account->first();
    			$account->Category_1_ID = $category->id;
    			$account->Category_1_Description = $category->category_name;
    			$account->Category_2_ID = $subcategory->id;
    			$account->Category_2_Description = $subcategory->sub_category_name;
    			$account->save();
    		}
    	}
    	return true;
    }

    private function getCategory($item)
    {
    	$category = Category::where('category_name', $item['cat1'])->get();
		if ($category->isEmpty()) {
			$category = Category::create([
							'category_name' => $item['cat1'],
						]);
		} else {
			$category = $category->first();
		}
		return $category;
    }

    private function getSubCategory($item, $category)
    {
    	$subcategory = SubCategory::where('sub_category_name', $item['cat2'])->get();
		if ($subcategory->isEmpty()) {
			$subcategory = SubCategory::create([
							'sub_category_name' => $item['cat2'],
							'category_id' => $category->id,
						]);
		} else {
			$subcategory = $subcategory->first();
		}
		return $subcategory;
    }
}
