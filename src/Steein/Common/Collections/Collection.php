<?php
namespace Steein\Common\Collections;

use IteratorAggregate;
use CachingIterator;
use Steein\Common\Collections\Bundle\StackBundle;
use Steein\Common\Collections\Intefaces\ArrayInterface;
use Steein\Common\Collections\Intefaces\JsonInterface;
use Steein\Common\Collections\Intefaces\JsonSerialize;

/****
 * Этот файл является частью пакета Steein.
 *
 * Для получения полной информации об авторских правах и лицензии, пожалуйста, просмотрите ЛИЦЕНЗИЮ
 * Файл, который был распространен с этим исходным кодом.
 *
 * @copyright       Steein Inc
 * @author          Shamsudin Serderov <support@steein.ru>
 * @website         https://www.steein.ru
 * @since           1.0.x
 * @license         http://www.opensource.org/licenses/mit-license.php MIT License
 */
class Collection extends StackBundle implements \ArrayAccess, \Countable, ArrayInterface, JsonSerialize, JsonInterface, IteratorAggregate
{
    /****
     * Создаем конструктор
     *
     * @param array $attributes
     * @return mixed
     */
    public function __construct($attributes = [])
    {
        $this->attributes   =   $this->getArrayble($attributes);
    }

    /**
     * Создаем новый экземпляр из указанных атрибутов.
     *
     * Этот метод предоставляется для производных классов, чтобы указать, как новый
     * Экземпляр должен быть создан при изменении семантики конструктора.
     *
     * @param array $attributes
     *
     * @return static
     */
    public static function instance($attributes = [])
    {
        return new static($attributes);
    }

    /****
     * Получаем все атрибуты
     *
     * @return array
    */
    public function all()
    {
        return $this->attributes;
    }

    /**
     * Получить первый элемент из коллекции.
     *
     * @return mixed
     */
    public function first()
    {
        return reset($this->attributes);
    }

    /****
     * Получаем последний элемент из коллекции
     *
     * @return mixed
    */
    public function last()
    {
        return end($this->attributes);
    }

    /****
     * Получение ключей из коллекции
     *
     * @return mixed
    */
    public function key()
    {
        return key($this->attributes);
    }

    /***
     *  Передвигаем указатель массива на одну позицию вперёд
     *
     * @return mixed
    */
    public function next()
    {
        return next($this->attributes);
    }

    /****
     * Получаем элемент с заданным смещением.
     *
     * @param null $key
     * @return mixed
     */
    public function offsetGet($key = null)
    {
        return $this->attributes[$key];
    }

    /****
     * Устанавливает элемент с заданным смещением.
     *
     * @param null $key
     * @param null $value
     * @return void
     */
    public function offsetSet($key = null, $value = null)
    {
        if(is_null($key))
            $this->attributes[] =   $value;
        else
            $this->attributes[$key] =   $value;
    }

    /****
     * Сброс позиции при заданном смещении.
     *
     * @param null $key
     * @return void
     */
    public function offsetUnset($key = null)
    {
        unset($this->attributes[$key]);
    }

    /**
     * Возьмите первый или последний {$limit} атрибут.
     *
     * @param  int  $limit
     * @return static
     */
    public function take($limit = 0)
    {
        if($limit < 0)
            return $this->slice($limit, abs($limit));

        return $this->slice(0, $limit);
    }

    /****
     * Осуществляем поиск определенного значения в коллекции
     *
     * @param array $attributes
     * @return mixed
     */
    public function indexOf($attributes = [])
    {
        return array_search($attributes, $this->attributes, true);
    }

    /****
     * Получаем ключи от атрибутов коллекции.
     *
     * @return mixed
    */
    public function keys()
    {
        return array_keys($this->attributes);
    }

    /****
     * Получаем значения от предметов коллекции
     *
     * @return mixed
    */
    public function values()
    {
        return new static(array_values($this->attributes));
    }

    /****
     * Запускаем карту для каждого элемента.
     *
     * @param callable $callable
     * @return mixed
     */
    public function map(callable $callable)
    {
        $keys = $this->keys();
        $items = array_map($callable, $this->attributes, $keys);

        return new static(array_combine($keys, $items));
    }

