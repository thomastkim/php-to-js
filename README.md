# Use PHP in JavaScript For Laravel 5

This package allows you to simply convert and transfer your PHP variables to use in JavaScript.

This is a very simple package I made for practice / fun.

## Installation

Installing this package is very easy. Just follow a few quick steps.

### Composer

Pull this package through composer by opening your composer.json file and adding this under `require`:

```
"kim/javascript": "~1.0"
```

Afterward, run either `composer update` or `composer install`.

### Providers and Aliases

Open up your `config/app.php` and add this to your providers array:

```
'Kim\JavaScript\JavaScriptServiceProvider'
```

and this to your aliases array:

```
'JavaScript' => 'Kim\JavaScript\JavaScriptFacade'
```

### Config File

Finally, publish the package's config file by running this inside your favorite command-line interface:

```bash
php artisan vendor:publish
```

This command will publish a `javascript.php` file inside your config directory.

## Usage

### Adding a single variable

To add or pass a single variable from PHP to JavaScript, you can use simple use the `add` method.

```
JavaScript::add('name', 'Thomas');
```

Now, you can access the `name` variable globally using JavaScript.

```
console.log(name);	// Outputs 'Thomas'
```

Notice that first value is the variable name. The second value is the variable's value. It follows a key-value pairing like an associative array.

### Adding an array of values

You can also pass an array of values.

```
JavaScript::put([
    'foo' => 'bar',
    'pi' => 3.14,
    'isWorking' => true,
    'nothing' => NULL
]);
```

All these will then be accessible globally within your JavaScript files.

```
console.log(foo);		// Outputs 'bar'
console.log(pi);		// Outputs 3.14
console.log(isWorking);	// Outputs true
console.log(nothing);	// Outputs null
```

### Multiple Calls

You do not need to call the add or put method in one location. You can call both methods multiple times across multiple files, and it will work just fine.

```
JavaScript::put([
    'foo' => 'bar',
    'pi' => 3.14,
]);
JavaScript::put([
    'isWorking' => true
    'name' => 'Thomas',
]);
JavaScript::add('nothing', NULL);
```

```
console.log(foo);		// Outputs 'bar'
console.log(pi);		// Outputs 3.14
console.log(isWorking);	// Outputs true
console.log(name);		// Outputs 'Thomas'
console.log(nothing);	// Outputs null
```

### Duplicates

The package also makes sure that there are no duplicates so if you add an object in one class and then add it again in another class, it will not conflict. However, if the two values are different, the latest call will overwrite the preceding value.

```
// No conflict
JavaScript::add('name', 'Thomas');
JavaScript::add('name', 'Thomas');

console.log(name); // Outputs 'Thomas'

// New value overwrite old value
$user = User::first();
JavaScript::add('user', $user);
$user->name = "Stephanie";
JavaScript::add('user', $user);

console.log('user'); // Outputs user object with the name of 'Stephanie'
```

### Configuration

If you open up your `config/javascript.php` file, you will see two settings. By default, the JavaScript variables are added to the end of your HTML `head`. If you want to add it elsewhere, simply specify the view. It will automatically add the JavaScript variables to the **start** of that view.

You can also specify the namespace and reduce the number of objects / functions that are added to the global scope. If you do change the namespace, remember to reflect that in your JavaScript code. For example, lets say you set the namespace to 'parent'.

```
// Add your data like you normally would.
JavaScript::add('foo', 'bar');
```

```
// Access it from the parent namespace using either brackets or the dot notation.
console.log(parent.foo);	// Outputs 'bar'
console.log(parent['foo']);	// Outputs 'bar'
```

## License

This package is free software distributed under the terms of the MIT license.