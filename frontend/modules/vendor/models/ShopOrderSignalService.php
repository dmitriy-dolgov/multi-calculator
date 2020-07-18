<?php

namespace frontend\modules\vendor\models;

class ShopOrderSignalService
{
    public function create(ProductCreateForm $form)
    {
        $product = Product::create(
            $form->code,
            $form->name,
            new Meta(
                $form->meta_title,
                $form->meta_description
            )
        );

        $product->changePrice($form->price_new, $form->price_old);

        $this->products->save($product);

        return $product->id;
    }
}
