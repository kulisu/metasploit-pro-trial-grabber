PHP Random User Agent generator
===============================

A random User-Agent generator written in PHP.  It generates desktop browser User
Agent strings, roughly based on browser and OS usage statistics from Wikipedia
and StatOwl.com as of July 2012.

Numbers for more minor variable components of the
User Agent for each browser are chosen randomly, so it may at times generate
User Agents that never actually exist in the wild.

Usage
-----

Simply require `uagent.php` and call the function `random_uagent()`. You may
optionally pass a single parameter consisting of an array of language codes to
randomly choose from.

Changelog
---------

* 2012-07-17: refactored by Mike White (<m@mwhite.info>) for readability,
              accuracy, and ease of modification
* 2011-11-01: initial release by Luka Pusic (<pusic93@gmail.com>)
              see http://360percents.com/posts/php-random-user-agent-generator/ 

License
-------

"THE BEER-WARE LICENSE" (Revision 42):

<pusic93@gmail.com> wrote this file. As long as you retain this notice you can
do whatever you want with this stuff. If we meet some day, and you think this
stuff is worth it, you can buy me a beer in return. Luka Pusic
