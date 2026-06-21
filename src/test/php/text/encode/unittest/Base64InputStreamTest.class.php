<?php namespace text\encode\unittest;

use io\streams\MemoryInputStream;
use test\{Assert, Test};
use text\encode\Base64InputStream;

class Base64InputStreamTest {

  #[Test]
  public function singleRead() {
    $stream= new Base64InputStream(new MemoryInputStream(base64_encode('Hello')));
    $chunk= $stream->read();
    $stream->close();
    Assert::equals('Hello', $chunk);
  }

  #[Test]
  public function multipleReads() {
    $stream= new Base64InputStream(new MemoryInputStream(base64_encode('Hello World')));
    $chunk1= $stream->read(5);
    $chunk2= $stream->read(1);
    $chunk3= $stream->read(5);
    $stream->close();
    Assert::equals('Hello', $chunk1);
    Assert::equals(' ', $chunk2);
    Assert::equals('World', $chunk3);
  }
}