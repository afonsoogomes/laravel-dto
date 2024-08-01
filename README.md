# Laravel DTO Library

A Laravel library for creating and managing Data Transfer Objects (DTOs) with validation and an Artisan command for easy DTO generation.

## Features

- **DTO Class**: A base class for creating DTOs with validation.
- **Artisan Command**: A command to easily generate new DTO classes.

## Installation

To install the library, add it to your `composer.json` file or run the following command:

```bash
composer require afonsoogomes/laravel-dto
```

## Usage

### Creating a DTO Class

To create a new DTO class, use the provided Artisan command:

```bash
php artisan make:dto {name}
```

Replace `{name}` with the desired name for your DTO class. The command will generate a new DTO class in the `app/DTO` directory.

### Example DTO Class

Here's an example of how to use the `DTO` class:

```php
namespace App\DTO;

use AfonsoOGomes\LaravelDTO\DTO;

class UserDTO extends DTO
{
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email'],
        ];
    }
}
```

### Using the DTO

You can create an instance of the DTO and access its properties like so:

```php
$userDTO = UserDTO::create([
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
]);

echo $userDTO->name;  // John Doe
```

### Advanced Examples

#### Using `transform`

You can use the `transform` method to process data before validation. For example, to clean up a phone number:

```php
namespace App\DTO;

use AfonsoOGomes\LaravelDTO\DTO;

class ContactDTO extends DTO
{
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'size:10'],  // Example: must be 10 digits
        ];
    }

    protected function transform(): array
    {
        return [
            'phone' => preg_replace('/\D/', '', $this->phone)
        ];
    }
}
```

#### Using `whitelist`

You can configure the DTO to use a whitelist to include only fields that are explicitly defined in the rules() method. This ensures that only validated fields are returned, filtering out any additional fields not covered by the validation rules:

```php
namespace App\DTO;

use AfonsoOGomes\LaravelDTO\DTO;

class OrderDTO extends DTO
{
    protected $whitelist = true;

    protected function rules(): array
    {
        return [
            'order_id' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            // No rule for 'notes', for example
        ];
    }
}
```

### Command Options

- `name`: The name of the DTO class to be created. You can use forward slashes to create nested directories.

Example:

```bash
php artisan make:dto UserProfile
```

This will create a `UserProfile.php` file in the `app/DTO` directory.

## Configuration

### DTO Constructor

The DTO constructor accepts the following parameters:

- `array $items`: The initial data for the DTO.
- `bool $whitelist`: Whether to remove fields that do not have validation rules. Default is `false`.

### Methods

- `create(array $items = [])`: Static method to create a new DTO instance.
- `__construct(array $items = [])`: Constructor to initialize the DTO with data and perform validation.
- `rules()`: Method to define validation rules for the DTO. Override this method in your DTO classes.
- `transform()`: Method to preprocess the data before validation.
- `defaults()`: Method to define default values for the DTO.
- `get($key, $default = null)`: Method to get a value from the DTO by key.
- `all()`: Method to get all the data in the DTO.
- `has($key)`: Method to check if a key exists in the DTO.
- `count()`: Method to count the number of items in the DTO.
- `toArray()`: Method to convert the DTO to an array.

## Testing

To test the library, make sure you have Laravel installed and configured. Create a new DTO class using the Artisan command and verify that it works as expected.

## Contributing

If you would like to contribute to the library, please submit a pull request on GitHub. Ensure that you follow the coding standards and include tests for new features.

## License

This library is licensed under the MIT License. See the [LICENSE](https://github.com/afonsoogomes/laravel-dto/blob/main/LICENSE.md) file for more information.
