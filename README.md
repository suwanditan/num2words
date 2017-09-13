# num2words
A tool to convert numeric to words we know, handy for invoicing utility.

## HowTo Use:

Change setting.json configuration file, from *default: id* to *default: en*.

```

/* without suffix */ 
num2words( 10000000 ); /* output: ten million */

/* with suffix */ 
num2words( 10000000, "rupiah" ); /* output: ten million rupiah */

/* up to centillion support */ 
num2words( 100000000000000000000, "rupiah" ); /* output: one hundred quintillion rupiah */

```

And now, also supports German(de) language, just change _setting.json_ configuration file, 
from *default: id* to *default: de*.

```

/* without suffix - de */ 
num2words( 10000000 ); /* output: zehn milliarde */

/* with suffix - de */ 
num2words( 10000000, "rupiah" ); /* output: zehn milliarde rupiah */

/* up to centillion support - de */ 
num2words( 100000000000000000000, "rupiah" ); /* output: eine hundert quintillion rupiah */

```