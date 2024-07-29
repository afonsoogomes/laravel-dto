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

use Illuminate\Support\Facades\Validator;

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
- `bool $removeUnvalidatedFields`: Whether to remove fields that do not have validation rules. Default is `false`.

### Methods

- `create(array $items = [])`: Static method to create a new DTO instance.
- `__construct(array $items = [])`: Constructor to initialize the DTO with data and perform validation.
- `rules()`: Method to define validation rules for the DTO. Override this method in your DTO classes.

## Testing

To test the library, make sure you have Laravel installed and configured. Create a new DTO class using the Artisan command and verify that it works as expected.

## Contributing

If you would like to contribute to the library, please submit a pull request on GitHub. Ensure that you follow the coding standards and include tests for new features.

## License

This library is licensed under the MIT License. See the [LICENSE](LICENSE) file for more information.
