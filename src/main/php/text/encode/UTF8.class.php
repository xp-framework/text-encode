<?php namespace text\encode;

/**
 * Encodes/decodes iso-8859-1 to utf-8
 *
 * <code>
 *   $b= UTF8::encode($str);
 *   $str= UTF8::decode($b);
 * </code>
 *
 * @see      rfc://2045#6.8
 */
class UTF8 {

  /**
   * Encode string
   *
   * @param   string str
   * @return  string
   */
  public static function encode($str) { 
    return iconv(\xp::ENCODING, 'utf-8', $str);
  }
  
  /**
   * Decode utf8 encoded data
   *
   * @param   string str
   * @return  string
   */
  public static function decode($str) { 
    return iconv('utf-8', \xp::ENCODING, $str);
  }
}