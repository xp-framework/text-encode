<?php namespace text\encode\unittest;

use io\streams\MemoryOutputStream;
use test\{Assert, Test};
use text\encode\QuotedPrintableOutputStream;

class QuotedPrintableOutputStreamTest {

  #[Test]
  public function singleWrite() {
    $out= new MemoryOutputStream();
    $stream= new QuotedPrintableOutputStream($out);
    $stream->write('Hello');
    $stream->close();
    Assert::equals('Hello', $out->bytes());
  }

  #[Test]
  public function multipeWrites() {
    $out= new MemoryOutputStream();
    $stream= new QuotedPrintableOutputStream($out);
    $stream->write('Hello');
    $stream->write(' ');
    $stream->write('World');
    $stream->close();
    Assert::equals('Hello World', $out->bytes());
  }

  #[Test]
  public function umlaut() {
    $out= new MemoryOutputStream();
    $stream= new QuotedPrintableOutputStream($out);
    $stream->write("Hello \xdcbercoder");
    $stream->close();
    Assert::equals('Hello =DCbercoder', $out->bytes());
  }

  #[Test]
  public function umlautAtTheBeginning() {
    $out= new MemoryOutputStream();
    $stream= new QuotedPrintableOutputStream($out);
    $stream->write("\xdcbercoder");
    $stream->close();
    Assert::equals('=DCbercoder', $out->bytes());
  }

  #[Test]
  public function lineLengthMayNotBeLongerThan76Characters() {
    $out= new MemoryOutputStream();
    $stream= new QuotedPrintableOutputStream($out);
    $stream->write(str_repeat('1', 75));
    $stream->write(str_repeat('2', 75));
    $stream->close();
    Assert::equals(str_repeat('1', 75)."=\n".str_repeat('2', 75), $out->bytes());
  }

  #[Test]
  public function spaceAtEndOfMustBeEncoded() {
    $out= new MemoryOutputStream();
    $stream= new QuotedPrintableOutputStream($out);
    $stream->write('Hello ');
    $stream->close();
    Assert::equals('Hello=20', $out->bytes());
  }

  #[Test]
  public function equalsSign() {
    $out= new MemoryOutputStream();
    $stream= new QuotedPrintableOutputStream($out);
    $stream->write('A=1');
    $stream->close();
    Assert::equals('A=3D1', $out->bytes());
  }
}