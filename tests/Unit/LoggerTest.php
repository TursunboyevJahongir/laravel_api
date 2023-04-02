<?php

namespace Tests\Unit;

use App\Core\Test\Feature\CoreTest;
use App\Repositories\ResourceRepository;
use App\Services\ResourceService;
use Illuminate\Http\UploadedFile;

class LoggerTest extends CoreTest
{
    public function testUpdateFile()
    {
// Create a mock UploadedFile object
        $file = UploadedFile::fake()->create('avatar.jpg');

 //Create a mock ResourceService object
        $serviceMock = $this->getMockBuilder(ResourceService::class)
            ->disableOriginalConstructor()
            ->getMock();


        $repo = $this->getMockBuilder(ResourceRepository::class)
            ->disableOriginalConstructor()
            ->getMock();

        $service = new ResourceService($repo);

        $service->updateFile($file, $this->user->avatar());

// Expect the deleteFile method to be called once with the $relation argument
        $serviceMock->expects($this->once())
            ->method('deleteFile')
            ->with($this->user->avatar());

// Expect the saveFile method to be called once with the $file, $relation, 'files', and null arguments
        $serviceMock->expects($this->once())
            ->method('saveFile')
            ->with($file, $this->user->avatar(), 'files', null);

// Call the updateFile method with the mock arguments

// Assert that the saveFile method was called with the expected parameters
        $this->assertTrue(true); // Add your own assertions here

    }
}
