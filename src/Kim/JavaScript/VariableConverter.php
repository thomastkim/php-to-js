<?php

namespace Kim\JavaScript;

use InvalidArgumentException;

class VariableConverter
{

    private $namespace;

    public function __construct($namespace = "window")
    {
        $this->namespace = $namespace;
    }

    public function convertToJavascript($key, $value)
    {
        $type = $this->getValueType($value);

        return "{$this->namespace}['{$key}']=" . $this->{"convert{$type}"}($value) . ';';
    }

    public function getValueType($value)
    {
        return $this->titleCase(gettype($value));
    }

    private function titleCase($value)
    {
        return ucfirst(strtolower($value));
    }

    private function convertArray($value)
    {
        return json_encode($value);
    }

    private function convertBoolean($value)
    {
        return ($value) ? 'true' : 'false';
    }

    private function convertDouble($value)
    {
        return $value;
    }

    private function convertInteger($value)
    {
        return $value;
    }

    private function convertNull($value)
    {
        return 'null';
    }

    private function convertObject($value)
    {
        if (method_exists($value, 'toJson')) {
            return $value->toJson();
        }

        if ($value instanceof JsonSerializable)
        {
            return json_encode($value);
        }

        throw new InvalidArgumentException('The provided object cannot be serialized.');
    }

    private function convertString($value)
    {
        return "'" . htmlspecialchars($value) . "'";
    }

}