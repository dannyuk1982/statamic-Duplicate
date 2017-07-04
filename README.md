*Statamic Duplicate*
==
Written by Danny Richardson for [Pixel Primate](http://www.pixelprimate.com), V1.5.2

> Licensed under the [MIT licence](https://opensource.org/licenses/MIT)
>
> Copyright 2017 Pixel Primate Ltd.  danny@pixelprimate.com
> 
> Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
> 
> The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
> 
> THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.

*Installation*
==

Copy the files over to `/site/addons/Duplicate`.


*Usage*
==

Just select the `Duplicate` option from a collection index page or the pages.. er, page.


*Translations*
==
Now translatable (thanks Rias500 and rrelmy!) so also add in a translation for your locale if needed. If you add in any other languages please create a pull request to add them here.


*Changelog*
==
1.5:
- Added support for pages also
- Improved detection as to whether the JavaScript inject code should fire
- Wrote more sexy comments
- Guessed at some words in German and Dutch
- Possibly broke something

1.5.1
- Added some checks to stop hackers doing hacking stuff, like rigging the US election

1.5.2
- Moved logic to detect page from the javascript to the listener, this means that the script only runs on a) the pages index or b) a collection index, this should fix [#7](https://github.com/dannyuk1982/statamic-Duplicate/issues/7)

*Help*
==

This could be much smarter 
- the alogorithm that names the files could be more intelligent (see comments)
- the script should listen for, and run after, a Vue event, not the current XHR hijack which is really hacky

If you want to help please create a pull request or contact me!
