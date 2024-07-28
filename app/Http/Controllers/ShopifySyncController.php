<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\Producto;
use Illuminate\Support\Facades\Log;

class ShopifySyncController extends Controller
{
    public function syncProductsToShopify()
    {
        $productos = Producto::all(); // Obtener todos los productos
        $shopifyUrl = 'https://' . config('shopify.shop_url') . '/admin/api/' . config('shopify.api_version') . '/products';
        $accessToken = config('shopify.access_token');

        if ($productos->isEmpty()) {
            return response()->json(['message' => 'No hay productos en la base de datos'], 404);
        }

        // Obtener todos los productos de Shopify
        $shopifyProducts = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
        ])->get($shopifyUrl . '.json')->json();

        foreach ($productos as $producto) {
            // Buscar si el producto ya existe en Shopify por código de barras
            $existingProduct = null;
            foreach ($shopifyProducts['products'] as $shopifyProduct) {
                foreach ($shopifyProduct['variants'] as $variant) {
                    if ($variant['sku'] == $producto->codigo_barras) {
                        $existingProduct = $shopifyProduct;
                        break 2; // Salir de ambos bucles si encontramos el producto
                    }
                }
            }

            if ($existingProduct) {
                // Actualizar producto existente en Shopify
                $response = Http::withHeaders([
                    'X-Shopify-Access-Token' => $accessToken,
                    'Content-Type' => 'application/json',
                ])->put($shopifyUrl . '/' . $existingProduct['id'] . '.json', [
                    'product' => [
                        'title' => $producto->nombre_producto,
                        'body_html' => $producto->descripcion,
                        'vendor' => $producto->id_familiar,
                        'product_type' => $producto->categoria,
                        'status' => 'draft',
                        'variants' => [
                            [
                                'id' => $existingProduct['variants'][0]['id'],
                                'price' => $producto->precio_publico,
                                'sku' => $producto->codigo_barras,
                                'inventory_quantity' => (int)$producto->cantidad,
                            ],
                        ],
                    ],
                ]);

                if ($response->successful()) {
                    $this->uploadImageToShopify($producto->img, $existingProduct['id'], $accessToken);
                }
            } else {
                // Crear nuevo producto en Shopify
                $response = Http::withHeaders([
                    'X-Shopify-Access-Token' => $accessToken,
                    'Content-Type' => 'application/json',
                ])->post($shopifyUrl . '.json', [
                    'product' => [
                        'title' => $producto->nombre_producto,
                        'body_html' => $producto->descripcion,
                        'vendor' => $producto->id_familiar,
                        'product_type' => $producto->categoria,
                        'status' => 'draft',
                        'variants' => [
                            [
                                'price' => $producto->precio_publico,
                                'sku' => $producto->codigo_barras,
                                'inventory_quantity' => (int)$producto->cantidad,
                            ],
                        ],
                    ],
                ]);

                if ($response->successful()) {
                    $shopifyProductId = $response->json()['product']['id'];
                    $this->uploadImageToShopify($producto->img, $shopifyProductId, $accessToken);
                }
            }

            if ($response->failed()) {
                Log::error('Error al enviar producto a Shopify', [
                    'product_id' => $producto->id,
                    'response' => $response->body()
                ]);
                return response()->json(['message' => 'Error al enviar producto a Shopify: ' . $response->body()], 500);
            }
        }

        return response()->json(['message' => 'Productos sincronizados con Shopify']);
    }

    private function uploadImageToShopify($base64Image, $shopifyProductId, $accessToken)
    {
        // Eliminar el prefijo "data:image/jpg;base64," si está presente
        if (strpos($base64Image, 'data:image/jpg; base64,') === 0) {
            $base64Image = str_replace('data:image/jpg; base64,', '', $base64Image);
        }

        $imageData = base64_decode($base64Image);
        $shopifyImageUrl = 'https://' . config('shopify.shop_url') . '/admin/api/' . config('shopify.api_version') . '/products/' . $shopifyProductId . '/images.json';

        $response = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
            'Content-Type' => 'application/json',
        ])->post($shopifyImageUrl, [
            'image' => [
                'attachment' => base64_encode($imageData),
            ],
        ]);

        if ($response->successful()) {
            return $response->json()['image']['src'];
        }

        Log::error('Error al subir imagen a Shopify', [
            'response' => $response->body()
        ]);
        return null;
    }

    public function syncFirstProductToShopify()
    {
        $producto = Producto::first(); // Obtener el primer producto
        $shopifyUrl = 'https://' . config('shopify.shop_url') . '/admin/api/' . config('shopify.api_version') . '/products';
        $accessToken = config('shopify.access_token');

        if (!$producto) {
            return response()->json(['message' => 'No hay productos en la base de datos'], 404);
        }

        // Obtener todos los productos de Shopify
        $shopifyProducts = Http::withHeaders([
            'X-Shopify-Access-Token' => $accessToken,
        ])->get($shopifyUrl . '.json')->json();

        // Buscar si el producto ya existe en Shopify por código de barras
        $existingProduct = null;
        foreach ($shopifyProducts['products'] as $shopifyProduct) {
            foreach ($shopifyProduct['variants'] as $variant) {
                if ($variant['sku'] == $producto->codigo_barras) {
                    $existingProduct = $shopifyProduct;
                    break 2; // Salir de ambos bucles si encontramos el producto
                }
            }
        }

        if ($existingProduct) {
            // Actualizar producto existente en Shopify
            $response = Http::withHeaders([
                'X-Shopify-Access-Token' => $accessToken,
                'Content-Type' => 'application/json',
            ])->put($shopifyUrl . '/' . $existingProduct['id'] . '.json', [
                'product' => [
                    'title' => $producto->nombre_producto,
                    'body_html' => $producto->descripcion,
                    'vendor' => $producto->id_familiar,
                    'product_type' => $producto->categoria,
                    'status' => 'draft',
                    'variants' => [
                        [
                            'id' => $existingProduct['variants'][0]['id'],
                            'price' => $producto->precio_publico,
                            'sku' => $producto->codigo_barras,
                            'inventory_quantity' => (int)$producto->cantidad,
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                $this->uploadImageToShopify($producto->img, $existingProduct['id'], $accessToken);
            }
        } else {
            // Crear nuevo producto en Shopify
            $response = Http::withHeaders([
                'X-Shopify-Access-Token' => $accessToken,
                'Content-Type' => 'application/json',
            ])->post($shopifyUrl . '.json', [
                'product' => [
                    'title' => $producto->nombre_producto,
                    'body_html' => $producto->descripcion,
                    'vendor' => $producto->id_familiar,
                    'product_type' => $producto->categoria,
                    'status' => 'draft',
                    'variants' => [
                        [
                            'price' => $producto->precio_publico,
                            'sku' => $producto->codigo_barras,
                            'inventory_quantity' => (int)$producto->cantidad,
                        ],
                    ],
                ],
            ]);

            if ($response->successful()) {
                $shopifyProductId = $response->json()['product']['id'];
                $this->uploadImageToShopify($producto->img, $shopifyProductId, $accessToken);
            }
        }

        if ($response->failed()) {
            Log::error('Error al enviar producto a Shopify', [
                'product_id' => $producto->id,
                'response' => $response->body()
            ]);
            return response()->json(['message' => 'Error al enviar producto a Shopify: ' . $response->body()], 500);
        }

        return response()->json(['message' => 'Producto enviado a Shopify']);
    }
}

