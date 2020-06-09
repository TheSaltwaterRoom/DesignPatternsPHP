<?php
/**
 * Created by PhpStorm.
 * User: wangwentong
 * Date: 2020/6/9
 * Time: 4:14 下午
 */

namespace RefactoringGuru\FactoryMethod\Conceptual;


/**
 * Creator类声明了工厂方法，该方法应该返回产品类的对象。创建器的子类通常提供此方法的实现。
 * Class Creator
 * @package RefactoringGuru\FactoryMethod\Conceptual
 */
abstract class Creator
{
    /**
     * 注意，创建者也可能提供一些工厂方法的默认实现。
     * @return Product
     */
    abstract public function factoryMethod(): Product;

    /**
     * 还要注意，尽管它的名字，创建者的主要职责是不创造产品。通常，它包含一些核心业务逻辑依赖于由工厂方法返回的Product对象。子类可以通过覆盖工厂方法间接更改业务逻辑返回不同类型的产品。
     * @return string
     */
    public function someOperation(): string
    {
        // Call the factory method to create a Product object.
        $product = $this->factoryMethod();
        // Now, use the product.
        $result = "Creator: The same creator's code has just worked with " .
            $product->operation();

        return $result;
    }
}


/**
 * 具体的创建程序覆盖了factory方法，以更改生成产品的类型。
 * Class ConcreteCreator1
 * @package RefactoringGuru\FactoryMethod\Conceptual
 */
class ConcreteCreator1 extends Creator
{
    /**
     * 请注意，该方法的签名仍使用抽象产品类型，即使实际从该方法返回了具体产品。 这样，创建者可以独立于具体的产品类别。
     * @return Product
     */
    public function factoryMethod(): Product
    {
        return new ConcreteProduct1;
    }
}

class ConcreteCreator2 extends Creator
{
    public function factoryMethod(): Product
    {
        return new ConcreteProduct2;
    }
}

/**
 * 产品接口声明所有具体产品必须实现的操作。
 */
interface Product
{
    public function operation(): string;
}

/**
 * 具体产品提供产品接口的各种实现。
 */
class ConcreteProduct1 implements Product
{
    public function operation(): string
    {
        return "{Result of the ConcreteProduct1}";
    }
}

class ConcreteProduct2 implements Product
{
    public function operation(): string
    {
        return "{Result of the ConcreteProduct2}";
    }
}

/**
 * 客户端代码使用具体创建者的实例，尽管通过它的基本接口。 只要客户通过与创作者保持合作基本接口，您可以将其传递给任何创建者的子类。
 * @param Creator $creator
 */
function clientCode(Creator $creator)
{
    // ...
    echo "Client: I'm not aware of the creator's class, but it still works.\n"
        . $creator->someOperation();
    // ...
}

/**
 * 应用程序根据配置或环境选择创建者的类型。
 */
echo "App: Launched with the ConcreteCreator1.\n";
clientCode(new ConcreteCreator1);
echo "\n\n";

echo "App: Launched with the ConcreteCreator2.\n";
clientCode(new ConcreteCreator2);