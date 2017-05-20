<?php


namespace BitrixBundle\Path\Resolver;


class FileCmsResolver
{
    /**
     * @var array
     */
    private $indexFiles;

    /**
     * @var null
     */
    private $fromDir;

    public function __construct($fromDir = null, array $indexFiles)
    {
        $this->indexFiles = $indexFiles;
        $this->fromDir = $fromDir;
    }

    public function locate($file, $fromDir = null)
    {
        $path = $this->getPath($fromDir ?: $this->fromDir, $file);
        if ($this->isFile($path)) {
            return $path;
        }

        foreach ($this->indexFiles as $indexFile) {
            $path = $this->getPath($path, $indexFile);
            if ($this->isFile($path)) {
                return $path;
            }
        }

        throw new FileCmsResolverFileNotFound(sprintf('FileCmsResolver cant find file for %s', $file));
    }

    public function getPath($directory, $file)
    {
        return implode(DIRECTORY_SEPARATOR, [
            rtrim($directory, DIRECTORY_SEPARATOR),
            ltrim($file, DIRECTORY_SEPARATOR),
        ]);
    }

    public function isFile($path)
    {
        return file_exists($path) && is_file($path);
    }
}