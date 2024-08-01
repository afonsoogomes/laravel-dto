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
    protected $whitelist = false;

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
     * Create a new DTO instance.
     *
     * @param array $items
     * @return static
     */
    public static function create(array $items = [])
    {
        return new static($items);
    }

    /**
     * DTO constructor.
     *
     * @param array $items
     */
    public function __construct($items = [])
    {
        $defaults = $this->defaults();
        $items = array_merge($defaults, $items);

        $items = array_filter($items, function ($value) {
            return $value !== null;
        });

        $transformedItems = $this->transform();
        $items = array_merge($items, $transformedItems);

        if ($this->whitelist) {
            $items = $this->applyWhitelist($items);
        }

        $this->collection = new Collection($items);

        $this->validate();
    }

    /**
     * Apply the whitelist to remove fields that are not allowed.
     *
     * @param \Illuminate\Support\Collection $collection
     * @return \Illuminate\Support\Collection
     */
    private function applyWhitelist(array $items): array
    {
        return array_filter($items, function ($key) {
            return array_key_exists($key, $this->rules());
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Transform the data before validation.
     * This method can be overridden in the child classes to transform data before validation.
     *
     * @return array
     */
    private function transform(): array
    {
        return $this->items;
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
    public function all()
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
    protected function toArray(): array
    {
        return $this->collection->toArray();
    }
}
