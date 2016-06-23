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
     * Write a config to a file
     *
     * @param  string $filePath
     * @param  array|\Traversable|\Zend\Config\Config $config
     * @return AbstractListener
     */
    protected function writeConfigToFile($filePath, $config)
    {
        $content = "<?php\nreturn " . $this->var_export_min($config) . ';';
        file_put_contents($filePath, $content);
        return $this;
    }

    /**
     * Minify var_export result
     *
     * @param array|\Traversable|\Zend\Config\Config $var
     * @return string
     */
    private function var_export_min($var)
    {
        if (is_array($var)) {
            $toImplode = [];
            foreach ($var as $key => $value) {
                $toImplode[] = var_export($key, 1) . '=>' . $this->var_export_min($value);
            }

            return '['.implode(',', $toImplode).']';
        }

        return var_export($var, 1);
    }
}
