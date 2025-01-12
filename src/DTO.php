<?php

namespace AfonsoOGomes\LaravelDTO;

use Illuminate\Support\Facades\Validator;

abstract class DTO
{
    /**
     * Constructs a new DTO instance with the given items and whitelist setting.
     *
     * @param array $items An associative array of data to initialize the DTO with.
     * @param bool $whitelist Whether to only allow setting properties that are defined on the DTO class.
     */
    private function __construct(array $items = [], bool $whitelist = false)
    {
        $defaultItems = $this->defaults();
        foreach ($defaultItems as $key => $item) {
            $this->set($key, $item, $whitelist);
        }

        foreach ($items as $key => $item) {
            $this->set($key, $item, $whitelist);
        }

        $transformItems = $this->transform();
        foreach ($transformItems as $key => $item) {
            $this->set($key, $item, $whitelist);
        }

        $this->validate();
    }

    /**
     * Create a new DTO instance
     *
     * @param array $data
     * @return static
     */
    public static function make(array $data, bool $whitelist = false)
    {
        return new static($data, $whitelist);
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
     * Get the validation rules for the DTO.
     *
     * @return array
     */
    protected function rules(): array
    {
        return [];
    }

    /**
     * Get the validation error messages for the DTO.
     *
     * @return array
     */
    protected function messages(): array
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
        $validator = Validator::make(get_object_vars($this), $this->rules(), $this->messages());
        $validator->validate();
    }

    /**
     * Sets a property on the DTO object.
     *
     * @param string $key The name of the property to set.
     * @param mixed $value The value to set for the property.
     * @param bool $whitelist Whether to only allow setting properties that are defined on the DTO class.
     */
    private function set(string $key, $value, $whitelist)
    {
        if ($whitelist) {
            if (!in_array($key, array_keys(get_object_vars($this)))) {
                return;
            }
        }

        $this->{$key} = $value;
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
        return collect(get_object_vars($this))->get($key, $default);;
    }

    /**
     * Get only the specified keys from the collection.
     *
     * @return array
     */
    public function only(array $keys): array
    {
        return collect(get_object_vars($this))->only($keys)->all();
    }

    /**
     * Get all items in the collection except for those with the specified keys.
     *
     * @return array
     */
    public function except(array $keys): array
    {
        return collect(get_object_vars($this))->except($keys)->all();
    }

    /**
     * Get all items from the collection.
     *
     * @return array
     */
    public function all(): array
    {
        return get_object_vars($this);
    }

    /**
     * Check if a key exists in the collection.
     *
     * @param string $key
     * @return bool
     */
    public function has(string $key): bool
    {
        return isset($this->{$key});
    }

    /**
     * Get the count of items in the collection.
     *
     * @return int
     */
    public function count(): int
    {
        return count(get_object_vars($this));
    }

    /**
     * Convert the collection to an array.
     *
     * @return array
     */
    public function toArray(): array
    {
        return $this->all();
    }

    /**
     * Convert the collection to a JSON string.
     *
     * @return string
     */
    public function toJson(): string
    {
        return json_encode($this->toArray());
    }
}
