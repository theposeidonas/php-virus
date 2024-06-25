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

It does simply replicate itself in every php file. After that, it will run commands from ``data.json`` file. If file version is lower, it will get content from ``update.json`` file and update itself.

## About

This repository is purely for educational purposes. This code will infect all of your PHP files. Use with caution.
