<!-- Screenshot -->
<p align="center">
    <img src="resources/wallpaper.jpg" alt="Wallpaper">
</p>

<!-- Badges -->
<p align="center">
  <img src="resources/version.svg" alt="Version">
  <img src="resources/license.svg" alt="License">
</p>

# Triggers

This package enables the use of database triggers within Laravel applications. Note that your chosen database must support triggers for the package to work.

## Installation

Pull in the package using Composer:

```bash
composer require mattkingshott/triggers
```

## Usage

Triggers can only be added to existing tables. Therefore, when creating triggers in your migration files, make sure you add them after the `Schema::create` method.

### Table

To create a trigger, simply call the `table` method on the `Triggers\Trigger` class:

```php
use Triggers\Trigger;

Trigger::table('posts');
```

### Key

By default, the class will generate a name for the trigger using the following convention:

```
trigger_{TABLE}_{TIME}_{EVENT}
```

However, since trigger names must be unique across the database, if you were to create two triggers that used the same event and time (these concepts are covered in the next section), then you'd get an error.

To address this problem, the class offers a `key` method that allows you to add your own custom text to the trigger's name, thereby ensuring that the trigger name can be made unique:

```php
Trigger::table('posts')->key('custom');
```

When a key is specified, the trigger name is derived from the following convention:

```
trigger_{TABLE}_{KEY}_{TIME}_{EVENT}
```

### Event and time

Next, you need to specify whether the trigger should be fired for an `INSERT`, `UPDATE` or `DELETE` event. You will also need to specify whether the trigger should run `BEFORE` or `AFTER` the event has taken place:

```php
Trigger::table('posts')->beforeDelete();
Trigger::table('posts')->beforeInsert();
Trigger::table('posts')->beforeUpdate();

Trigger::table('posts')->afterDelete();
Trigger::table('posts')->afterInsert();
Trigger::table('posts')->afterUpdate();
```

### Statement

The final step, is to specify the SQL statement(s) that should be executed by the trigger when it is fired. To do this, supply a `Closure` to the event / time method. Note that the `Closure` must return a SQL `string` e.g.

```php
Trigger::table('posts')->afterInsert(function() {
    return "UPDATE `users` SET `posts` = 1 WHERE `id` = NEW.user_id;";
});
```

### Example

The following example shows a migration that creates a `posts` table and then assigns the trigger to it.

```php
use Triggers\Trigger;

class CreatePostsTable extends Migration
{
    public function up() : void
    {
        Schema::create('posts', function(Blueprint $table) {
            $table->unsignedTinyInteger('id');
            $table->string('title');
        });

        Trigger::table('posts')->key('count')->afterInsert(function() {
            return "UPDATE `users` SET `posts` = 1 WHERE `id` = NEW.user_id;";
        });
    }
}
```

## Contributing

Thank you for considering a contribution to the project. You are welcome to submit a PR containing improvements, however if they are substantial in nature, please also be sure to include a test or tests.

## Support the project

If you'd like to support the development of the project, then please consider [sponsoring me](https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=YBEHLHPF3GUVY&source=url). Thanks so much!

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
