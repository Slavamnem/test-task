<?php declare(strict_types=1);

namespace App\Enum;

abstract class AbstractEnum
{
    /** @var array<int, string> */
    protected static array $_enums;

    protected string $_value;

    /**
     * AbstractEnum constructor.
     * @param string $type
     * @throws \Exception
     */
    final public function __construct(string $type)
    {
        if (!in_array($type, static::$_enums, true)) {
            throw new \Exception(sprintf('%s not in [%s]', $type, implode(',', self::getEnumsList())));
        }
        $this->_value = $type;
    }

    /**
     * @return string[]
     */
    public static function getEnums(): array
    {
        return static::$_enums;
    }

    /**
     * @return array<string>
     */
    public static function getEnumsList(): array
    {
        return array_values(static::$_enums);
    }

    /**
     * @return array<int>
     */
    public static function getEnumsListIds(): array
    {
        return array_keys(static::$_enums);
    }

    /**
     * @param int $id
     * @return string
     */
    public static function getValueById(int $id): string
    {
        return (string)@static::$_enums[$id];
    }

    /**
     * @param string $value
     * @return int|string
     */
    public static function getIdByValue(string $value)
    {
        return array_search($value, static::$_enums, true);
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->_value;
    }

    /**
     * @return int|string
     */
    public function getId()
    {
        return array_search($this->_value, static::$_enums, true);
    }

    /**
     * @param AbstractEnum $abstractEnum
     * @return bool
     */
    public function isEqual(AbstractEnum $abstractEnum): bool
    {
        return $this->getId() === $abstractEnum->getId();
    }

    /**
     * @param int $id
     * @return AbstractEnum
     * @throws BadRequestHttpException
     */
    public static function createEnumFromId(int $id): self
    {
        return new static(static::getValueById($id));
    }

    /**
     * @param string $name
     * @param array<mixed> $arguments
     * @return self
     * @throws BadRequestHttpException
     */
    public static function __callStatic(string $name, array $arguments): self
    {
        return new static(constant("static::$name"));
    }
}
