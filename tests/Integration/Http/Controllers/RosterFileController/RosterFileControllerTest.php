<?php

namespace Http\Controllers\RosterFileController;

use App\Models\Activity;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class RosterFileControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case CCNX file uploaded
     * @expectation parsed data stored in database
     * @test
     */
    public function parse_fileParsed()
    {
        // GIVEN
        Storage::fake('uploads');
        $file = new UploadedFile(__DIR__ . '/Roster - CrewConnex.html', 'Roster - CrewConnex.html', null, null, true );

        // WHEN
        $response = $this->postJson(route('parse-file'), [
            'file' => $file,
            'source_type' => 'CCNX'
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        // THEN
        $response->assertStatus(200);
        $rowsCreated = Activity::count();
        $this->assertEquals(48, Activity::count());
    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case file not sent
     * @expectation error status returned
     * @test
     */
    public function parse_missing_file()
    {
        // GIVEN

        // WHEN
        $response = $this->postJson(route('parse-file'), ['source_type' => 'CCNX'], ['X-CSRF-TOKEN' => csrf_token()]);

        // THEN
        $response->assertStatus(422);
    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case file source type not defined
     * @expectation error status returned
     * @test
     */
    public function parse_missing_source_type()
    {
        // GIVEN
        $file = new UploadedFile(__DIR__ . '/Roster - CrewConnex.html', 'Roster - CrewConnex.html', null, null, true );

        // WHEN
        $response = $this->postJson(route('parse-file'), ['file' => $file], ['X-CSRF-TOKEN' => csrf_token()]);

        // THEN
        $response->assertStatus(422);
    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case file source type unknown
     * @expectation error status returned
     * @test
     */
    public function parse_unknown_source_type()
    {
        // GIVEN
        $file = new UploadedFile(__DIR__ . '/Roster - CrewConnex.html', 'Roster - CrewConnex.html', null, null, true );

        // WHEN
        $response = $this->postJson(route('parse-file'), [
            'file' => $file,
            'source_type' => 'Unknown'
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        // THEN
        $response->assertStatus(422);
    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case Someother file source type uploaded
     * @expectation error status returned with message NOT IMPLEMENTED
     * @test
     */
    public function parse_source_otherType()
    {
        // GIVEN
        $file = new UploadedFile(__DIR__ . '/Roster - CrewConnex.html', 'Roster - CrewConnex.html', null, null, true );

        // WHEN
        $response = $this->postJson(route('parse-file'), [
            'file' => $file,
            'source_type' => 'SomeOther'
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        // THEN
        $response->assertStatus(500);
        $response->assertJson(['message' => 'NOT IMPLEMENTED']);
    }

    /**
     * @Feature parse roster data
     * @scenario html document
     * @case unknown extension of uploaded file type
     * @expectation error status returned
     * @test
     */
    public function parse_unknown_file_type()
    {
        // GIVEN
        $file = new UploadedFile(__DIR__ . '/unknown-file.xtx', '/unknown-file.xtx', null, null, true );

        // WHEN
        $response = $this->postJson(route('parse-file'), [
            'file' => $file,
            'source_type' => 'CCNX'
        ], ['X-CSRF-TOKEN' => csrf_token()]);

        // THEN
        $response->assertStatus(500);
        $response->assertJson(['message' => 'Unsupported file type for CCNX source']);
    }
}
