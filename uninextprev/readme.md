# uniNextPrev Plugin

## What is it?

The uniNextPrev [Kirby CMS](https://getkirby.com/) plugin provide svariables with links and page titles for next/prev pages and can move through the site, up and down, through `parent/children/grandchildren` and also seamlessly moves through pagination (e.g. articles index)... if you set it. **So a sort of universal next / prev** & hence the pants name. 
 
Although it works in Kirby2, I'm sure it could be written in a much better way, I just needed it for a project and was in a hurry. It's not had a huge amount of testing so beware and we're only posting here in response to a request on the Kirby Forum.
 

## Demo
You can see it in action on [Susan Hall's website](http://susanhall.shoesforindustry.net). Note you can page through the site using the buttons at the bottom of the page. 

1. You can move backwards & forwards down & up through levels (parent/children e.g. about/privacy). 
2. You also can page through an article index and then through the actual details pages (articles) 
3. Or just the index (press) skipping the detail pages (details pages are still findable with search). 
4. It also works with Tag index pages and search indexes. 
5. Although it is hard to see as it is minified, it is also used in the header in `rel` links for next & previous. 

*We set this is a particular way, for the flow through the site, but you can do it how you please.* 


### Warning
It only works at the parent/child/grandchild menu levels - it will fail with ugly errors if you try and go deeper! *Four levels is probably too deep for a site anyway - but you have been warned - dragons.*

## Installation

1. Put the `uninextprev` folder in `/site/plugins` or use the [Kirby CLI](https://github.com/getkirby/cli): In your project folder, from the command line, enter:
```kirby plugin:install shoesforindustry/kirbycms-extensions/tree/master/plugins/uninextprev ```  
To update the plugin use:
```kirby plugin:update shoesforindustry/kirbycms-extensions/tree/master/plugins/uninextprev ```
2. You **must** set a comma separated quoted list of page uid's for which you want to ignore the children in next/prev in your config.php (it can be empty but must be set) e.g.

```php
/*---------------------------------------
Set 'ignore_children; for uniNextPrev Plugin
-----------------------------------------
Set a comma separated quoted list of page uid's for which you want to ignore the children of in this plugin. Maybe because you have an index page & detail pages, but only want to show the index and not the details.
e.g
c::set('uniNextPrev_ignore_children',array('press','articles'));
or an empty array
c::set('uniNextPrev_ignore_children',array(''));
*/
// Here we are ignoring the children (details pages) of 'press' (index page)
c::set('uniNextPrev_ignore_children',array('press'));
```

## How to use it?

### Basic
In your template add:

```php
<?php
//Call uniNextPrev plugin
$uniNextPrev = uninextprev(); 
?>
```

### With pagination
If your page has pagination (e.g. articles index), you should call it with that pagination. (If you don't set pagination it will ignore it and leave a big hole in your next / previous.)

**Obviously you can only call this after you have created the pagination.**

```php
<?php
//Call uniNextPrev plugin with articles pagination
$uniNextPrev = uninextprev(array('pagination' => $articles->pagination())); 
?>
```

### Using in a template

After you have called `uninextprev()` the plugin creates some variables which you can use in your template.

**Note:** These variables maybe empty so you should check before using!

+ nextPageURL   - accessible through `$uniNextPrev['nextPageURL']`
+ nextPageTitle - accessible through `$uniNextPrev['nextPageTitle']`
+ nextPageUID - accessible through `$uniNextPrev['nextPageUID']`
+ prevPageURL   - accessible through `$uniNextPrev['prevPageURL']`
+ prevPageTitle - accessible through `$uniNextPrev['prevPageTitle']`
+ prevPageUID - accessible through `$uniNextPrev['prevPageUID']`



### Using in snippets (called from your template)

After you have called the `uninextprev()` plugin you can also use the plugin variables in snippets.


### Call in a header snippet

Perhaps for 'head/rel' links e.g. 
  
`<link rel="next" title="Page title" href="http://...."/>`

```php
//Call header snippet with uniNextPrev plugin variables
snippet('header', array(
	'nextPageURL' => $uniNextPrev['nextPageURL'],
	'nextPageTitle' => $uniNextPrev['nextPageTitle'],
	'prevPageURL' => $uniNextPrev['prevPageURL'],
	'prevPageTitle' => $uniNextPrev['prevPageTitle']
));
```

In your header snippet use with something like (check to see if set and not blank!):
```php
<?php if(isset($nextPageURL) AND $nextPageURL<>''): ?>
<link rel="next" title="<?php echo $nextPageTitle ?>" href="<?php echo $nextPageURL ?>"/>
<?php endif?>

```

#### Call in a footer snippet

Or in your `<footer>` with nav links 
e.g. 
```html
<a href="http://..." rel="next" title="Page title ->">Next ></a>
````

```php
//Call footer snippet with uniNextPrev plugin variables
snippet('footer', array(
	'nextPageURL' => $uniNextPrev['nextPageURL'],
	'nextPageTitle' => $uniNextPrev['nextPageTitle'],
	'prevPageURL' => $uniNextPrev['prevPageURL'],
	'prevPageTitle' => $uniNextPrev['prevPageTitle']
));
```
In your footer snippet use with something like (check to see if set!):

```php
<?php if(isset($nextPageURL) AND $nextPageURL<>''): ?>
<a href="<?php echo $nextPageURL ?>" rel="next" title="<?php echo $nextPageTitle ?>">Next (<?php echo $nextPageTitle ?>)-></a>
<?php endif?>
```

#### Notes

In the demo, we don't call 'prev' on the first page or 'next' on the last, you could probably fix this ;-) 

e.g. for first page/template:

```php
snippet('footer', array(
	'nextPageURL' => $uniNextPrev['nextPageURL'],
	'nextPageTitle' => $uniNextPrev['nextPageTitle']
)) 
```

And for last page/template:

```php
snippet('footer', array(
	'prevPageURL' => $uniNextPrev['prevPageURL'],
	'prevPageTitle' => $uniNextPrev['prevPageTitle']
)) 
```

## Author
Russ Baldwin
<http://shoesforindustry.net>