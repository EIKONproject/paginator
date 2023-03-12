<?php

declare(strict_types=1);

require __DIR__ . "/../src/Files/UtilsFiles.php";

use PHPUnit\Framework\TestCase;
use EikonPaginator\Files;

final class FileUtilsTest extends TestCase
{
    public function testJsonCanBeLoaded(): void
    {
        // Prepare temporary input file
        $temp = tmpfile();
        fwrite($temp, '{"field_1": "value_1"}');
        // Get file location
        $temp_path = stream_get_meta_data($temp)['uri'];
        $temp_dir = dirname($temp_path);
        $temp_fname = basename($temp_path);
        // Prepare expected output
        $expected = array("field_1" => "value_1");
        // Check actual output
        $check_fields = array("field_1");
        $output = Files\load_json($temp_dir, $temp_fname, $check_fields);
        $this->assertSame($expected, $output);
        // Remove the temporary file
        fclose($temp);
    }

    public function testJsonCheckWrongFieldsError(): void
    {
        // Prepare temporary input file
        $temp = tmpfile();
        fwrite($temp, '{"field_1": "value_1"}');
        // Get file location
        $temp_path = stream_get_meta_data($temp)['uri'];
        $temp_dir = dirname($temp_path);
        $temp_fname = basename($temp_path);
        // Check for wrong fields loaded
        $this->expectException(Exception::class);
        $check_fields = array("field_2");
        Files\load_json($temp_dir, $temp_fname, $check_fields);
        // Remove the temporary file
        fclose($temp);
    }

    public function testJsonCheckRightWrongFieldsError(): void
    {
        // Prepare temporary input file
        $temp = tmpfile();
        fwrite($temp, '{"field_1": "value_1"}');
        // Get file location
        $temp_path = stream_get_meta_data($temp)['uri'];
        $temp_dir = dirname($temp_path);
        $temp_fname = basename($temp_path);
        // Check for right and wrong fields loaded
        $this->expectException(Exception::class);
        $check_fields = array("field_1", "field_2");
        Files\load_json($temp_dir, $temp_fname, $check_fields);
        // Remove the temporary file
        fclose($temp);
    }
}
