<?php

namespace App\Http\Requests\Package;

use App\Http\Requests\BaseRequest;
use App\Models\Package;
use Illuminate\Validation\Rule;

class PackageStoreRequest extends BaseRequest{
    public function prepareForValidation(){
        $this->mergeIfMissing( [ 'active' => 0, 'notes' => config('general.package.default_notes') ] );
        $package_type   = $this->packageable_type && !isset( Package::INSTANCE_MAP[$this->packageable_type])
                    ? ''
                    : utils()->plural( Package::INSTANCE_MAP[$this->packageable_type] );

        $this->merge([ 'package_type' => $package_type ]);
    }

    public function rules(){
        return [
            'active'            => [ 'required', 'in:0,1' ],
            'sort'              => [ 'sometimes', 'numeric', 'gte:0'],
            'name'              => [ 'required' ],
            'price'             => [ 'required', 'numeric', 'min:1,max:10000' ],
            'quantity'          => [ 'required', 'numeric', 'min:1,max:10000' ],
            'packageable_type'  => [ 'required', Rule::In( array_keys( Package::getPackageTypes() ) ) ],
            'packageable_id'    => [ 'required', "exists:{$this->package_type},id"]
        ];
    }

    protected function getResponseMessage(){
        return 'api/package.create.failed';
    }
}
