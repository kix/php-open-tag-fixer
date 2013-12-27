PHP Open tag fixer
==================

This tiny script is aimed at fixing the bad and ugly PHP short open tag syntax. It is based on PHP's tokenizer
extension, so you'll have to install it first.

Please note, this is not a Regex replace implementation, this script will only match valid ```<?php``` open tags in
valid PHP files.

Usage
-----
Usage is pretty straightforward:

```
# bin/console should actually point to matching file from this repo
bin/console fix ./ # where ./ is the path
```

Also you can only fix ```<?```'s and ```<?=```'s using ```-c``` and ```-e``` options respectively.