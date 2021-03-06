<?php

namespace Jackardios\ElasticJsonApiPaginate\Tests;

class RequestTest extends TestCase
{
    /** @test */
    public function it_will_discover_the_page_size_parameter(): void
    {
        $response = $this->get('/?page[size]=2');

        $response->assertJsonFragment(['per_page' => 2]);
    }

    /** @test */
    public function it_will_discover_the_page_number_parameter(): void
    {
        $response = $this->get('/?page[number]=2');

        $response->assertJsonFragment(['current_page' => 2]);
    }

    /** @test */
    public function it_will_use_the_default_page_size(): void
    {
        $response = $this->get('/');

        $response->assertJsonFragment(['per_page' => 30]);
    }

    /** @test */
    public function it_will_use_the_configured_page_size_parameter(): void
    {
        config(['json-api-paginate.size_parameter' => 'modified_size']);

        $response = $this->get('/?page[modified_size]=2');

        $response->assertJsonFragment(['per_page' => 2]);
    }

    /** @test */
    public function it_will_use_the_configured_page_number_parameter(): void
    {
        config(['json-api-paginate.number_parameter' => 'modified_number']);

        $response = $this->get('/?page[modified_number]=2');

        $response->assertJsonFragment(['current_page' => 2]);
    }
}
