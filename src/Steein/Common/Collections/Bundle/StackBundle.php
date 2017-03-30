<?php
namespace Steein\Common\Collections\Bundle;


use Steein\Common\Collections\Intefaces\ArrayInterface;
use Steein\Common\Collections\Intefaces\JsonInterface;
use Steein\Common\Collections\Intefaces\JsonSerialize;
use Steein\Common\Collections\Bundle\Converter\XmlConverter;

abstract class StackBundle
{
    /****
     * Атрибуты, содержащиеся в коллекции.
     *
     * @return array
     */
    protected $attributes   =   [];

    /**
     * Массив результатов из коллекции или массива.
     *
     * @param array $attributes
     * @return array
     * @internal param mixed $items
     */
    protected function getArrayble($attributes = [])
    {
        if(is_array($attributes))
        {
            return $attributes;
        } elseif($attributes instanceof self)
        {
            return $attributes->all();
        }elseif($attributes instanceof ArrayInterface)
        {
            return $attributes->toArray();
        }elseif($attributes instanceof JsonInterface)
        {
            return json_decode($attributes->toJson(), true);
        }elseif($attributes instanceof JsonSerialize)
        {
            return $attributes->jsonSerialize();
        }
        elseif($attributes instanceof \Traversable)
        {
            return iterator_to_array($attributes);
        }

        return (array) $attributes;
    }

    /**
     * Преобразование объектов в формат JSON
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_map(function($value)
        {
            if($value instanceof JsonSerialize)
                return $value->jsonSerialize();
            elseif($value instanceof  JsonInterface)
                return json_decode($value->toJson(), true);
            elseif($value instanceof ArrayInterface)
                return $value->toArray();
            else
                return $value;

        }, $this->attributes);
    }

    /**
     * Получение коллекций элементов в формате JSON.
     *
     * @param  int  $options
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->jsonSerialize(), $options);
    }

    /****
     * Получение коллекций элеметов в формате String
     *
     * @param string $delimiter
     * @return string
     */
    public function toString($delimiter = ',')
    {
        $result = [];
        array_walk_recursive($this->attributes, function($v) use (&$result) {
            $result[] = $v;
        });

        return \implode($delimiter, $result);
    }

    /**
     * Получение коллекций элементов в формате Array.
     *
     * @return array
     */
    public function toArray()
    {
        return array_map(function($value) {
            return $value instanceof ArrayInterface ? $value->toArray() : $value;
        }, $this->attributes);
    }

    /**
     * Получение коллекций элементов в формате XML.
     *
     * @return string
     */
    public function toXml()
    {
        return XmlConverter::convert($this->attributes);
    }
}