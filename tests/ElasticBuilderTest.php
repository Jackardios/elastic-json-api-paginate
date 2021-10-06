<?php

namespace Jackardios\ElasticJsonApiPaginate\Tests;

use ElasticScoutDriverPlus\Support\Query;

class ElasticBuilderTest extends TestCase
{
    /** @test */
    public function it_can_paginate_records(): void
    {
        $paginator = TestModel::searchQuery(Query::matchAll())->jsonPaginate();

        $this->assertEquals('http://localhost?page%5Bnumber%5D=2', $paginator->nextPageUrl());
    }

    /** @test */
    public function it_returns_the_amount_of_records_specified_in_the_config_file(): void
    {
        config()->set('json-api-paginate.default_size', 10);

        $paginator = TestModel::searchQuery(Query::matchAll())->jsonPaginate();

        $this->assertCount(10, $paginator);
    }

    /** @test */
    public function it_can_return_the_specified_amount_of_records(): void
    {
        $paginator = TestModel::searchQuery(Query::matchAll())->jsonPaginate(15);

        $this->assertCount(15, $paginator);
    }

    /** @test */
    public function it_will_not_return_more_records_that_the_configured_maximum(): void
    {
        $paginator = TestModel::searchQuery(Query::matchAll())->jsonPaginate(15);

        $this->assertCount(15, $paginator);
    }

    /** @test */
    public function it_can_set_a_custom_base_url_in_the_config_file(): void
    {
        config()->set('json-api-paginate.base_url', 'https://example.com');

        $paginator = TestModel::searchQuery(Query::matchAll())->jsonPaginate();

        $this->assertEquals('https://example.com?page%5Bnumber%5D=2', $paginator->nextPageUrl());
    }
}
