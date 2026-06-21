Text encoding changelog
=======================

## ?.?.? / ????-??-??

## 10.1.0 / 2026-06-21

* Made library compatible with XP 11, all PHP versions from 8.3 up to 8.6
  (@thekid)
* Merged PR #3: Refactor I/O exceptions, dropping XP 10 support in the
  doing. See xp-framework/core#363 for a write-up on the reason behind
  this and the plan to move forward.
  (@thekid)

## 10.0.0 / 2022-02-27

* Implemented xp-framework/rfc#341, dropping compatibility with XP 9

## 9.0.1 / 2020-05-31

* Removed HHVM implementations added in #2, HHVM no longer supports PHP
  (@thekid)

## 9.0.0 / 2020-04-10

* Implemented xp-framework/rfc#334: Drop PHP 5.6:
  . **Heads up:** Minimum required PHP version now is PHP 7.0.0
  . Rewrote code base, grouping use statements
  . Converted `newinstance` to anonymous classes
  (@thekid)

## 8.0.1 / 2020-04-04

* Made compatible with XP 10 - @thekid

## 8.0.0 / 2017-06-19

* Fixed issue #2: Fix Base64OutputStream on HHVM - @thekid
* **Heads up:** Drop PHP 5.5 support - @thekid
* Added forward compatibility with XP 9.0.0 - @thekid

## 7.1.0 / 2016-08-29

* Added forward compatibility with XP 8.0.0 - @thekid

## 7.0.0 / 2016-02-21

* **Adopted semantic versioning. See xp-framework/rfc#300** - @thekid 
* Added version compatibility with XP 7 - @thekid

## 6.6.0 / 2014-12-08

* Extracted from the XP Framework's core - @thekid