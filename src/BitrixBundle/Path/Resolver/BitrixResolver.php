<?php


namespace BitrixBundle\Path\Resolver;


class BitrixResolver
{
    /**
     * @var \BitrixBundle\Path\Resolver\FileCmsResolver
     */
    private $fileCmsResolver;

    /**
     * @var \BitrixBundle\Path\Resolver\UrlRewrite
     */
    private $urlRewrite;

    public function __construct(FileCmsResolver $fileCmsResolver, UrlRewrite $urlRewrite)
    {
        $this->fileCmsResolver = $fileCmsResolver;
        $this->urlRewrite = $urlRewrite;
    }

    public function locate($file)
    {
        try {
            if ($path = $this->fileCmsResolver->locate($file)) {
                return $path;
            }
        } catch (FileCmsResolverFileNotFound $exception) {
            $urlRewriteFile = $this->urlRewrite->locate($file);
            if ($this->fileCmsResolver->isFile($urlRewriteFile)) {
                return $urlRewriteFile;
            }
        }


        throw new \InvalidArgumentException(sprintf('Cant find file for %s.', $file));
    }
}