    /**
     * Запустите ассоциативную карту над каждым из атрибутов.
     * Обратный вызов должен возвращать ассоциативный массив с единственной парой ключ / значение.
     *
     * @param callable $callable
     * @return static
     */
    public function mapWithKeys(callable $callable)
    {
        $result = [];

        foreach ($this->attributes as $key => $attribute)
        {
            $assoc = $callable($attribute, $key);

            foreach ($assoc as $mapKey => $value)
            {
                $result[$mapKey]    =   $value;
            }
        }

        return new static($result);
    }

    /****
     * Получаем итератор для атрибутов.
     *
     * @return \ArrayIterator
    */
    public function getIterator()
    {
        return new \ArrayIterator($this->attributes);
    }

    /**
     * Переварачиваем элементы в коллекции.
     *
     * @return static
     */
    public function flip()
    {
        return new static(array_flip($this->attributes));
    }

    /**
     * Проверяем, существует ли указанный элемент в общем массиве атрибутов
     *
     * @param  mixed  $key
     * @return bool
     */
    public function offsetExists($key = null)
    {
        return array_key_exists($key, $this->attributes);
    }

    /****
     *  Подсчитываем количество элементов в коллекции.
     *
     * @return integer
    */
    public function count()
    {
        return count($this->attributes);
    }

    /****
     * Очистка атрибутов в коллекции
     *
     * @return void
    */
    public function clear()
    {
        $this->attributes   =   [];
    }

    /****
     * Поместите элемент в коллекцию по ключу.
     *
     * @param null $key
     * @param null $value
     * @return \Steein\Common\Collections\Collection
     */
    public function put($key = null, $value = null)
    {
        $this->offsetSet($key, $value);
        return $this;
    }

    /****
     * Архивируйте коллекцию вместе с одним или несколькими массивами.
     *
     * @param $attributes
     * @return static
     */
    public function archive($attributes)
    {
        $arraybleAttributes = array_map(function($attributes) {
           return $this->getArrayble($attributes);
        }, func_get_args());

        $params = array_merge([function() {
            return new static(func_get_args());
        }, $this->attributes], $arraybleAttributes);

        return new static(call_user_func_array('array_map', $params));
    }

    /***
     * Перетаскиваем элемент в конец коллекции.
     *
     * @param $value
     * @return \Steein\Common\Collections\Collection
     */
    public function push($value)
    {
        $this->offsetSet(null, $value);

        return $this;
    }

    /****
     * Получаем и удаляем последний элемент из коллекции.
     *
     * @return mixed
    */
    public function pop()
    {
        return array_pop($this->attributes);
    }

    /****
     * Передайте коллекцию данному ответному вызову и верните результат.
     *
     * @param  callable $callback
     * @return mixed
    */
    public function pipe(callable $callback)
    {
        return $callback($this);
    }

    /**
     * Получить элементы в коллекции, которых нет в данных атрибутах.
     *
     * @param  mixed  $attributes
     * @return static
     */
    public function diff($attributes = [])
    {
        return new static(array_diff($this->attributes, $this->getArrayble($attributes)));
    }

    /**
     * Получить элементы коллекции, ключи которых отсутствуют в данных атрибутах.
     *
     * @param  mixed  $attributes
     * @return static
     */
    public function diffKeys($attributes = [])
    {
        return new static(array_diff_key($this->attributes, $this->getArrayble($attributes)));
    }


    /****
     * Выполните обратный вызов для каждого атрибута.
     *
     * @param callable $callable
     * @return \Steein\Common\Collections\Collection
     */
    public function each(callable $callable)
    {
        foreach ($this->attributes as $key => $value)
        {
            if($callable($value, $key) === false)
                break;
        }

        return $this;
    }


    /****
     * Примените обратный вызов, если значение является истинным.
     *
     * @param  bool $value
     * @param  callable $callable
     * @param  callable $default
     * @return \Steein\Common\Collections\Collection
     */
    public function when($value, callable $callable, callable $default = null)
    {
        if($value)
            return $callable($this);
        elseif($default)
            return $default($this);

        return $this;
    }

    /****
     * Удалить элемент из коллекции по ключу.
     *
     * @param  null $keys
     * @return \Steein\Common\Collections\Collection
     */
    public function forget($keys = null)
    {
        foreach ((array)$keys as $key) {
            $this->offsetUnset($key);
        }

        return $this;
    }


