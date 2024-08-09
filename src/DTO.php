<?php

namespace AfonsoOGomes\LaravelDTO;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

abstract class DTO
{
    /**
     * The collection that stores the data for the DTO.
     *
     * @var \Illuminate\Support\Collection
     */
    private $collection;

    /**
     * Indicates whether to use a whitelist to allow only specific fields.
     *
     * @var bool
     */
    protected $whitelist = true;

    /**
     * Magic method to get properties dynamically.
     *
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->collection->get($name);
    }

    /**
     * DTO constructor.
     *
     * @param array $items
     */
    public function __construct(array $items = [])
    {
        $this->collection = new Collection($items);
        $this->collection = $this->collection->mergeRecursive($this->defaults());
        $this->collection = $this->collection->mergeRecursive($this->transform());

        if ($this->whitelist) {
            $this->collection = $this->collection->filter(function ($item, $key) {
                return array_key_exists($key, $this->rules());
            });
        }

        $this->validate();
    }

    /**
     * Transform the data before validation.
     * This method can be overridden in the child classes to transform data before validation.
     *
     * @return array
     */
    protected function transform(): array
    {
        return [];
    }

    /**
     * Get the default values for the DTO.
     * This method can be overridden in the child classes to provide default values.
     *
     * @return array
     */
    protected function defaults(): array
    {
        return [];
    }

    /**
     * Get the validation rules for the DTO.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * Validate the DTO data.
     *
     * @return void
     */
    private function validate()
    {
        $validator = Validator::make($this->collection->all(), $this->rules());
        $validator->validate();
    }

    /**
     * Get an item from the collection.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        return $this->collection->get($key, $default);
    }

    /**
     * Get all items from the collection.
     *
     * @return array
     */
    public function all(): array
    {
        return $this->collection->all();
    }

    /**
     * Check if a key exists in the collection.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return $this->collection->has($key);
    }

    /**
     * Set an item in the collection.
     *
     * @param string $key
     * @param mixed $value
     * @return void
     */
    public function set(string $key, $value): void
    {
        $this->collection->put($key, $value);
    }

    /**
     * Remove an item from the collection.
     *
     * @param string $key
     * @return void
     */
    public function remove(string $key): void
    {
        $this->collection->forget($key);
    }

    /**
     * Get the count of items in the collection.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->collection->count();
    }

    /**
     * Convert the collection to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->collection->toArray();
    }

    /**
     * Convert the collection to a JSON string.
     *
     * @return string
     */
    public function toJson(): string
    {
        return $this->collection->toJson();
    }

    /**
     * Create a new DTO instance from a JSON string.
     *
     * @param string $json
     * @return static
     */
    public static function fromJson(string $json)
    {
        $data = json_decode($json, true);
        return new static($data);
    }

    /**
     * Create a new DTO instance from an array.
     *
     * @param array $data
     * @return static
     */
    public static function fromArray(array $data)
    {
        return new static($data);
    }
}
