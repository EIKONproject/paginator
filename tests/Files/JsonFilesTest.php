<?php

declare(strict_types=1);

require __DIR__ . "/../../src/Files/JsonFiles.php";

use PHPUnit\Framework\TestCase;
use EikonPaginator\Files;

final class JsonFilesTest extends TestCase
{
    public function test_JsonCheck_RightFields(): void
    {
        $json = array("field_1" => "value_1");
        $check_fields = array("field_1");
        $this->assertTrue(Files\check_fields_json($json, $check_fields));
    }

    public function test_JsonCheck_WrongFields(): void
    {
        // Check for wrong fields loaded
        $json = array("field_1" => "value_1");
        $check_fields = array("field_2");
        $this->expectException(Exception::class);
        Files\check_fields_json($json, $check_fields);
    }

    public function test_JsonCheck_RightAndWrongFields(): void
    {
        // Check for right and wrong fields loaded
        $json = array("field_1" => "value_1");
        $check_fields = array("field_1", "field_2");
        $this->expectException(Exception::class);
        Files\check_fields_json($json, $check_fields);
    }

    public function test_JsonLoad_Success(): void
    {
        // Prepare temporary input file
        $temp = tmpfile();
        fwrite($temp, '{"field_1":"value_1"}');
        $temp_path = stream_get_meta_data($temp)['uri'];

        // Prepare expected output
        $expected = array("field_1" => "value_1");

        // Check actual output
        $check_fields = array("field_1");
        $output = Files\load_json($temp_path, $check_fields);
        $this->assertSame($expected, $output);

        // Remove the temporary file
        fclose($temp);
    }

    public function test_JsonStore_Success(): void
    {
        // Prepare temporary expected file
        $temp = tmpfile();
        fwrite($temp, '{"field_1":"value_1"}');
        $expected_file = stream_get_meta_data($temp)['uri'];

        // Write json to the output
        $json = array("field_1" => "value_1");
        $output_file = dirname($expected_file) . "/output.json";
        Files\store_json($json, $output_file);

        // Check that the files are the same
        $this->assertSame(file_get_contents($expected_file), file_get_contents($output_file));

        // Remove the temporary files
        unlink($output_file);
        fclose($temp);
    }
}
