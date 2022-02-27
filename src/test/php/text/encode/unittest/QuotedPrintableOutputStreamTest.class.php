<?php namespace text\encode\unittest;

use io\streams\MemoryOutputStream;
use text\encode\QuotedPrintableOutputStream;
use unittest\{Test, TestCase};


/**
 * Test QuotedPrintable encoder
 *
 * @see   xp://text.encode.QuotedPrintableOutputStream
 */
class QuotedPrintableOutputStreamTest extends TestCase {

  /**
   * Test single write
   *
   */
  #[Test]
  public function singleWrite() {
    $out= new MemoryOutputStream();
    $stream= new QuotedPrintableOutputStream($out);
    $stream->write('Hello');
    $stream->close();
    $this->assertEquals('Hello', $out->bytes());
  }

  /**
   * Test multiple consecutive writes
   *
   */
  #[Test]
  public function multipeWrites() {
    $out= new MemoryOutputStream();
    $stream= new QuotedPrintableOutputStream($out);
    $stream->write('Hello');
    $stream->write(' ');
    $stream->write('World');
    $stream->close();
    $this->assertEquals('Hello World', $out->bytes());
  }

  /**
   * Test encoding an umlaut
   *
   */
  #[Test]
  public function umlaut() {
    $out= new MemoryOutputStream();
    $stream= new QuotedPrintableOutputStream($out);
    $stream->write("Hello \xdcbercoder");
    $stream->close();
    $this->assertEquals('Hello =DCbercoder', $out->bytes());
  }

  /**
   * Test encoding an umlaut
   *
   */
  #[Test]
  public function umlautAtTheBeginning() {
    $out= new MemoryOutputStream();
    $stream= new QuotedPrintableOutputStream($out);
    $stream->write("\xdcbercoder");
    $stream->close();
    $this->assertEquals('=DCbercoder', $out->bytes());
  }

  /**
   * Test encoding lines 150 bytes of data should end up in two lines.
   *
   */
  #[Test]
  public function lineLengthMayNotBeLongerThan76Characters() {
    $out= new MemoryOutputStream();
    $stream= new QuotedPrintableOutputStream($out);
    $stream->write(str_repeat('1', 75));
    $stream->write(str_repeat('2', 75));
    $stream->close();
    $this->assertEquals(str_repeat('1', 75)."=\n".str_repeat('2', 75), $out->bytes());
  }

  /**
   * Test end of data
   *
   */
  #[Test]
  public function spaceAtEndOfMustBeEncoded() {
    $out= new MemoryOutputStream();
    $stream= new QuotedPrintableOutputStream($out);
    $stream->write('Hello ');
    $stream->close();
    $this->assertEquals('Hello=20', $out->bytes());
  }

  /**
   * Test decoding an equals sign
   *
   */
  #[Test]
  public function equalsSign() {
    $out= new MemoryOutputStream();
    $stream= new QuotedPrintableOutputStream($out);
    $stream->write('A=1');
    $stream->close();
    $this->assertEquals('A=3D1', $out->bytes());
  }
}