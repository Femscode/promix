<?php

namespace App\Exports\Common\Sheets;

use App\Abstracts\Export;
use App\Http\Requests\Common\Item as Request;
use App\Models\Common\Item as Model;

class Items extends Export
{
    public $request_class = Request::class;

    public function collection()
    {
        return Model::with('category')->collectForExport($this->ids);
    }

    public function map($model): array
    {
        $model->category_name = $model->category->name;

        return parent::map($model);
    }

    public function fields(): array
    {
        return [
            'name',
            'type',
            'description',
            'sale_price',
            'quantity',
            'purchase_price',
            'category_name',
            'enabled',
        ];
    }
}
