<?php
namespace App\Services;

use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CategoryService
{

    public function getCategoryById(int $id): ?Category
    {
        return Category::find($id);
    }
    public function createCategory(array $data): Category
    {
        return Category::create($data);
    }

    public function updateCategory($category, array $data)
    {
        $category->update($data);
        return $category;
    }
    public function destroyById($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return false;
        }
        return $category->delete(); // Devuelve true si la eliminación fue exitosa
    }

    // El método recibe el UUID de la sucursal
   
}
