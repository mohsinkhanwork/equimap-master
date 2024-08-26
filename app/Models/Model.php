<?php


namespace App\Models;

use App\Helpers\Response;

class Model extends \Illuminate\Database\Eloquent\Model {
    /**
     * @param array $params Parameters used to create database record.
     * @param string $messageSuccess Return success message from language file if record is created.
     * @param string $messageFailed Return failure message from language file if record is created.
     * @param string $method Defines if we need to use create or updateOrCreate method.
     * @param array $search This parameter is used if method is updateOrCreate.
     * @return Response
     */
    public function createAndReturn( $params, $messageSuccess='', $messageFailed='', $method='create', $search=[] ){
        $messageSuccess     = $messageSuccess != '' ? $messageSuccess : 'api/general.record_created';
        $messageFailed      = $messageFailed != ''  ? $messageFailed  : 'api/general.record_failed';

        $generatedInstance = $this->$method( $params, $search );
        if( $generatedInstance->id > 0 ){
            return utils()->response()->status('created')->items($generatedInstance)->submit( $messageSuccess );
        }

        return utils()->response()->error( $messageFailed );
    }

    public function createOrUpdateAndReturn( $search, $params, $messageSuccess='', $messageFailed='' ){
        return $this->createAndReturn( $params, $messageSuccess, $messageFailed, 'updateOrCreate', $search );
    }

    /**
     * @param $morphName
     * @param array $params Parameters used to create database record.
     * @param string $messageSuccess Return success message from language file if record is created.
     * @param string $messageFailed Return failure message from language file if record is created.
     * @return Response
     */
    public function createMorphAndReturn( $morphName, $params, $messageSuccess='', $messageFailed='' ){
        $morphed        = $this->$morphName();
        $morphedModel   = $morphed->getRelated();
        $filteredParams = collect( $params )->only( $morphedModel->getFillable() );
        $filteredParams = $filteredParams->merge([
            $this->$morphName()->getMorphType()     => $this->getMorphClass(),
            $this->$morphName()->getForeignKeyName()=> $this->getKey(),
        ])->toArray();

        return $morphedModel->createAndReturn( $filteredParams, $messageSuccess, $messageFailed );
    }
}
