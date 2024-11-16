<?php

namespace Tests\Unit\BaseServices;

use App\Jobs\TestJob;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class QueueTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->artisan('horizon:continue-supervisor', ['name' => 'supervisor-test']);
    }


    protected function tearDown(): void
    {
        $this->artisan('horizon:pause-supervisor', ['name' => 'supervisor-test']);

        parent::tearDown();
    }

    private function getSize()
    {
        return Queue::connection('rabbitmq')->size('test');
    }

    public function test_send_message_to_rabbitmq()
    {
        $size = $this->getSize();

        TestJob::dispatch();

        $size2 = $this->getSize();

        $this->assertTrue($size === ($size2 - 1));
    }

    public function test_send_message_to_rabbitmq_and_it_got_by_horizon()
    {
        TestJob::dispatch();

        $size = $this->getSize();

        sleep(6);

        $size2 = $this->getSize();

        $this->assertTrue($size >= $size2);
    }
}
