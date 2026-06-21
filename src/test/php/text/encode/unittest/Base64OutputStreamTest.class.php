<?php namespace text\encode\unittest;

use io\streams\MemoryOutputStream;
use test\{Assert, Test};
use text\encode\Base64OutputStream;

class Base64OutputStreamTest {

  #[Test]
  public function singleWrite() {
    $out= new MemoryOutputStream();
    $stream= new Base64OutputStream($out);
    $stream->write('Hello');
    $stream->close();
    Assert::equals(base64_encode('Hello'), $out->bytes());
  }

  #[Test]
  public function lineWrappedAt76Characters() {
    $data= str_repeat('1', 75).str_repeat('2', 75);
    $out= new MemoryOutputStream();
    $stream= new Base64OutputStream($out, 76);
    $stream->write($data);
    $stream->close();
    Assert::equals(rtrim(chunk_split(base64_encode($data), 76, "\n"), "\n"), $out->bytes());
  }

  #[Test]
  public function multipeWrites() {
    $out= new MemoryOutputStream();
    $stream= new Base64OutputStream($out);
    $stream->write('Hello');
    $stream->write(' ');
    $stream->write('World');
    $stream->close();
    Assert::equals(base64_encode('Hello World'), $out->bytes());
  }
}