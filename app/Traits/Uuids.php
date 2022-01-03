<?php

namespace App\Traits;

use Illuminate\Support\Str;
use Ramsey\Uuid\Uuid;

/**
 *
 */
trait Uuids
{
    protected static function bootUuids()
    {
        static::creating( function ( $model ){
            $model->{$model->getKeyName()} = ( isset( $model->attribute['email'] ) ) ?
                    (string)( Uuid::uuid5( Uuid::NAMESPACE_DNS, $model->email ) ) :
                    (string)( Str::uuid() );
        } );
    }
}
