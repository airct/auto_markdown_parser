auto_markdown_parser
====================

This is auto parser markdown files in directorys.

## Reference
GitHub Flavored

[parsedown]



## Example

> **Command:**

```shell
	#> cd path/to/auto_markdown_parser/
	#> php parser.php
```

> **Code:**

```php
	$auto = new AutoParser();
	$auto->md_folder = "md";
	$auto->html_folder = "html";
	$auto->parser();
```



[parsedown]: http://parsedown.org
