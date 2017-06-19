<?php namespace text\encode;

use io\streams\InputStream;

/**
 * InputStream that decodes from a QuotedPrintable-encoded source 
 *
 * Note this does not use the convert.quoted-printable-decode
 * stream filter provided along with PHP as that is buggy.
 *
 * @see      http://bugs.php.net/bug.php?id=50363
 * @see      http://bugs.horde.org/ticket/8747
 * @see      rfc://2045 section 6.7
 * @test     xp://net.xp_framework.unittest.text.encode.QuotedPrintableInputStreamTest
 */
class QuotedPrintableInputStream implements InputStream {
  protected $in = null;
  protected $buffer= '';
  
  /**
   * Constructor
   *
   * @param   io.streams.InputStream in
   */
  public function __construct(InputStream $in) {
    $this->in= $in;
  }

  /**
   * Read a string
   *
   * @param   int limit default 8192
   * @return  string
   */
  public function read($limit= 8192) {
    while ($this->in->available() > 0 && strlen($this->buffer) < $limit) {
      $read= $this->in->read($limit);
      $o= 0;
      $s= strlen($read);
      while ($o < $s) {
        $p= strcspn($read, '=', $o);
        $this->buffer.= substr($read, $o, $p);
        if (($o+= $p+ 1) <= $s) {
          while ($this->in->available() > 0 && $o > $s- 2) {
            $read.= $this->in->read(2);
            $s= strlen($read);
          }
          if ("\n" === $read{$o}) {
            $o+= 1;
          } else {
            if (1 !== sscanf($h= substr($read, $o, 2), '%x', $c)) {
              throw new \io\IOException('Invalid byte sequence "='.$h.'"');
            }
            $this->buffer.= chr($c);
            $o+= 2;
          }
        }
      }
    }
    $chunk= substr($this->buffer, 0, $limit);
    $this->buffer= substr($this->buffer, $limit);
    return $chunk;
  }

  /**
   * Returns the number of bytes that can be read from this stream 
   * without blocking.
   *
   */
  public function available() {
    return $this->in->available();
  }

  /**
   * Close this buffer.
   *
   */
  public function close() {
    $this->in->close();
  }
}
