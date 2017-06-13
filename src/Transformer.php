<?php

namespace Softbox\Support;

class Transformer
{
    private $convertionRules;
    private $delimiter;

    public function __construct($convertionRules = array(), $delimiter = '.')
    {
        $this->convertionRules = $convertionRules ? $convertionRules : array();
        $this->delimiter       = $delimiter;
    }

    public function addRule($source, $target)
    {
        $this->convertionRules[$source] = $target;

        return $this;
    }

    private function get($path, $arr)
    {
        $tokens = explode($this->delimiter, $path);

        $total = count($tokens) - 1;

        $value = (array)$arr;

        $i = 0;

        for (; $i < $total; $i++) {
            if (!isset($value[$tokens[$i]])) {
                return null;
            }

            $value = (array)$value[$tokens[$i]];
        }

        return isset($value[$tokens[$i]]) ? $value[$tokens[$i]] : null;
    }

    private function set($path, $arr, $newValue)
    {
        $tokens = explode($this->delimiter, $path);

        if (null === $arr) {
            $arr = array();
        }

        $total = count($tokens) - 1;

        $arr = (array)$arr;

        $value = &$arr;

        for ($i = 0; $i < $total; $i++) {
            if (!isset($value[$tokens[$i]]) || !is_array($value[$tokens[$i]])) {
                $value[$tokens[$i]] = array();
            }

            $value = &$value[$tokens[$i]];
        }

        $value[$tokens[$i]] = $newValue;

        return $arr;
    }

    /**
     * Realiza um DE/PARA do array fornecido (este deve ser do tipo chave => valor.
     *
     * @param $data
     *
     * @return array
     */
    public function transform($data)
    {
        $ret = array();

        foreach ($this->convertionRules as $source => $target) {
            $ret = $this->set($target, $ret, $this->get($source, $data));
        }

        return $ret;
    }

    public function transformArray($arrData)
    {
        $ret = array();

        foreach ($arrData as $data) {
            $ret[] = $this->transform($data);
        }

        return $ret;
    }
}
