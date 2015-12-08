Text encoding
=============

[![Build Status on TravisCI](https://secure.travis-ci.org/xp-framework/text-encode.svg)](http://travis-ci.org/xp-framework/text-encode)
[![XP Framework Module](https://raw.githubusercontent.com/xp-framework/web/master/static/xp-framework-badge.png)](https://github.com/xp-framework/core)
[![BSD Licence](https://raw.githubusercontent.com/xp-framework/web/master/static/licence-bsd.png)](https://github.com/xp-framework/core/blob/master/LICENCE.md)
[![Required PHP 5.5+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-5_5plus.png)](http://php.net/)
[![Supports PHP 7.0+](https://raw.githubusercontent.com/xp-framework/web/master/static/php-7_0plus.png)](http://php.net/)
[![Supports HHVM 3.4+](https://raw.githubusercontent.com/xp-framework/web/master/static/hhvm-3_4plus.png)](http://hhvm.com/)
[![Latest Stable Version](https://poser.pugx.org/xp-framework/text-encode/version.png)](https://packagist.org/packages/xp-framework/text-encode)

Common encodings for files, strings and streams

API
---

```php
package text.encode {
  public class text.encode.Base57
  public class text.encode.Base64
  public class text.encode.Base64InputStream
  public class text.encode.Base64OutputStream
  public class text.encode.CvsPassword
  public class text.encode.QuotedPrintable
  public class text.encode.QuotedPrintableInputStream
  public class text.encode.QuotedPrintableOutputStream
  public class text.encode.UTF7
  public class text.encode.UTF8
  public class text.encode.UUCode
}
```