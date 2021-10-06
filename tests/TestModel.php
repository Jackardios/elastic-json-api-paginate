<?php

namespace Jackardios\ElasticJsonApiPaginate\Tests;

use ElasticScoutDriverPlus\Searchable;
use Illuminate\Database\Eloquent\Model;

class TestModel extends Model
{
    use Searchable;

    protected $guarded = [];
}
