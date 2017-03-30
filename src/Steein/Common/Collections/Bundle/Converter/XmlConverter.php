<?php
namespace Steein\Common\Collections\Bundle\Converter;

use DOMElement;
use DOMDocument;
use DOMException;

class XmlConverter
{
    /**
     * Корневой документ DOM.
     *
     * @var \DOMDocument
     */
    protected $document;

    /**
     * Включить замену пробела подчеркиванием.
     *
     * @var bool
     */
    protected $replaceSpacesByUnderScoresInKeyNames = true;

    /**
     * Создайте новый экземпляр.
     *
     * @param string[] $array
     * @param string   $rootElementName
     * @param bool     $replaceSpacesByUnderScoresInKeyNames
     *
     * @throws DOMException
     */
    public function __construct($array, $rootElementName = '', $replaceSpacesByUnderScoresInKeyNames = true)
    {
        $this->document = new DOMDocument();
        $this->replaceSpacesByUnderScoresInKeyNames = $replaceSpacesByUnderScoresInKeyNames;

        if ($this->isArrayAllKeySequential($array) && ! empty($array)) {
            throw new DOMException('Invalid Character Error');
        }

        $root = $this->document->createElement($rootElementName == '' ? 'root' : $rootElementName);

        $this->document->appendChild($root);

        $this->convertElement($root, $array);
    }

    /**
     * Преобразует заданный массив в строку xml.
     *
     * @param string[] $array
     * @param string   $rootElementName
     * @param bool     $replaceSpacesByUnderScoresInKeyNames
     *
     * @return string
     */
    public static function convert(array $array, $rootElementName = '', $replaceSpacesByUnderScoresInKeyNames = true)
    {
        $converter = new static($array, $rootElementName, $replaceSpacesByUnderScoresInKeyNames);

        return $converter->toXml();
    }

    /**
     * Вернуть как XML.
     *
     * @return string
     */
    public function toXml()
    {
        return $this->document->saveXML();
    }

    /**
     * Вернуть как DOM объект.
     *
     * @return DOMDocument
     */
    public function toDom()
    {
        return $this->document;
    }

    /**
     * Выполнить разбор отдельных элементов.
     *
     * @param \DOMElement     $element
     * @param string|string[] $value
     */
    private function convertElement(DOMElement $element, $value)
    {
        $sequential = $this->isArrayAllKeySequential($value);

        if (! is_array($value)) {
            $element->nodeValue = htmlspecialchars($value);

            return;
        }

        foreach ($value as $key => $data) {
            if (! $sequential) {
                if (($key === '_attributes') || ($key === '@attributes')) {
                    $this->addAttributes($element, $data);
                } elseif ((($key === '_value') || ($key === '@value')) && is_string($data)) {
                    $element->nodeValue = htmlspecialchars($data);
                } else {
                    $this->addNode($element, $key, $data);
                }
            } elseif (is_array($data)) {
                $this->addCollectionNode($element, $data);
            } else {
                $this->addSequentialNode($element, $data);
            }
        }
    }

    /**
     * Добавить узел.
     *
     * @param \DOMElement     $element
     * @param string          $key
     * @param string|string[] $value
     */
    protected function addNode(DOMElement $element, $key, $value)
    {
        if ($this->replaceSpacesByUnderScoresInKeyNames) {
            $key = str_replace(' ', '_', $key);
        }

        $child = $this->document->createElement($key);
        $element->appendChild($child);
        $this->convertElement($child, $value);
    }

    /**
     * Добавить узел сбора.
     *
     * @param \DOMElement     $element
     * @param string|string[] $value
     *
     * @internal param string $key
     */
    protected function addCollectionNode(DOMElement $element, $value)
    {
        if ($element->childNodes->length == 0) {
            $this->convertElement($element, $value);

            return;
        }

        $child = $element->cloneNode();
        $element->parentNode->appendChild($child);
        $this->convertElement($child, $value);
    }

    /**
     * Добавить последовательный узел.
     *
     * @param \DOMElement     $element
     * @param string|string[] $value
     *
     * @internal param string $key
     */
    protected function addSequentialNode(DOMElement $element, $value)
    {
        if (empty($element->nodeValue)) {
            $element->nodeValue = htmlspecialchars($value);

            return;
        }

        $child = $element->cloneNode();
        $child->nodeValue = htmlspecialchars($value);
        $element->parentNode->appendChild($child);
    }

    /**
     * Проверьте, все ли массивы последовательны.
     *
     * @param array|string $value
     *
     * @return bool
     */
    protected function isArrayAllKeySequential($value)
    {
        if (! is_array($value)) {
            return false;
        }

        if (count($value) <= 0) {
            return true;
        }

        return array_unique(array_map('is_int', array_keys($value))) === [true];
    }

    /**
     * Добавить атрибуты.
     *
     * @param \DOMElement $element
     * @param string[]    $data
     */
    protected function addAttributes($element, $data)
    {
        foreach ($data as $attrKey => $attrVal) {
            $element->setAttribute($attrKey, $attrVal);
        }
    }
}