<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class PHPtoJS {

    /**
     * The namespace to nest JS vars under.
     *
     * @var string
     */
    protected $namespace;

    /**
     * variables to the views.
     *
     * @var jsToview
     */
    protected $jsToView;

    /**
     * All transformable types.
     *
     * @var array
     */
    protected $types = [
        'String',
        'Array',
        'Object',
        'Numeric',
        'Boolean',
        'Null'
    ];

    /**
     * Create a new JS transformer instance.
     *
     * @param string     $namespace
     */
    function __construct($namespace = 'window') {
        
        $this->namespace = is_array($namespace) ? $namespace['namespace'] : $namespace;
    }

    /**
     * Store the given array of variables to the jsToview.
     *
     * @param array $variables
     */
    public function put(array $variables) {
        // First, we have to translate the variables
        // to something JS-friendly.
        $this->jsToView = $this->buildJavaScriptSyntax($variables);

    }
    
    /**
     * returns the javascript variables.
     *
     * @return string
     */    
    public function getJsVars() {
        return '<script type="text/javascript">'.$this->jsToView.'</script>';
    }

    /**
     * Translate the array of PHP vars to
     * the expected JavaScript syntax.
     *
     * @param  array $vars
     * @return array
     */
    public function buildJavaScriptSyntax(array $vars) {
        $js = $this->buildNamespaceDeclaration();

        foreach ($vars as $key => $value) {
            $js .= $this->buildVariableInitialization($key, $value);
        }

        return $js;
    }

    /**
     * Create the namespace that all
     * vars will be nested under.
     *
     * @return string
     */
    protected function buildNamespaceDeclaration() {
        return "window.{$this->namespace} = window.{$this->namespace} || {};";
    }

    /**
     * Translate a single PHP var to JS.
     *
     * @param  string $key
     * @param  string $value
     * @return string
     */
    protected function buildVariableInitialization($key, $value) {
        return "{$this->namespace}.{$key} = {$this->optimizeValueForJavaScript($value)};";
    }

    /**
     * Format a value for JavaScript.
     *
     * @param  string $value
     * @throws \Exception
     * @return mixed
     */
    protected function optimizeValueForJavaScript($value) {
        // For every transformable type, let's see if
        // it needs to be converted for JS-used.
        foreach ($this->types as $transformer) {
            $js = $this->{"transform{$transformer}"}($value);

            if (!is_null($js)) {
                return $js;
            }
        }
    }

    /**
     * Transform a string.
     *
     * @param  string $value
     * @return string
     */
    protected function transformString($value) {
        if (is_string($value)) {
            return "'{$this->escape($value)}'";
        }
    }

    /**
     * Transform an array.
     *
     * @param  array  $value
     * @return string
     */
    protected function transformArray($value) {
        if (is_array($value)) {
            return json_encode($value);
        }
    }

    /**
     * Transform a numeric value.
     *
     * @param  mixed $value
     * @return mixed
     */
    protected function transformNumeric($value) {
        if (is_numeric($value)) {
            return $value;
        }
    }

    /**
     * Transform a boolean.
     *
     * @param  boolean $value
     * @return string
     */
    protected function transformBoolean($value) {
        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }
    }

    /**
     * Transform an object.
     *
     * @param  object $value
     * @return string
     * @throws \Exception
     */
    protected function transformObject($value) {
        if (is_object($value)) {
            // If a toJson() method exists, we'll assume that
            // the object can cast itself automatically.
            if (method_exists($value, 'toJson')) {
                return $value;
            }

            // Otherwise, if the object doesn't even have
            // a toString method, we can't proceed.
            if (!method_exists($value, '__toString')) {
                throw new Exception('The provided object needs a __toString() method.');
            }

            return "'{$value}'";
        }
    }

    /**
     * Transform "null."
     *
     * @param  mixed $value
     * @return string
     */
    protected function transformNull($value) {
        if (is_null($value)) {
            return 'null';
        }
    }

    /**
     * Escape any single quotes.
     *
     * @param  string $value
     * @return string
     */
    protected function escape($value) {
        return str_replace(["\\", "'"], ["\\\\", "\'"], $value);
    }

}
