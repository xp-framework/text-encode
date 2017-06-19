<?php namespace text\encode;

use io\streams\OutputStream;
use lang\Type;

/**
 * OuputStream that encodes data to base64 encoding
 *
 * @see      rfc://2045 section 6.8 
 * @test     xp://net.xp_framework.unittest.text.encode.Base64OutputStreamTest
 */
class HHVMBase64OutputStream implements OutputStream {
  const TABLE = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/';
  const PAD = '=';

  private $out, $remain;
  private $pos= 0;
  
  /**
   * Constructor
   *
   * @param   io.streams.OutputStream out
   * @param   int lineLength limit maximum line length
   */
  public function __construct(OutputStream $out, $lineLength= 0) {
    $this->out= $out;
    $this->remain= '';
    if ($lineLength) {
      $pos= 0;
      $this->write= function($bytes) use(&$pos, $lineLength) {
        $l= strlen($bytes);
        $o= 0;
        while ($l - $o > $lineLength - $pos) {
          $this->out->write(substr($bytes, $o, $lineLength - $pos)."\n");
          $pos= 0;
          $o+= $lineLength;
        }
        $this->out->write(substr($bytes, $o));
        $pos= $l - $o;
      };
    } else {
      $this->write= Type::forName('function(var): var')->cast([$this->out, 'write']);
    }
  }

  /**
   * Write a string
   *
   * @param   var arg
   */
  public function write($arg) {
    $input= $this->remain.$arg;

    $limit= strlen($input) - 2;
    $encoded= '';
    $offset= 0;
    while ($offset < $limit) {
      $encoded.= 
        self::TABLE[ord($input{$offset}) >> 2].
        self::TABLE[((ord($input{$offset}) & 0x03) << 4) + (ord($input{$offset + 1}) >> 4)].
        self::TABLE[((ord($input{$offset + 1}) & 0x0f) << 2) + (ord($input{$offset + 2}) >> 6)].
        self::TABLE[ord($input{$offset + 2}) & 0x3f]
      ;
      $offset+= 3;
    }

    $this->write->__invoke($encoded);
    $this->remain= substr($input, $offset);
  }

  /**
   * Flush this buffer
   *
   */
  public function flush() {
    $this->out->flush();
  }

  /**
   * Close this buffer. Flushes this buffer and then calls the close()
   * method on the underlying OuputStream.
   *
   */
  public function close() {
    switch (strlen($this->remain)) {
      case 1:
        $this->write->__invoke(
          self::TABLE[ord($this->remain{0}) >> 2].
          self::TABLE[(ord($this->remain{0}) & 0x03) << 4].
          self::PAD.
          self::PAD
        );
        break;
      case 2:
        $this->write->__invoke(
          self::TABLE[ord($this->remain{0}) >> 2].
          self::TABLE[((ord($this->remain{0}) & 0x03) << 4) + (ord($this->remain{1}) >> 4)].
          self::TABLE[((ord($this->remain{1}) & 0x0f) << 2)].
          self::PAD
        );
        break;
    }
    $this->out->close();
    $this->remain= '';
  }

  /**
   * Destructor. Ensures output stream is closed.
   *
   */
  public function __destruct() {
    $this->out->close();
  }
}
