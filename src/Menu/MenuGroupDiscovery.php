<?php

declare(strict_types=1);

namespace Igne\Nova\Menu;

use FilesystemIterator;
use Igne\Nova\Menu\Contracts\NovaMenuGroup;
use Igne\Nova\Menu\Exceptions\MenuGroupException;
use Illuminate\Config\Repository as Config;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

/**
 * Finds the app's {@see NovaMenuGroup} enum: the one named in igne-nova.menu.groups, or else the
 * only one in the igne-nova.menu.discover folders. Only files that mention the contract are
 * loaded; the class name is read from the file's namespace and enum declaration, so the folders
 * need no PSR-4 layout of their own as long as Composer can autoload the enum.
 *
 * The result is kept for the life of the process.
 */
final class MenuGroupDiscovery
{
    /**
     * @var class-string<NovaMenuGroup>|null
     */
    private ?string $enum = null;

    public function __construct(private readonly Config $config) {}

    /**
     * @return class-string<NovaMenuGroup>
     */
    public function enum(): string
    {
        return $this->enum ??= $this->resolve();
    }

    /**
     * @return class-string<NovaMenuGroup>
     */
    private function resolve(): string
    {
        $configured = $this->config->get('igne-nova.menu.groups');

        if ($configured !== null) {
            return self::validated(\is_string($configured) ? $configured : get_debug_type($configured));
        }

        $paths = array_values(array_filter((array) $this->config->get('igne-nova.menu.discover', []), \is_string(...)));
        $enums = $this->discover($paths);

        return match (\count($enums)) {
            0 => throw MenuGroupException::noneFound($paths),
            1 => $enums[0],
            default => throw MenuGroupException::severalFound($enums),
        };
    }

    /**
     * @param  list<string>  $paths
     * @return list<class-string<NovaMenuGroup>>
     */
    private function discover(array $paths): array
    {
        $marker = class_basename(NovaMenuGroup::class);
        $enums = [];

        foreach ($paths as $path) {
            foreach (self::phpFiles($path) as $file) {
                $source = (string) file_get_contents($file);
                $enum = str_contains($source, $marker) ? self::declaredEnum($source) : null;

                if ($enum !== null && enum_exists($enum) && is_subclass_of($enum, NovaMenuGroup::class)) {
                    $enums[$enum] = $enum;
                }
            }
        }

        sort($enums);

        return $enums;
    }

    /**
     * @return class-string<NovaMenuGroup>
     */
    private static function validated(string $class): string
    {
        if (! enum_exists($class) || ! is_subclass_of($class, NovaMenuGroup::class)) {
            throw MenuGroupException::notAnEnum($class);
        }

        return $class;
    }

    /**
     * @return list<string>
     */
    private static function phpFiles(string $path): array
    {
        if (! is_dir($path)) {
            throw MenuGroupException::notADirectory($path);
        }

        $files = [];

        foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path, FilesystemIterator::SKIP_DOTS)) as $file) {
            if ($file instanceof SplFileInfo && $file->isFile() && $file->getExtension() === 'php') {
                $files[] = $file->getPathname();
            }
        }

        return $files;
    }

    private static function declaredEnum(string $source): ?string
    {
        if (preg_match('/^\s*enum\s+(\w+)/m', $source, $declaration) !== 1) {
            return null;
        }

        $namespace = preg_match('/^\s*namespace\s+([\w\\\\]+)\s*[;{]/m', $source, $match) === 1 ? $match[1].'\\' : '';

        return $namespace.$declaration[1];
    }
}
