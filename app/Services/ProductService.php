<?php
namespace App\Services;

use App\Http\Resources\ProductResource;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ProductService
{

    public function getProductById(int $id): ?Product
    {
        return Product::find($id);
    }
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    public function updateProduct($Product, array $data)
    {
        $Product->update($data);
        return $Product;
    }
    public function destroyById($id)
    {
        $Product = Product::find($id);

        if (!$Product) {
            return false;
        }
        return $Product->delete(); // Devuelve true si la eliminación fue exitosa
    }

     
    

}
