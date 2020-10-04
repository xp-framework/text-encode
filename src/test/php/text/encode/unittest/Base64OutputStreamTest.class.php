<?php namespace text\encode\unittest;

use io\streams\MemoryOutputStream;
use text\encode\Base64OutputStream;
use unittest\actions\VerifyThat;
use unittest\{Test, TestCase};

/**
 * Test base64 encoder
 *
 * @see   xp://text.encode.Base64OutputStream
 */
#[Action(eval: 'new VerifyThat(fn() => in_array("convert.*", stream_get_filters()))')]
class Base64OutputStreamTest extends TestCase {

  #[Test]
  public function singleWrite() {
    $out= new MemoryOutputStream();
    $stream= new Base64OutputStream($out);
    $stream->write('Hello');
    $stream->close();
    $this->assertEquals(base64_encode('Hello'), $out->getBytes());
  }

  #[Test]
  public function lineWrappedAt76Characters() {
    $data= str_repeat('1', 75).str_repeat('2', 75);
    $out= new MemoryOutputStream();
    $stream= new Base64OutputStream($out, 76);
    $stream->write($data);
    $stream->close();
    $this->assertEquals(rtrim(chunk_split(base64_encode($data), 76, "\n"), "\n"), $out->getBytes());
  }

  #[Test]
  public function multipeWrites() {
    $out= new MemoryOutputStream();
    $stream= new Base64OutputStream($out);
    $stream->write('Hello');
    $stream->write(' ');
    $stream->write('World');
    $stream->close();
    $this->assertEquals(base64_encode('Hello World'), $out->getBytes());
  }
}