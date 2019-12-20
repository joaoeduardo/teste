<?php

namespace Tests\Feature;

use App\Models\Product;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Arr;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test the first page of list.
     *
     * @return void
     */
    public function testFirstPageList()
    {
        factory(Product::class, 15)->create();

        $this
            ->get('api/products')
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'free_shipping',
                            'description',
                            'price',
                            'created_at',
                            'updated_at'
                        ],
                        'links' => [
                            'self'
                        ]
                    ]
                ],
                'meta' => [
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages'
                    ]
                ],
                'links' => [
                    'self',
                    'first',
                    'next',
                    'last'
                ]
            ]);
    }

    /**
     * Test the second page of list.
     *
     * @return void
     */
    public function testSecondPageList()
    {
        factory(Product::class, 15)->create();

        $this
            ->get('api/products?page[number]=2')
            ->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'free_shipping',
                            'description',
                            'price',
                            'created_at',
                            'updated_at'
                        ],
                        'links' => [
                            'self'
                        ]
                    ]
                ],
                'meta' => [
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages'
                    ]
                ],
                'links' => [
                    'self',
                    'first',
                    'prev',
                    'last'
                ]
            ]);
    }

    /**
     * Test a list with different size.
     *
     * @return void
     */
    public function testDifferentSizeList()
    {
        factory(Product::class, 15)->create();

        $this
            ->get('api/products?page[size]=5')
            ->assertStatus(200)
            ->assertJsonCount(5, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'free_shipping',
                            'description',
                            'price',
                            'created_at',
                            'updated_at'
                        ],
                        'links' => [
                            'self'
                        ]
                    ]
                ],
                'meta' => [
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages'
                    ]
                ],
                'links' => [
                    'self',
                    'first',
                    'next',
                    'last'
                ]
            ]);
    }

    /**
     * Test sorting list asc.
     *
     * @return void
     */
    public function testAscSorting()
    {
        /** @var \Illuminate\Database\Eloquent\Collection|Product[] $products */
        $products = factory(Product::class, 10)->create()->sortBy('name')->values();

        $res = $this
            ->get('api/products?sort=name')
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'free_shipping',
                            'description',
                            'price',
                            'created_at',
                            'updated_at'
                        ],
                        'links' => [
                            'self'
                        ]
                    ]
                ],
                'meta' => [
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages'
                    ]
                ],
                'links' => [
                    'self',
                    'first',
                    'last'
                ]
            ])->baseResponse;

        $payload = json_decode($res->content(), true)['data'];

        $products->each(function (Product $product, $i) use ($payload) {
            $this->assertEquals($product->name, $payload[$i]['attributes']['name']);
        });
    }

    /**
     * Test sorting list desc.
     *
     * @return void
     */
    public function testDescSorting()
    {
        /** @var \Illuminate\Database\Eloquent\Collection|Product[] $products */
        $products = factory(Product::class, 10)->create()->sortByDesc('name')->values();

        $res = $this
            ->get('api/products?sort=-name')
            ->assertStatus(200)
            ->assertJsonCount(10, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'free_shipping',
                            'description',
                            'price',
                            'created_at',
                            'updated_at'
                        ],
                        'links' => [
                            'self'
                        ]
                    ]
                ],
                'meta' => [
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages'
                    ]
                ],
                'links' => [
                    'self',
                    'first',
                    'last'
                ]
            ])->baseResponse;

        $payload = json_decode($res->content(), true)['data'];

        $products->each(function (Product $product, $i) use ($payload) {
            $this->assertEquals($product->name, $payload[$i]['attributes']['name']);
        });
    }

    /**
     * Test filter.
     *
     * @return void
     */
    public function testFilter()
    {
        $letters = range('a', 'j');

        /** @var \Illuminate\Database\Eloquent\Collection|Product[] $products */
        $products = factory(Product::class, 10)->make();

        $products->each(function (Product $product, $i) use ($letters) {
            $product->name = $letters[$i];

            $product->save();
        });

        $res = $this
            ->get('api/products?filter[name]=a')
            ->assertStatus(200)
            ->assertJsonCount(1, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => [
                        'type',
                        'id',
                        'attributes' => [
                            'name',
                            'free_shipping',
                            'description',
                            'price',
                            'created_at',
                            'updated_at'
                        ],
                        'links' => [
                            'self'
                        ]
                    ]
                ],
                'meta' => [
                    'pagination' => [
                        'total',
                        'count',
                        'per_page',
                        'current_page',
                        'total_pages'
                    ]
                ],
                'links' => [
                    'self',
                    'first',
                    'last'
                ]
            ])->baseResponse;

        $payload = json_decode($res->content(), true)['data'];

        $this->assertEquals($products[0]->name, $payload[0]['attributes']['name']);
    }

    /**
     * Test find a entity by id.
     *
     * @return void
     */
    public function testFind()
    {
        factory(Product::class)->create();

        $this
            ->get('api/products/1')
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'free_shipping',
                        'description',
                        'price',
                        'created_at',
                        'updated_at'
                    ],
                    'links' => [
                        'self'
                    ]
                ],
            ]);
    }

    /**
     * Test update a entity.
     *
     * @return void
     */
    public function testUpdate()
    {
        /** @var Product $product */
        $product = factory(Product::class)->create();

        $json = [
            'data' => [
                'type' => 'product',
                'attributes' => [
                    'name'          => 'teste',
                    'free_shipping' => false,
                    'description'   => 'teste',
                    'price'         => 100
                ]
            ]
        ];

        $this
            ->putJson('/api/products/1', $json)
            ->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'type',
                    'id',
                    'attributes' => [
                        'name',
                        'free_shipping',
                        'description',
                        'price',
                        'created_at',
                        'updated_at'
                    ],
                    'links' => [
                        'self'
                    ]
                ]
            ]);

        $this->assertDatabaseHas('products', Arr::get($json, 'data.attributes'));
    }

    /**
     * Test delete a entity.
     *
     * @return void
     */
    public function testDelete()
    {
        /** @var Product $product */
        factory(Product::class)->create();

        $this
            ->delete('api/products/1')
            ->assertStatus(204);

        $this->assertDatabaseMissing('products', ['id' => 1]);
    }
}
