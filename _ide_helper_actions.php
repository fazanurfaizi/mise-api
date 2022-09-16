<?php

namespace App\Actions\ProductSku;

/**
 * @method static \Lorisleiva\Actions\Decorators\JobDecorator|\Lorisleiva\Actions\Decorators\UniqueJobDecorator makeJob(\Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static \Lorisleiva\Actions\Decorators\UniqueJobDecorator makeUniqueJob(\Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch dispatch(\Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchIf(bool $boolean, \Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchUnless(bool $boolean, \Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static dispatchSync(\Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static dispatchNow(\Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static dispatchAfterResponse(\Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static \App\Models\Product\Product run(\Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 */
class CreateProductSku
{
}
namespace App\Actions\Product;

/**
 * @method static \Lorisleiva\Actions\Decorators\JobDecorator|\Lorisleiva\Actions\Decorators\UniqueJobDecorator makeJob(\Illuminate\Http\Request $request)
 * @method static \Lorisleiva\Actions\Decorators\UniqueJobDecorator makeUniqueJob(\Illuminate\Http\Request $request)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch dispatch(\Illuminate\Http\Request $request)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchIf(bool $boolean, \Illuminate\Http\Request $request)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchUnless(bool $boolean, \Illuminate\Http\Request $request)
 * @method static dispatchSync(\Illuminate\Http\Request $request)
 * @method static dispatchNow(\Illuminate\Http\Request $request)
 * @method static dispatchAfterResponse(\Illuminate\Http\Request $request)
 * @method static mixed run(\Illuminate\Http\Request $request)
 */
class CreateProduct
{
}
/**
 * @method static \Lorisleiva\Actions\Decorators\JobDecorator|\Lorisleiva\Actions\Decorators\UniqueJobDecorator makeJob(\Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static \Lorisleiva\Actions\Decorators\UniqueJobDecorator makeUniqueJob(\Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch dispatch(\Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchIf(bool $boolean, \Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchUnless(bool $boolean, \Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static dispatchSync(\Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static dispatchNow(\Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static dispatchAfterResponse(\Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 * @method static \App\Models\Product\Product run(\Illuminate\Http\Request $request, \App\Models\Product\Product $product)
 */
class UpdateProduct
{
}
namespace Lorisleiva\Actions\Concerns;

/**
 * @method void asController()
 */
trait AsController
{
}
/**
 * @method void asListener()
 */
trait AsListener
{
}
/**
 * @method void asJob()
 */
trait AsJob
{
}
/**
 * @method void asCommand(\Illuminate\Console\Command $command)
 */
trait AsCommand
{
}