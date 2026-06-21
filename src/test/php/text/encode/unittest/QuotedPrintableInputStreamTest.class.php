<?php namespace text\encode\unittest;

use io\OperationFailed;
use io\streams\{InputStream, MemoryInputStream};
use test\{Assert, Expect, Test};
use text\encode\QuotedPrintableInputStream;

class QuotedPrintableInputStreamTest {

  #[Test]
  public function singleRead() {
    $stream= new QuotedPrintableInputStream(new MemoryInputStream('Hello'));
    $chunk= $stream->read();
    $stream->close();
    Assert::equals('Hello', $chunk);
  }

  #[Test]
  public function multipleReads() {
    $stream= new QuotedPrintableInputStream(new MemoryInputStream('Hello World'));
    $chunk1= $stream->read(5);
    $chunk2= $stream->read(1);
    $chunk3= $stream->read(5);
    $stream->close();
    Assert::equals('Hello', $chunk1);
    Assert::equals(' ', $chunk2);
    Assert::equals('World', $chunk3);
  }

  #[Test]
  public function umlaut() {
    $stream= new QuotedPrintableInputStream(new MemoryInputStream('=DCbercoder'));
    $chunk= $stream->read();
    $stream->close();
    Assert::equals("\xdcbercoder", $chunk);
  }

  #[Test]
  public function space() {
    $stream= new QuotedPrintableInputStream(new MemoryInputStream('Space between'));
    $chunk= $stream->read();
    $stream->close();
    Assert::equals('Space between', $chunk);
  }

  #[Test]
  public function encodedSpace() {
    $stream= new QuotedPrintableInputStream(new MemoryInputStream('Space=20between'));
    $chunk= $stream->read();
    $stream->close();
    Assert::equals('Space between', $chunk);
  }

  #[Test]
  public function tab() {
    $stream= new QuotedPrintableInputStream(new MemoryInputStream("Tab\tbetween"));
    $chunk= $stream->read();
    $stream->close();
    Assert::equals("Tab\tbetween", $chunk);
  }

  #[Test]
  public function encodedTab() {
    $stream= new QuotedPrintableInputStream(new MemoryInputStream('Tab=09between'));
    $chunk= $stream->read();
    $stream->close();
    Assert::equals("Tab\tbetween", $chunk);
  }

  #[Test]
  public function softLineBreak() {
    $stream= new QuotedPrintableInputStream(new MemoryInputStream(str_repeat('1', 75)."=\n".str_repeat('2', 75)));
    $chunk= $stream->read(150);
    $stream->close();
    Assert::equals(str_repeat('1', 75).str_repeat('2', 75), $chunk);
  }

  #[Test]
  public function spaceAtEnd() {
    $stream= new QuotedPrintableInputStream(new MemoryInputStream('Hello '));
    $chunk= $stream->read();
    $stream->close();
    Assert::equals('Hello ', $chunk);
  }

  #[Test]
  public function chunkedRead() {
    $expected= "Hello \xdcbercoder & World";
    $stream= new QuotedPrintableInputStream(new class() implements InputStream {
      private $chunks= ['Hello =', 'DCbercoder=', "\n", ' & World'];
      
      public function read($limit= 8192) {
        return array_shift($this->chunks);
      }
      
      public function available() {
        return sizeof($this->chunks) > 0 ? 1 : 0;
      }
      
      public function close() {
        $this->chunks= [];
      }
    });
    $chunk= $stream->read(strlen($expected));
    $stream->close();
    Assert::equals($expected, $chunk);
  }

  #[Test]
  public function equalsSign() {
    $stream= new QuotedPrintableInputStream(new MemoryInputStream('A=3D1'));
    $chunk= $stream->read();
    $stream->close();
    Assert::equals('A=1', $chunk);
  }

  #[Test]
  public function lowerCaseEscapeSequence() {
    $stream= new QuotedPrintableInputStream(new MemoryInputStream('=3d'));
    $chunk= $stream->read();
    $stream->close();
    Assert::equals('=', $chunk);
  }
  
  #[Test, Expect(OperationFailed::class)]
  public function invalidByteSequence() {
    (new QuotedPrintableInputStream(new MemoryInputStream('Hell=XX')))->read();
  }
}