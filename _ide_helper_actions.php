<?php

namespace App\Actions\Product;

/**
 * @method static \Lorisleiva\Actions\Decorators\JobDecorator|\Lorisleiva\Actions\Decorators\UniqueJobDecorator makeJob(\App\Http\Requests\Product\StoreProductRequest $request)
 * @method static \Lorisleiva\Actions\Decorators\UniqueJobDecorator makeUniqueJob(\App\Http\Requests\Product\StoreProductRequest $request)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch dispatch(\App\Http\Requests\Product\StoreProductRequest $request)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchIf(bool $boolean, \App\Http\Requests\Product\StoreProductRequest $request)
 * @method static \Illuminate\Foundation\Bus\PendingDispatch|\Illuminate\Support\Fluent dispatchUnless(bool $boolean, \App\Http\Requests\Product\StoreProductRequest $request)
 * @method static dispatchSync(\App\Http\Requests\Product\StoreProductRequest $request)
 * @method static dispatchNow(\App\Http\Requests\Product\StoreProductRequest $request)
 * @method static dispatchAfterResponse(\App\Http\Requests\Product\StoreProductRequest $request)
 * @method static mixed run(\App\Http\Requests\Product\StoreProductRequest $request)
 */
class CreateProduct
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