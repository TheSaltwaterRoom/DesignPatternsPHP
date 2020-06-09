<?php
/**
 * Created by PhpStorm.
 * User: wangwentong
 * Date: 2020/6/9
 * Time: 5:04 下午
 */

namespace RefactoringGuru\FactoryMethod\RealWorld;

/**
 *创建者声明了一种工厂方法，可以代替
 *产品的直接构造函数调用，例如：
 *
 *-之前：$ p =新的FacebookConnector；
 *-之后：$ p = $ this-> getSocialNetwork;
 *
 *这允许更改正在创建的产品的类型
 * SocialNetworkPoster的子类。
 */
abstract class SocialNetworkPoster
{
    /**
     *实际工厂方法。 请注意，它返回抽象连接器。
     *这样子类就可以返回任何具体的连接器而不会破坏
     *超类合同。
     */
    abstract public function getSocialNetwork(): SocialNetworkConnector;

    /**
     *当在创建者的业务逻辑中使用工厂方法时，
     *子类可以通过返回不同类型的
     *出厂时的连接器。
     */
    public function post($content): void
    {
        // Call the factory method to create a Product object...
        $network = $this->getSocialNetwork();
        // ...then use it as you will.
        $network->logIn();
        $network->createPost($content);
        $network->logout();
    }
}

/**
 *该具体创建者支持Facebook。 请记住，这堂课也
 *从父类继承'post'方法。 具体的创造者是
 *客户端实际使用的类。
 */
class FacebookPoster extends SocialNetworkPoster
{
    private $login, $password;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function getSocialNetwork(): SocialNetworkConnector
    {
        return new FacebookConnector($this->login, $this->password);
    }
}

/**
 * 该具体创建者支持LinkedIn。
 */
class LinkedInPoster extends SocialNetworkPoster
{
    private $email, $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function getSocialNetwork(): SocialNetworkConnector
    {
        return new LinkedInConnector($this->email, $this->password);
    }
}

/**
 * 产品接口声明各种产品的行为。
 */
interface SocialNetworkConnector
{
    public function logIn(): void;

    public function logOut(): void;

    public function createPost($content): void;
}

/**
 * 该具体产品实现了Facebook API。
 */
class FacebookConnector implements SocialNetworkConnector
{
    private $login, $password;

    public function __construct(string $login, string $password)
    {
        $this->login = $login;
        $this->password = $password;
    }

    public function logIn(): void
    {
        echo "Send HTTP API request to log in user $this->login with " .
            "password $this->password\n";
    }

    public function logOut(): void
    {
        echo "Send HTTP API request to log out user $this->login\n";
    }

    public function createPost($content): void
    {
        echo "Send HTTP API requests to create a post in Facebook timeline.Content{ $content}\n";
    }
}

/**
 * 该具体产品实现了LinkedIn API。
 */
class LinkedInConnector implements SocialNetworkConnector
{
    private $email, $password;

    public function __construct(string $email, string $password)
    {
        $this->email = $email;
        $this->password = $password;
    }

    public function logIn(): void
    {
        echo "Send HTTP API request to log in user $this->email with " .
            "password $this->password\n";
    }

    public function logOut(): void
    {
        echo "Send HTTP API request to log out user $this->email\n";
    }

    public function createPost($content): void
    {
        echo "Send HTTP API requests to create a post in LinkedIn timeline.Content{ $content}\n";
    }
}

/**
 *客户端代码可以与SocialNetworkPoster的任何子类一起使用，因为它可以
 *不依赖于具体的类。
 */
function clientCode(SocialNetworkPoster $creator)
{
    // ...
    $creator->post("Hello world!");
    $creator->post("I had a large hamburger this morning!");
    // ...
}

/**
 *在初始化阶段，应用程序可以决定其使用的社交网络
 *希望使用，创建适当子类的对象，并将其传递给
 *客户端代码。
 */
echo "Testing ConcreteCreator1:\n";
clientCode(new FacebookPoster("john_smith", "******"));
echo "\n\n";

echo "Testing ConcreteCreator2:\n";
clientCode(new LinkedInPoster("john_smith@example.com", "******"));