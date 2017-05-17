<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2015 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Zend\ModuleManager\Listener;

/**
 * Abstract listener
 */
abstract class AbstractListener
{
    /**
     * @var ListenerOptions
     */
    protected $options;

    /**
     * __construct
     *
     * @param  ListenerOptions $options
     */
    public function __construct(ListenerOptions $options = null)
    {
        $options = $options ?: new ListenerOptions;
        $this->setOptions($options);
    }

    /**
     * Get options.
     *
     * @return ListenerOptions
     */
    public function getOptions()
    {
        return $this->options;
    }

    /**
     * Set options.
     *
     * @param ListenerOptions $options the value to be set
     * @return AbstractListener
     */
    public function setOptions(ListenerOptions $options)
    {
        $this->options = $options;
        return $this;
    }

    /**
     * Write a simple array of scalars to a file
     *
     * @param  string $filePath
     * @param  array $array
     * @return AbstractListener
     */
    protected function writeArrayToFile($filePath, array $array)
    {
        $content = "<?php\nreturn " . $this->var_export_min($array) . ';';
        file_put_contents($filePath, $content, LOCK_EX);
        return $this;
    }

    /**
     * var_export with minified content
     *
     * From http://php.net/manual/fr/function.var-export.php#54440
     *
     * @param  string|array $var
     * @return string
     */
    private function var_export_min($var)
    {
        if (is_array($var)) {
            $toImplode = [];
            foreach ($var as $key => $value) {
                $toImplode[] = var_export($key, true) . '=>' . $this->var_export_min($value, true);
            }
            return '['.implode(',', $toImplode).']';
        }

        return var_export($var, true);
    }
}
