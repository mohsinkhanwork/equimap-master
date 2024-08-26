<?php

namespace App\Http\Requests\Package;

use App\Http\Requests\BaseRequest;
use App\Models\Package;
use Illuminate\Validation\Rule;

class PackageIndexRequest extends BaseRequest{
    public function prepareForValidation(){
        $this->mergeIfMissing( [ 'type' => Package::getDefaultPackageType() ] );
    }

    public function rules(){
        return [
            'type'  => [ 'required', Rule::In( array_keys( Package::getPackageTypes() ) ) ]
        ];
    }

    protected function getResponseMessage(){
        return 'api/package.index.no_results';
    }
}
