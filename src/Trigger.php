<?php declare(strict_types = 1);

namespace Triggers;

use Closure;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class Trigger
{
    /**
     * The unique element in the trigger name.
     *
     */
    protected string $key;

    /**
     * The table to create the trigger on.
     *
     */
    protected string $table;

    /**
     * Constructor.
     *
     */
    public function __construct(string $table)
    {
        $this->key   = '';
        $this->table = $table;
    }

    /**
     * Configure the given statement to run after records are deleted.
     *
     */
    public function afterDelete(Closure $statement) : void
    {
        $this->create('AFTER', 'DELETE', $statement);
    }

    /**
     * Configure the given statement to run after records are inserted.
     *
     */
    public function afterInsert(Closure $statement) : void
    {
        $this->create('AFTER', 'INSERT', $statement);
    }

    /**
     * Configure the given statement to run after records are updated.
     *
     */
    public function afterUpdate(Closure $statement) : void
    {
        $this->create('AFTER', 'UPDATE', $statement);
    }

    /**
     * Configure the given statement to run before records are deleted.
     *
     */
    public function beforeDelete(Closure $statement) : void
    {
        $this->create('BEFORE', 'DELETE', $statement);
    }

    /**
     * Configure the given statement to run before records are inserted.
     *
     */
    public function beforeInsert(Closure $statement) : void
    {
        $this->create('BEFORE', 'INSERT', $statement);
    }

    /**
     * Configure the given statement to run before records are updated.
     *
     */
    public function beforeUpdate(Closure $statement) : void
    {
        $this->create('BEFORE', 'UPDATE', $statement);
    }

    /**
     * Attach the trigger to the database table.
     *
     */
    protected function create(string $time, string $event, Closure $statement) : void
    {
        $placeholders = [
            '{NAME}'  => $this->name($time, $event),
            '{TIME}'  => $time,
            '{EVENT}' => $event,
            '{TABLE}' => $this->table,
            '{QUERY}' => $statement(),
        ];

        $sql = str_replace(
            array_keys($placeholders),
            array_values($placeholders),
            File::get(__DIR__ . '/../stubs/trigger.stub')
        );

        DB::unprepared($sql);
    }

    /**
     * Add a unique element to the name of the trigger.
     *
     */
    public function key(string $name) : static
    {
        $this->key = Str::finish($name, '_');

        return $this;
    }

    /**
     * Generate a unique name for the trigger.
     *
     */
    protected function name(string $time, string $event) : string
    {
        return Str::lower("trigger_{$this->table}_{$this->key}{$time}_{$event}");
    }

    /**
     * Create a new trigger for the given table.
     *
     */
    public static function table(string $table) : static
    {
        return new static($table);
    }
}
