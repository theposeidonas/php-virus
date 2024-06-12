# PHP Worm Virus

PHP virus inspired from Linux Conf Au 2019

## Description

boom.php file have 2 main functions:

```php
function executeCode($virus)
function encryptContent($virus)
```
- executeCode function simply gets every PHP file in and check if file has ``Checksum`` in first line. 
- If file has checksum, nothing happens. If dont, foreach loop will start
- Foreach loop gets every file gathered by glob function, and simply adds;
  - Checksum of this file (md5 hash of filename)
  - Payload code as hash ready to execute 
  - Original code of file

## About

This repository is purely for educational purposes. This code will infect all of your PHP files.




Related to this video: https://www.youtube.com/watch?v=2Ra1CCG8Guo
