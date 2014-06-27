Cascade Magic
=============

The Magic plugin synthetizes page blocks from various metadata, like Smalldb
state machines and form definitions.

Primary feature of this plugin is a block storage. Default configuration
registers this block storage and lets it scan all resources in global context
automatically.


Usage
-----

  1. Create blocks as usual, then point `template_block` option of the magic
     block storage to them: `"magic_template/{action}"`. Same symbols as in
     templates are available.

  2. Any block of name in form "prefix/entity/action", will be created.
     Relevant entity and action must be found in context.

  3. Blocks are created as usual, but after loading all strings are processed
     using `filename_format()` function.

It is a good idea to put magic block storage as the last one. It allows to
override any generated block, but still have nice generated default blocks.


Available symbols in template
-----------------------------

@note Symbols are processed using `filename_format()` function. There are more
  features than plain string substitution only.

  - `{entity}`: Entity name from requested block name.
  - `{action}`: Action from requested block name.

@todo Get more symbols.

License
-------

The most of the code is published under Apache 2.0 license. See [LICENSE](doc/license.md) file for details.



Contribution guidelines
-----------------------

There is no bug tracker yet, so send me an e-mail and we will figure it out.

If you wish to send me a patch, please create a Git pull request or send a Git formatted patch via email.

