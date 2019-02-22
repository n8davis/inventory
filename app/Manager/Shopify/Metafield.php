<?php
/**
 * Created by PhpStorm.
 * User: work
 * Date: 10/11/18
 * Time: 3:06 PM
 */

namespace App\Manager\Shopify;


class Metafield extends AbstractMetafield
{

    public function getSingularName()
    {
        return 'metafield';
    }

    public function getPluralName()
    {
        return 'metafields';
    }

}