    /****
     * Определите, существует ли элемент в коллекции по ключу.
     *
     * @param null $key
     * @return bool
     */
    public function has($key = null)
    {
        return $this->offsetExists($key);
    }

    /****
     * Пересечение коллекции с данными элементами.
     *
     * @param $attributes
     * @return static
     */
    public function intersect($attributes)
    {
        return new static(array_intersect($this->attributes, $this->getArrayble($attributes)));
    }

    /****
     * Определите, является ли коллекция пустой или нет.
     *
     * @return mixed
    */
    public function isEmpty()
    {
        return empty($this->attributes);
    }

    /****
     * Определите, не является ли коллекция пустой.
     *
     * @return bool
    */
    public function isNotEmpty()
    {
        return !$this->isEmpty();
    }

    /****
     * Объедините коллекцию с данными элементами.
     *
     * @param $attributes
     * @return static
     */
    public function merge($attributes)
    {
        return new static(array_merge($this->attributes, $this->getArrayble($attributes)));
    }

    /****
     * Создайте коллекцию, используя эту коллекцию для ключей, а другую - для ее значений.
     *
     * @param $values
     * @return static
     */
    public function combine($values)
    {
        return new static(array_combine($this->all(), $this->getArrayble($values)));
    }

    /****
     * Союз коллекций с данными элементами.
     *
     * @param $attributes
     * @return static
     */
    public function union($attributes)
    {
        return new static($this->attributes + $this->getArrayble($attributes));
    }

    /****
     * Создаем новую коллекцию, состоящую из каждого n-го атрибута.
     *
     * @param $step
     * @param int $offset
     * @return static
     */
    public function nth($step, $offset = 0)
    {
        $new = [];

        $position = 0;

        foreach ($this->attributes as $attribute)
        {
            if($position % $step == $offset)
                $new[] = $attribute;

            $position++;
        }

        return new static($new);
    }

    /****
     * Определяет, является ли данное значение допустимым, но не является строкой.
     *
     * @param $value
     * @return bool
     */
    public function useAsCallable($value)
    {
        return !is_string($value) && is_callable($value);
    }

    /****
     * Преобразуйте каждый элемент в коллекции с помощью обратного вызова.
     *
     * @param callable $callable
     * @return $this
     */
    public function transform(callable $callable)
    {
        $this->attributes = $this->map($callable)->all();
        return $this;
    }

    /****
     * Получить базовый экземпляр коллекции поддержки из этой коллекции.
     *
     * @return \Steein\Common\Collections\Collection
    */
    public function base()
    {
        return new self($this);
    }

    /****
     * Передайте коллекцию данному обратному вызову, а затем верните ее.
     *
     * @param callable $callable
     * @return $this
     */
    public function tap(callable $callable)
    {
        $callable(new static($this->attributes));
        return $this;
    }

    /****
     * Создайте основной массив коллекции.
     *
     * @param int $offset
     * @param int $length
     * @return static
     */
    public function slice($offset = 0, $length  = 0)
    {
        return new static(array_splice($this->attributes, $offset, $length, true));
    }

    /****
     * Получить элемент из коллекции по ключу.
     *
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        if($this->offsetExists($key))
            return $this->attributes[$key];

        return null;
    }

    /****
     * Новый элемент для коллекции
     *
     * @param $key
     * @param $value
     * @return \Steein\Common\Collections\Collection
     */
    public function set($key, $value)
    {
        $this->offsetSet($key, $value);

        return $this;
    }

    /****
     * Преобразуйте коллекцию в ее строковое представление.
     *
     * @return string
    */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * Магический метод: Проверяем существует ли выбранное значение в коллекции
     *
     * @param $key
     * @return bool
     * @abstracting ArrayAccess
     */
    public function __isset($key) {
        return isset($this->attributes[$key]);
    }

    /****
     * Магический метод создания нового элемента для коллекции
     *
     * @param $key
     * @param $value
     * @abstracting ArrayAccess
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    /***
     * Магический метод получения определенного элемента из коллекции
     *
     * @param null $key
     * @return mixed
     * @abstracting ArrayAccess
     */
    public function __get($key = null)
    {
        return $this->get($key);
    }
}