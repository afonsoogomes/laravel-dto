<?php

namespace AfonsoOGomes\LaravelDTO\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use function Laravel\Prompts\text;

class MakeDTOCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dto {name?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new dto class';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $name = $this->argument('name') ?? text(
            label: 'What is your dto name?',
            placeholder: 'DTO name',
            required: true
        );

        $dtoName = Str::studly(class_basename($name));
        $directory = Str::replaceLast($dtoName, '', $name);
        $namespace = 'App\DTO\\' . str_replace('/', '\\', $directory);

        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_\/]*$/', $name)) {
            $this->error('Invalid dto name. Please use only letters, numbers, underscores, and forward slashes.');
            return;
        }

        $stubPath = __DIR__ . '/../../../stubs/dto.plain.stub';

        if (!File::exists($stubPath)) {
            $this->error('Stub file does not exist.');
            return;
        }

        try {
            $stub = File::get($stubPath);
        } catch (\Exception $e) {
            $this->error('Could not read the stub file: ' . $e->getMessage());
            return;
        }

        $stub = str_replace('{{ namespace }}', trim($namespace, '\\'), $stub);
        $stub = str_replace('{{ class }}', $dtoName, $stub);

        $dtoPath = app_path('Dto/' . str_replace('\\', '/', $directory));
        $dtoPath = rtrim($dtoPath, '/');
        $dtoFile = $dtoPath . '/' . $dtoName . '.php';

        if (File::exists($dtoFile) && !$this->confirm('DTO already exists. Do you want to overwrite it?', false)) {
            $this->info('DTO creation aborted.');
            return;
        }

        try {
            if (!File::exists($dtoPath)) {
                File::makeDirectory($dtoPath, 0755, true);
            }

            File::put($dtoFile, $stub);
        } catch (\Exception $e) {
            $this->error('Could not write the dto file: ' . $e->getMessage());
            return;
        }

        $this->components->info(sprintf('%s [%s] created successfully.', 'DTO', $dtoFile));
    }
}
