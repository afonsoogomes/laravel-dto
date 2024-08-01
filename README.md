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
$userDTO = new UserDTO([
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
]);

echo $userDTO->name;  // John Doe
```

### Advanced Examples

#### Using `defaults`

The `defaults` method allows you to define default values for the DTO fields. Here's an example:

```php
namespace App\DTO;

use AfonsoOGomes\LaravelDTO\DTO;

class ProductDTO extends DTO
{
    protected function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'numeric'],
            'stock' => ['required', 'integer'],
        ];
    }

    protected function defaults(): array
    {
        return [
            'stock' => 0, // Default stock value
        ];
    }
}

// Using the DTO with default values

$data = [
    'name' => 'Product Name',
    'price' => 19.99,
];

$productDTO = new ProductDTO($data);

echo $productDTO->name;  // Product Name
echo $productDTO->price; // 19.99
echo $productDTO->stock; // 0 (default value)
```

#### Using `transform`

The `transform` method allows you to process data before validation. For example, to clean up a phone number:

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
            'phone' => ['required', 'string', 'size:10'],  // Example: must be 10 digits
        ];
    }

    protected function transform(): array
    {
        return [
            'phone' => preg_replace('/\D/', '', $this->phone) // Remove non-numeric characters from phone
        ];
    }
}

// Using the DTO with data transformation

$data = [
    'name' => 'John Doe',
    'email' => 'john.doe@example.com',
    'phone' => '(123) 456-7890', // Original format
];

$userDTO = new UserDTO($data);

echo $userDTO->name;   // John Doe
echo $userDTO->email;  // john.doe@example.com
echo $userDTO->phone;  // 1234567890 (transformed to 10 digits)
```

#### Combined Example

Here is an example that uses both `defaults` and `transform`:

```php
namespace App\DTO;

use AfonsoOGomes\LaravelDTO\DTO;

class OrderDTO extends DTO
{
    protected function rules(): array
    {
        return [
            'order_id' => ['required', 'string'],
            'amount' => ['required', 'numeric'],
            'currency' => ['required', 'string', 'size:3'], // Example: 3-letter currency code
        ];
    }

    protected function defaults(): array
    {
        return [
            'currency' => 'USD', // Default currency value
        ];
    }

    protected function transform(): array
    {
        return [
            'order_id' => strtoupper($this->order_id), // Transform order ID to uppercase
        ];
    }
}

// Using the DTO with default values and data transformation

$data = [
    'order_id' => 'abc123',
    'amount' => 99.99,
];

$orderDTO = new OrderDTO($data);

echo $orderDTO->order_id; // ABC123 (transformed to uppercase)
echo $orderDTO->amount;   // 99.99
echo $orderDTO->currency; // USD (default value)
```

### Command Options

- `name`: The name of the DTO class to be created. You can use forward slashes to create nested directories.

Example:

```bash
php artisan make:dto UserProfileDTO
```

This will create a `UserProfileDTO.php` file in the `app/DTO` directory.

## Configuration

### DTO Constructor

The DTO constructor accepts the following parameter:

- `array $items`: The initial data for the DTO.

### Methods

- `__construct(array $items = [])`: Constructor to initialize the DTO with data and perform validation.
- `rules()`: Method to define validation rules for the DTO. Override this method in your DTO classes.
- `transform()`: Method to preprocess the data before validation.
- `defaults()`: Method to define default values for the DTO.
- `get($key, $default = null)`: Method to get a value from the DTO by key.
- `all()`: Method to get all the data in the DTO.
- `has($key)`: Method to check if a key exists in the DTO.
- `set(string $key, $value)`: Method to set a value in the DTO by key.
- `remove(string $key)`: Method to remove a value from the DTO by key.
- `count()`: Method to count the number of items in the DTO.
- `toArray()`: Method to convert the DTO to an array.
- `toJson()`: Method to convert the DTO to a JSON string.
- `fromJson(string $json)`: Static method to create a new DTO instance from a JSON string.
- `fromArray(array $data)`: Static method to create a new DTO instance from an array.

## Testing

To test the library, make sure you have Laravel installed and configured. Create a new DTO class using the Artisan command and verify that it works as expected.

## Contributing

If you would like to contribute to the library, please submit a pull request on GitHub. Ensure that you follow the coding standards and include tests for new features.

## License

This library is licensed under the MIT License. See the [LICENSE](https://github.com/afonsoogomes/laravel-dto/blob/main/LICENSE.md) file for more information.
