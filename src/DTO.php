<?php

namespace AfonsoOGomes\LaravelDTO\DTO;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Collection;

abstract class DTO extends Collection
{
    /**
     * Indicates whether fields that do not have validation rules should be removed.
     *
     * @var bool
     */
    protected $removeUnvalidatedFields = false;

    /**
     * Magic method to get properties dynamically.
     *
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {
        return $this->items[$name] ?? null;
    }

    /**
     * Create a new DTO instance.
     *
     * @param array $items
     * @param bool $removeUnvalidatedFields
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
     * @param bool $removeUnvalidatedFields
     */
    public function __construct($items = [])
    {
        $this->items = array_filter($items, function ($value) {
            return $value !== null;
        });

        $transformedItems = $this->prepareForValidation();
        $mergedData = array_merge($this->items, $transformedItems);

        parent::__construct($mergedData);

        if ($this->removeUnvalidatedFields) {
            $this->items = $this->removeUnvalidatedFields($this->items);
        }

        $this->validate();
    }

    /**
     * Remove fields that do not have validation rules.
     *
     * @param array $items
     * @return array
     */
    protected function removeUnvalidatedFields(array $items): array
    {
        return array_filter($items, function ($key) {
            return array_key_exists($key, $this->rules());
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Prepare the data for validation.
     * This method can be overridden in the child classes to transform data before validation.
     *
     * @return array
     */
    protected function prepareForValidation(): array
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
        $validator = Validator::make($this->all(), $this->rules());
        $validator->validate();
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
}
