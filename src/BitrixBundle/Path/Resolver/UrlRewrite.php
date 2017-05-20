<?php


namespace BitrixBundle\Path\Resolver;


use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;

class UrlRewrite implements LoggerAwareInterface
{
    use LoggerAwareTrait;
    /**
     * @var string
     */
    private $urlRewritePath;

    public function __construct(string $urlRewritePath)
    {
        $this->urlRewritePath = $urlRewritePath;
    }

    /**
     * @param $name
     *
     * @return mixed
     * @throws UrlRewriteCantResolveException
     * @throws \RuntimeException
     */
    public function locate($name)
    {
        if (!$this->urlRewritePath || !is_file($this->urlRewritePath)) {
            $this->logger->alert(sprintf('The urlrewrite file "%s" does not exist.', $this->urlRewritePath));
            throw new \RuntimeException(sprintf('The urlrewrite file "%s" does not exist.', $this->urlRewritePath));
        }

        global $DBType, $DBDebug, $DBDebugToFile, $DBHost, $DBName, $DBLogin, $DBPassword;
        /** @noinspection PhpIncludeInspection */
        /** @noinspection UsingInclusionReturnValueInspection */
        if (($file = include $this->urlRewritePath) && is_string($file)) {
            $this->logger->info(sprintf('Bitrix UrlRewrite resolved %s.', $file));
            return $file;
        }
        $this->logger->error(sprintf('Cant find file for "%s".', $name));
        throw new UrlRewriteCantResolveException(sprintf('Cant find file for "%s".', $name));
    }
}